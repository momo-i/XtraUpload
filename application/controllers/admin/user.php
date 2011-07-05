<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package	 XtraUpload
 * @author	  Matthew Glinski
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @license	 http://xtrafile.com/docs/license
 * @link		http://xtrafile.com
 * @since	   Version 2.0
 * @filesource
 */

/**
 * XtraUpload User Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('users/users_db');
		$this->load->library('form_validation');
		$this->load->helper('form');
	}

	public function index()
	{
		redirect('admin/user/view');
	}

	public function manage()
	{
		redirect('admin/user/view');
	}

	public function home()
	{
		redirect('admin/user/view');
	}

	public function view()
	{
		$this->load->library('pagination');
		$this->load->helper('admin/sort');
		$this->load->helper('string');

		$sort = $this->session->userdata('user_sort');
		$direction = $this->session->userdata('user_direction');
		$per_page = $this->session->userdata('user_count');

		if( ! $per_page)
		{
			$per_page = 50;
			$this->session->set_userdata('user_count', $per_page);
		}

		if( ! $sort)
		{
			$sort = 'id';
			$this->session->set_userdata('user_sort', $sort);
		}

		if( ! $direction)
		{
			$direction = 'desc';
			$this->session->set_userdata('user_direction', $direction);
		}

		$data['flash_message'] = '';
		$data['sort'] = $sort;
		$data['direction'] = $direction;
		$data['per_page'] = $per_page;

		$config['base_url'] = site_url('admin/user/view');
		$config['total_rows'] = $this->users_db->get_num_users();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);

		$data['users'] = $this->users_db->get_all_users($sort, $direction, $per_page, $this->uri->segment(4));

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<p><span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span></p>';
		}

		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Users')));
		$this->load->view($this->startup->skin.'/admin/users/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function search($query='')
	{
		$this->load->helper('string');
		if( ! empty($query))
		{
			$this->load->library('pagination');
			$this->load->helper('admin/sort');

			$query = urldecode($query);

			$sort = $this->session->userdata('user_sort');
			$direction = $this->session->userdata('user_direction');
			$per_page = $this->session->userdata('user_count');

			if( ! $per_page)
			{
				$per_page = 50;
				$this->session->set_userdata('user_count', $per_page);
			}

			if( ! $sort)
			{
				$sort = 'id';
				$this->session->set_userdata('user_sort', $sort);
			}

			if( ! $direction)
			{
				$direction = 'desc';
				$this->session->set_userdata('user_direction', $direction);
			}

			$results_num = $this->users_db->get_num_users_search($query);

			$data['flash_message'] = '';
			$data['sort'] = $sort;
			$data['direction'] = $direction;
			$data['res_num'] = $results_num;
			$data['query'] = $query;
			$data['per_page'] = $per_page;

			$config['base_url'] = site_url('admin/user/view');
			$config['total_rows'] = $results_num;
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);

			$data['users'] = $this->users_db->get_all_users_search($query, $sort, $direction, $per_page, $this->uri->segment(4));

			if($this->session->flashdata('msg'))
			{
				$data['flash_message'] = '<p><span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span></p>';
			}

			$data['pagination'] = $this->pagination->create_links();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Search Files')));
			$this->load->view($this->startup->skin.'/admin/users/search_result',$data);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Search Users')));
			$this->load->view($this->startup->skin.'/admin/users/search');
			$this->load->view($this->startup->skin.'/footer');
		}
	}

	// ------------------------------------------------------------------------

	public function edit($id)
	{
		$config = array(
			array(
				'field' => 'email',
				'label' => lang('Email'),
				'rules' => 'trim|valid_email'
			),
			array(
				'field' => 'username',
				'label' => lang('Username'),
				'rules' => 'trim|callback_check_user'
			),
			array(
				'field' => 'password',
				'label' => lang('Password'),
				'rules' => 'trim|min_length[5]|max_length[70]'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();
			if($this->input->post('edited'))
			{
				$data['error'] = '<span class="alert"><strong>'.lang('Error:').'</strong><br><ul>'.$error.'</ul></span>';
			}
			else
			{
				$data['error'] = '';
			}
		}
		else
		{
			$this->users_db->edit_user($id, $this->input->post('username'),  $this->input->post('password'), $this->input->post('email'), $this->input->post('group'));
			$this->session->set_flashdata('msg', lang('User Edited!'));
			redirect('/admin/user/view');
			return true;
		}

		$data['user'] = $this->users_db->get_user_by_id($id);
		$data['groups'] = $this->db->get('groups');
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit User')));
		$this->load->view($this->startup->skin.'/admin/users/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function add()
	{
		$config = array(
			array(
				'field' => 'email',
				'label' => lang('Email'),
				'rules' => 'trim|valid_email'
			),
			array(
				'field' => 'username',
				'label' => lang('Username'),
				'rules' => 'trim|min_length[5]|max_length[70]'
			),
			array(
				'field' => 'password',
				'label' => lang('Password'),
				'rules' => 'trim|min_length[5]|max_length[70]'
			)
		);
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();
			if($this->input->post('edited'))
			{
				$data['error'] = '<span class="alert"><strong>'.lang('Error:').'</strong><br><ul>'.$error.'</ul></span>';
			}
			else
			{
				$data['error'] = '';
			}
		}
		else
		{
			// hash the password before it goes in the database
			$_POST['password'] = md5($this->config->config['encryption_key'].$this->input->post('password'));

			$this->users_db->add_user($_POST);
			$this->session->set_flashdata('msg', lang('New User Added!'));
			redirect('/admin/user/view');
		}

		$data['groups'] = $this->db->select('id, name')->get('groups');
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Add User')));
		$this->load->view($this->startup->skin.'/admin/users/add', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function delete($id)
	{
		$this->users_db->delete_user($id);
		$this->session->set_flashdata('msg', lang('User has been deleted'));
		redirect('admin/user/view');
	}

	public function turn_off($id)
	{
		$this->users_db->set_user_status(0, $id);
		$this->session->set_flashdata('msg', lang('User has been Deactivated'));
		redirect('admin/user/view');
	}

	public function turn_on($id)
	{
		$this->users_db->set_user_status(1, $id);
		$this->session->set_flashdata('msg', lang('User has been Activated'));
		redirect('admin/user/view');
	}

	public function sort()
	{
		if($this->input->post('sort'))
		{
			$sort = $this->input->post('sort');
			$this->session->set_userdata('user_sort', $sort);
		}

		if($this->input->post('direction'))
		{
			$direction = $this->input->post('direction');
			$this->session->set_userdata('user_direction', $direction);
		}

		redirect('admin/user');
	}

	public function count()
	{
		if($this->input->post('user_count'))
		{
			$count = $this->input->post('user_count');
			$this->session->set_userdata('user_count', $count);
		}

		redirect('admin/user/view');
	}

	public function search_count($query)
	{
		if($this->input->post('user_count'))
		{
			$count = $this->input->post('user_count');
			$this->session->set_userdata('user_count', $count);
		}

		redirect('admin/user/search/'.$query);
	}

	public function mass_delete($query='')
	{
		if($this->input->post('users') and is_array($this->input->post('users')))
		{
			foreach($this->input->post('users') as $id)
			{
				$this->users_db->delete_user($id);
			}
			$this->session->set_flashdata('msg', sprintf(lang('%d User(s) have been deleted'), count($this->input->post('users'))));
		}
		if( ! empty($query))
		{
			redirect('admin/user/search/'.$query);
		}
		else
		{
			redirect('admin/user/view');
		}
	}

	public function check_user()
	{
		$query = $this->db->get_where('users', array('username' => $this->input->post('username')));
		$num = $query->num_rows();
		if($num != 1)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}

/* End of file admin/user.php */
/* Location: ./application/controllers/admin/user.php */

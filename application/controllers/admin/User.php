<?php
/* vim: set ts=4 sw=4 sts=0: */

/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package		XtraUpload
 * @author		Matthew Glinski
 * @copyright	Copyright (c) 2006, XtraFile.com
 * @license		http://xtrafile.com/docs/license
 * @link		http://xtrafile.com
 * @since		Version 2.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * XtraUpload User Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class User extends CI_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @see		Admin_access
	 * @see		Users_db
	 * @see		XU_Form_validation
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('users/users_db');
		$this->load->library('form_validation');
		$this->load->helper('form');
	}

	/**
	 * User::index()
	 *
	 * Redirect User::view()
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		redirect('admin/user/view');
	}

	/**
	 * User::manage()
	 *
	 * Redirect User::view()
	 *
	 * @access	public
	 * @return	void
	 */
	public function manage()
	{
		redirect('admin/user/view');
	}

	/**
	 * User::home()
	 *
	 * Redirect User::view()
	 *
	 * @access	public
	 * @return	void
	 */
	public function home()
	{
		redirect('admin/user/view');
	}

	/**
	 * User::view()
	 *
	 * Show user account page
	 *
	 * @access	public
	 * @return	void
	 */
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
		$config['attributes'] = array('class' => 'prevnext');
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

	/**
	 * User::search()
	 *
	 * Show user search page
	 *
	 * @access	public
	 * @param	string	$query	Search string
	 * @return	void
	 */
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

	/**
	 * User::edit()
	 *
	 * Edit user
	 *
	 * @access	public
	 * @param	int		$id	User ID
	 * @return	void
	 */
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
			$this->users_db->edit_user($id, $this->input->post('username'),  $this->input->post('password'), $this->input->post('email'), $this->input->post('group'), $this->input->post('locale'));
			$this->session->set_flashdata('msg', lang('User Edited!'));
			redirect('/admin/user/view');
			return true;
		}

		$data['user'] = $this->users_db->get_user_by_id($id);
		$data['groups'] = $this->db->get('groups');
		$data['locales'] = available_lang();
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit User')));
		$this->load->view($this->startup->skin.'/admin/users/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * User::add()
	 *
	 * Add user
	 *
	 * @access	public
	 * @return	void
	 */
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

	/**
	 * User::delete()
	 *
	 * Delete user
	 *
	 * @access	public
	 * @param	int		$id	User ID
	 * @return	void
	 */
	public function delete($id)
	{
		$this->users_db->delete_user($id);
		$this->session->set_flashdata('msg', lang('User has been deleted'));
		redirect('admin/user/view');
	}

	/**
	 * User::turn_off()
	 *
	 * Disable user account
	 *
	 * @access	public
	 * @param	int		$id	User ID
	 * @return	void
	 */
	public function turn_off($id)
	{
		$this->users_db->set_user_status(0, $id);
		$this->session->set_flashdata('msg', lang('User has been Deactivated'));
		redirect('admin/user/view');
	}

	/**
	 * User::turn_on()
	 *
	 * Enable user account
	 *
	 * @access	public
	 * @param	int		$id	User ID
	 * @return	void
	 */
	public function turn_on($id)
	{
		$this->users_db->set_user_status(1, $id);
		$this->session->set_flashdata('msg', lang('User has been Activated'));
		redirect('admin/user/view');
	}

	/**
	 * User::sort()
	 *
	 * Sort user account
	 *
	 * @access	public
	 * @return	void
	 */
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

	/**
	 * User::count()
	 *
	 * Count users
	 *
	 * @access	public
	 * @return	void
	 */
	public function count()
	{
		if($this->input->post('user_count'))
		{
			$count = $this->input->post('user_count');
			$this->session->set_userdata('user_count', $count);
		}

		redirect('admin/user/view');
	}

	/**
	 * User::search_count()
	 *
	 * Count search user
	 *
	 * @access	public
	 * @param	string	$query	Search string
	 * @return	void
	 */
	public function search_count($query)
	{
		if($this->input->post('user_count'))
		{
			$count = $this->input->post('user_count');
			$this->session->set_userdata('user_count', $count);
		}

		redirect('admin/user/search/'.$query);
	}

	/**
	 * User::mass_delete()
	 *
	 * Mass user delete
	 *
	 * @access	public
	 * @param	string	$query	Search string
	 * @return	void
	 */
	public function mass_delete($query='')
	{
		if($this->input->post('users') and is_array($this->input->post('users')))
		{
			foreach($this->input->post('users') as $id)
			{
				$this->users_db->delete_user($id);
			}
			$count = count($this->input->post('users'));
			$this->session->set_flashdata('msg', sprintf(nlang('%d user has been deleted', '%d users have been deleted', $count), $count));
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

	/**
	 * User::check_user()
	 *
	 * User check callback
	 *
	 * @access	public
	 * @return	bool	true|false
	 */
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

/* End of file admin/User.php */
/* Location: ./application/controllers/admin/User.php */

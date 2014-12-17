<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @author      momo-i
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @copyright   Copyright (c) 2011-, www.momo-i.org
 * @license     http://www.opensource.org/licenses/Apache-2.0
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

/**
 * XtraUpload Files Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        https://gitorious.org/xtraupload-v3
 */
class Files extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	public function index()
	{
		redirect('admin/files/view');
	}

	public function view()
	{
		$this->load->library('pagination');
		$this->load->helper('admin/sort');
		$this->load->helper('string');
		$this->load->helper('date');

		$sort = $this->session->userdata('file_sort');
		$direction = $this->session->userdata('file_direction');
		$per_page = $this->session->userdata('file_count');

		if( ! $per_page)
		{
			$per_page = 50;
			$this->session->set_userdata('file_count', $per_page);
		}

		if( ! $sort)
		{
			$sort = 'time';
			$this->session->set_userdata('file_sort', $sort);
		}

		if( ! $direction)
		{
			$direction = 'desc';
			$this->session->set_userdata('file_direction', $direction);
		}

		$data['sort'] = $sort;
		$data['direction'] = $direction;
		$data['flash_message'] = '';
		$data['per_page'] = $per_page;

		$config['base_url'] = site_url('admin/files/view');
		$config['total_rows'] = $this->files_db->get_admin_num_files();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;

		$this->pagination->initialize($config);

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['files'] = $this->files_db->get_admin_files($sort, $direction, $per_page, $this->uri->segment(4));
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Files')));
		$this->load->view($this->startup->skin.'/admin/files/view',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function folder($folder_id)
	{
		$this->load->library('pagination');
		$this->load->helper('admin/sort');
		$this->load->helper('string');
		$this->load->helper('date');

		$sort = $this->session->userdata('file_sort');
		$direction = $this->session->userdata('file_direction');
		$per_page = $this->session->userdata('file_count');

		if( ! $per_page)
		{
			$per_page = 50;
			$this->session->set_userdata('file_count', $per_page);
		}

		if( ! $sort)
		{
			$sort = 'time';
			$this->session->set_userdata('file_sort', $sort);
		}

		if( ! $direction)
		{
			$direction = 'desc';
			$this->session->set_userdata('file_direction', $direction);
		}

		$data['sort'] = $sort;
		$data['direction'] = $direction;
		$data['flash_message'] = '';
		$data['per_page'] = $per_page;

		$config['base_url'] = site_url('admin/files/view');
		$config['total_rows'] = $this->files_db->get_admin_num_filesInFolder($folder_id);
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;

		$this->pagination->initialize($config);

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['files'] = $this->files_db->get_admin_file_in_folder($folder_id, $sort, $direction, $per_page, $this->uri->segment(4));
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Files in Folder')));
		$this->load->view($this->startup->skin.'/admin/files/folder',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function search($query='')
	{
		if( ! empty($query))
		{
			$this->load->library('pagination');
			$this->load->helper('admin/sort');
			$this->load->helper('string');
			$this->load->helper('date');

			$query = urldecode($query);

			$sort = $this->session->userdata('file_sort');
			$direction = $this->session->userdata('file_direction');
			$per_page = $this->session->userdata('file_count');

			if( ! $per_page)
			{
				$per_page = 50;
				$this->session->set_userdata('file_count', $per_page);
			}

			if( ! $sort)
			{
				$sort = 'time';
				$this->session->set_userdata('file_sort', $sort);
			}

			if( ! $direction)
			{
				$direction = 'desc';
				$this->session->set_userdata('file_direction', $direction);
			}

			$data['sort'] = $sort;
			$data['direction'] = $direction;
			$data['flash_message'] = '';
			$data['res_num'] = $this->files_db->get_admin_num_files_search($query);
			$data['res_num'] = $this->files_db->get_admin_num_files_search($query);
			$data['query'] = $query;
			$data['per_page'] = $per_page;

			$config['base_url'] = site_url('admin/files/search/'.urlencode($query));
			$config['total_rows'] = $data['res_num'];
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 5;

			$this->pagination->initialize($config);

			if($this->session->flashdata('msg'))
			{
				$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
			}

			$data['files'] = $this->files_db->get_admin_files_search($query, $sort, $direction, $per_page, $this->uri->segment(5));
			$data['pagination'] = $this->pagination->create_links();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Files') ));
			$this->load->view($this->startup->skin.'/admin/files/search_result',$data);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Search Files')));
			$this->load->view($this->startup->skin.'/admin/files/search');
			$this->load->view($this->startup->skin.'/footer');
		}
	}

	public function edit($id)
	{
		if($this->input->post('status'))
		{
			$this->db->where('file_id', $id);
			$this->db->update('refrence', $_POST);

			$this->session->set_flashdata('msg', lang('File Edited'));
			redirect('admin/files/view');
			return false;
		}

		$data['id'] = $id;
		$data['file'] = $this->files_db->get_file_object($id);

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit File')));
		$this->load->view($this->startup->skin.'/admin/files/edit',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function delete($id)
	{
		$this->files_db->delete_file_admin($id);
		$this->session->set_flashdata('msg', lang('File Deleted'));
		redirect('admin/files/view');
	}

	public function ban($id)
	{
		$this->files_db->ban_file_admin($id);
		$this->session->set_flashdata('msg', lang('File Banned'));
		redirect('admin/files/view');
	}

	public function sort()
	{
		if($this->input->post('sort'))
		{
			$sort = $this->input->post('sort');
			$this->session->set_userdata('file_sort', $sort);
		}

		if($this->input->post('direction'))
		{
			$direction = $this->input->post('direction');
			$this->session->set_userdata('file_direction', $direction);
		}

		redirect('admin/files');
	}

	public function count()
	{
		if($this->input->post('file_count'))
		{
			$count = $this->input->post('file_count');
			$this->session->set_userdata('file_count', $count);
		}

		redirect('admin/files/view');
	}

	public function search_count($query)
	{
		if($this->input->post('file_count'))
		{
			$count = $this->input->post('file_count');
			$this->session->set_userdata('file_count', $count);
		}

		redirect('admin/files/search/'.$query);
	}

	public function mass_ban($query='')
	{
		if($this->input->post('files') && is_array($this->input->post('files')))
		{
			foreach($this->input->post('files') as $id)
			{
				$this->files_db->ban_file_admin($id);
			}

			$this->session->set_flashdata('msg', sprintf(lang('%d File(s) have been Banned'), count($this->input->post('files'))));
		}

		if( ! empty($query))
		{
			redirect('admin/files/search/'.$query);
		}
		else
		{
			redirect('admin/files/view');
		}
	}

	public function mass_delete($query='')
	{
		if($this->input->post('files') && is_array($this->input->post('files')))
		{
			foreach($this->input->post('files') as $id)
			{
				$this->files_db->delete_file_admin($id);
			}

			$this->session->set_flashdata('msg', sprintf(lang('%d Files(s) have been deleted'), count($this->input->post('files'))));
		}

		if( ! empty($query))
		{
			redirect('admin/files/search/'.$query);
		}
		else
		{
			redirect('admin/files/view');
		}
	}
}

/* End of file admin/files.php */
/* Location: ./application/controllers/admin/files.php */

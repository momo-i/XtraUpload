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
 * XtraUpload Folders Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Folders extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('folders/folders_db');
	}

	public function index()
	{
		redirect('admin/folders/view');
	}

	public function view()
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$this->load->library('pagination');
		$per_page = 50;

		$config['base_url'] = site_url('admin/folders/view');
		$config['total_rows'] = $this->folders_db->get_num_folders();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['folders'] = $this->folders_db->get_folders($per_page, $this->uri->segment(4));

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('File Folder Manager')));
		$this->load->view($this->startup->skin.'/admin/folders/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function search($query='')
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		if($query == '')
		{
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Search File Folder')));
			$this->load->view($this->startup->skin.'/admin/folders/search', $data);
			$this->load->view($this->startup->skin.'/footer');
			return;
		}

		$this->load->library('pagination');
		$per_page = 50;

		$config['base_url'] = site_url('admin/folders/view');
		$config['total_rows'] = $this->folders_db->get_num_search_folders($query);
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);

		$data['folders'] = $this->folders_db->search_folders($query, $per_page, $this->uri->segment(4));
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('File Folder Search Results')));
		$this->load->view($this->startup->skin.'/admin/folders/search_result', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function delete($id)
	{
		if(intval($id) != 0))
		{
			$this->folders_db->delete(intval($id));
			$this->session->set_flashdata('msg', lang('Folder Deleted!'));
			redirect('admin/folders/view');
		}
	}
}

/* End of file admin/folders.php */
/* Location: ./application/controllers/admin/folders.php */

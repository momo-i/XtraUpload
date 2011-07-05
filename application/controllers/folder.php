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
 * XtraUpload Folder Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Folder extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('files/files_db');
		$this->load->library('functions');
	}

	public function index()
	{
		redirect('home');
	}

	public function view($id)
	{
		$data['folder'] = $this->db->get_where('folder', array('f_id' => $id))->row();
		if( ! $data['folder'])
		{
			show_404();
		}

		$data['folder_files'] = $this->db->get_where('f_items', array('folder_id' => $id));
		$data['id'] = $id;
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('File Folder').' '.$this->startup->site_config->title_separator.' '.$data['folder']->name));
		$this->load->view($this->startup->skin.'/folder/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function create()
	{
		$this->load->model('user_access');
		$data['files'] = $this->files_db->get_files();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Create File Folder')));
		$this->load->view($this->startup->skin.'/folder/create', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function process()
	{
		$this->load->model('user_access');
		if( ! is_array($this->input->post('files')) OR ! $this->input->post('name'))
		{
			redirect('folder/create');
		}

		$name = $this->input->post('name');
		$desc = $this->input->post('desc');
		$fid = $this->functions->get_rand_id(8);

		$data['fid'] = $fid;
		$this->db->insert('folder', array('name' => $name, 'descr' => $desc, 'f_id' => $fid));

		$files = $this->input->post('files');
		foreach($files as $ind => $fileid)
		{
			if($this->files_db->file_exists($fileid))
			{
				$this->db->insert('f_items', array('folder_id'=>$fid, 'file_id' => $fileid));
			}
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Create File Folder').' '.$this->startup->site_config->title_separator. ' ' .lang('Done')));
		$this->load->view($this->startup->skin.'/folder/done', $data);
		$this->load->view($this->startup->skin.'/footer');
	}
}

/* End of file folder.php */
/* Location: ./application/controllers/folder.php */

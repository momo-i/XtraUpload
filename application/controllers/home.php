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
 * XtraUpload Home Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Home extends CI_Controller {

	/**
	 * Constructor
	 *
	 * The home page controller constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('server/server_db');
	}

	/**
	 * Home->index()
	 *
	 * The home page for XtraUpload, containing the flash uploader
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		// Get some vars
		$data = array(
			'server' => $this->server_db->get_random_server()->url,
			'upload_limit' => $this->startup->group_config->upload_size_limit,
			'upload_num_limit' => $this->startup->group_config->upload_num_limit,
			'files_types' => $this->startup->group_config->files_types,
			'file_icons' => $this->functions->get_json_filetype_list(),
			'file_types_allow_deny' => $this->startup->group_config->file_types_allow_deny,
			'storage_limit' => $this->startup->group_config->storage_limit
		);

		if($this->config->config['index_page'] != '')
		{
			$data['server'] .= $this->config->config['index_page'].'/';
		}

		if(intval($this->startup->group_config->storage_limit) > 0)
		{
			$data['storage_used'] = $this->functions->get_filesize_prefix(($this->startup->group_config->storage_limit * 1024 * 1024) - $this->files_db->get_files_usage_space());
		}

		$data['flash_message'] = '';

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		// There is no processing functionality here, just static pages to send the user
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Home'), 'include_flash_upload_js' => true));
		$this->load->view($this->startup->skin.'/home', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */

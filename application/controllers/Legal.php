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
 * XtraUpload Legal Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Legal extends CI_Controller {

	private $server = false;

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		return;
	}

	public function tos()
	{
		$data=array();
		$data['site_name'] = $this->startup->site_config->sitename;
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Terms of Service')));
		$this->load->view($this->startup->skin.'/legal/tos', $data);
		$this->load->view($this->startup->skin.'/footer');
		return;
	}

	public function privacy()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Privacy Policy')));
		$this->load->view($this->startup->skin.'/legal/privacy');
		$this->load->view($this->startup->skin.'/footer');
		return;
	}
}

/* End of file legal.php */
/* Location: ./application/controllers/legal.php */

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
 * XtraUpload Legal Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Legal extends CI_Controller {

	/**
	 * ???
	 *
	 * @access	private
	 * @var		bool
	 */
	private $server = false;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Legal::index()
	 *
	 * Nothing to do
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
	}

	/**
	 * Legal::tos()
	 *
	 * Show Terms of Service page
	 *
	 * @access	public
	 * @return	void
	 */
	public function tos()
	{
		$data=array();
		$data['site_name'] = $this->startup->site_config->sitename;
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Terms of Service')));
		$this->load->view($this->startup->skin.'/legal/tos', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Legal::privacy()
	 *
	 * Show Privacy Policy page
	 *
	 * @access	public
	 * @return	void
	 */
	public function privacy()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Privacy Policy')));
		$this->load->view($this->startup->skin.'/legal/privacy');
		$this->load->view($this->startup->skin.'/footer');
	}
}

/* End of file Legal.php */
/* Location: ./application/controllers/Legal.php */

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
 * XtraUpload Step1 Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Step1 extends CI_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Step1::index()
	 *
	 * Show install step1 page
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$data['license'] = file_get_contents(realpath(BASEPATH.'../LICENSE'));
		$this->load->view('install/header');
		$this->load->view('install/step1', $data);
		$this->load->view('install/footer');
	}

}

/* End of file install/Step1.php */
/* Location: ./application/controllers/install/Step1.php */

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
 * XtraUpload Setup Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Setup extends CI_Controller {

	/**
	 * Operating system
	 *
	 * @access	private
	 * @var		bool
	 */
	private $_os = false;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_check_os();
	}

	/**
	 * Setup::index()
	 *
	 * Show install page
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$this->load->view('install/header');
		if(strcmp($this->_os, 'LINUX') === 0)
		{
			$this->load->view('install/setup');
		}
		else
		{
			$this->load->view('install/error');
		}
		$this->load->view('install/footer');
	}

	/**
	 * Setup::_check_os()
	 *
	 * Get PHP_OS
	 *
	 * @access	private
	 * @return	void
	 */
	private function _check_os()
	{
		if(defined('PHP_OS'))
		{
			$this->_os = strtoupper(PHP_OS);
		}
	}

}

/* End of file install/Setup.php */
/* Location: ./application/controllers/install/Setup.php */

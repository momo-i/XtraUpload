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
 * XtraUpload Setup Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Setup extends CI_Controller {

	private $os = false;

	public function __construct()
	{
		parent::__construct();
		$this->_check_os();
	}

	public function index()
	{
		$this->load->view('install/header');
		if(strcmp($this->os, 'LINUX') === 0)
		{
			$this->load->view('install/setup');
		}
		else
		{
			$this->load->view('install/error');
		}
		$this->load->view('install/footer');
	}

	private function _check_os()
	{
		if(defined('PHP_OS'))
		{
			$this->os = strtoupper(PHP_OS);
		}
	}

}

/* End of file install/setup.php */
/* Location: ./application/controllers/install/setup.php */

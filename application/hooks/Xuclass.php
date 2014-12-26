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
 * XtraUpload Hooks
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XuClass {

	/**
	 * XuClass::index()
	 *
	 * required class file load
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		log_message('debug', 'XuClass Initialized');
		ini_set('include_path', APPPATH.'xu_class/');
		require_once 'Zend/Debug.php';
		require_once 'Zend/Locale.php';
		require_once 'Zend/Locale/Data.php';
		require_once 'Zend/Translate.php';
	}

}

/* End of file XuClass.php */
/* Location: ./application/hooks/XuClass.php */

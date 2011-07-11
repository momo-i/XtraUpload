<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class XuClass {

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

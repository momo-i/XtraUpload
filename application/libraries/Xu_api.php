<?php
/* vim: set ts=4 sw=4 sts=0: */

/**
 * XtraUpload
 *
 * A turn-key open source Web 2.0 PHP file uploading package requiring PHP v5
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
 * XtraUpload XU_API Library
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/api
 */
class Xu_api {

	/**
	 * Menu
	 *
	 * @access	public
	 * @var		string
	 */
	public $menus;

	/**
	 * CodeIgniter singleton
	 *
	 * @access	private
	 * @var		object
	 */
	private $CI;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "XtraUpload API Class Initialized");
		
		$this->init();
	}

	/**
	 * Xu_api::init()
	 *
	 * Init api
	 *
	 * @access	private
	 * @return	void
	 */
	private function init()
	{
		$dir = APPPATH."libraries/api/";
		$load = array();
		
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir)) 
		{
			if ($dh = opendir($dir)) 
			{
				while (($file = readdir($dh)) !== false) 
				{
					if(!is_dir($dir . $file) and substr($file, -8) == '_api.php')
					{
						$load[ucfirst(str_replace('.php', '', $file))] = $file;
					}
				}
				closedir($dh);
			}
		}
		else
		{
			show_error('COMPLETE FAIL, REUPLOAD FILES');
		}
		
		if( ! empty($load))
		{
			foreach($load as $class => $file)
			{
				$name = str_replace(array('_api', 'xu_', 'Xu_'), '', $class);
				include_once($dir.$file);
				$this->$name = new $class();
			}
		}
	}
}

/* End of file Xu_api.php */
/* Location: ./application/libraries/Xu_api.php */

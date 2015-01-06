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
 * XtraUpload XU_API Hooks Library
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/api/hooks
 */
class Xu_hooks_api {

	/**
	 * CodeIgniter singleton
	 *
	 * @access	private
	 * @var		object
	 */
	private $CI = '';

	/**
	 * Hooks
	 *
	 * @access	private
	 * @var		object
	 */
	private $_hooks = array();

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "XtraUpload Hooks API Class Initialized");
		$this->_init();
	}

	/**
	 * Xu_hooks_api::_init()
	 *
	 * Nothing to do
	 *
	 * @access	private
	 * @return	void
	 */
	private function _init()
	{
	}

	/**
	 * Xu_hooks_api::set_hook()
	 *
	 * Set a hook to be run
	 *
	 * @access	public
	 * @param	string	$name	Hook name
	 * @param	string	$function	Function name
	 * @return	void
	 */
	public function set_hook($name, $function)
	{
		if(!isset($this->_hooks[$name]))
		{
			$this->_hooks[$name] = array();
		}
		
		$this->_hooks[$name][] = $function;
	}

	/**
	 * Xu_hooks_api::run_hooks()
	 *
	 * Run all set hooks, and return the data
	 *
	 * @access	public
	 * @param	string	$name	Hook name
	 * @param	string	$args	Hook args
	 * @return	string	$args
	 */
	public function run_hooks($name, $args)
	{
		if(!isset($this->_hooks[$name]) or !is_array($this->_hooks[$name]) or empty($this->_hooks[$name]))
		{
			return $args;	
		}
		
		$this->CI =& get_instance();
		
		// dirty work, needs to be rethought. I hate eval statments... 
		foreach($this->_hooks[$name] as $function)
		{
			$args = $function($args);
		}
		return $args;
	}

	/**
	 * Xu_hooks_api::_get_hooks()
	 *
	 * [SYSTEM] -> return the raw hooks store variable
	 *
	 * @access	private
	 * @return	object
	 */
	private function _get_hooks()
	{
		return $this->_hooks;
	}

	/**
	 * Xu_hooks_api::_put_hooks()
	 *
	 * [SYSTEM] -> apply the raw hooks store variable
	 *
	 * @accses	private
	 * @return	void
	 */
	private function _put_hooks($hooks)
	{
		$this->_hooks = $hooks;
	}
}

/* End of file Xu_hooks_api.php */
/* Location: ./application/libraries/api/Xu_hooks_api.php */

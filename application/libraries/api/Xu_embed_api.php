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
class Xu_embed_api
{
	/**
	 * Store
	 *
	 * @access	private
	 * @var		object
	 */
	private $_store;

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
		log_message('debug', "XtraUpload Embed Code API Class Initialized");
		$this->_init();
	}

	/**
	 * Xu_embed_api::_init()
	 *
	 * Initialize object
	 *
	 * @access	private
	 * @return	void
	 */
	private function _init()
	{
		$this->_store = new stdClass();
		$this->_store->embed = array();
	}

	/**
	 * Xu_embed_api::add_embed_type()
	 *
	 * Add embed type
	 *
	 * @access	public
	 * @param	string	$types	Embed type
	 * @param	string	$data	Embed data
	 * @return	void
	 */
	public function add_embed_type($types, $data)
	{
		if(!is_array($types))
		{
			$types = array($types);
		}
		foreach($types as $type)
		{
			$this->_store->embed[$type] = $data;
		}
	}

	/**
	 * Xu_embed_api::remove_embed_type()
	 *
	 * Remove embed type
	 *
	 * @access	public
	 * @param	string	$type	Embed type
	 * @return	void
	 */
	public function remove_embed_type($type)
	{
		unset($this->_store->embed[$type]);
	}

	/**
	 * Xu_embed_api::get_embed_code()
	 *
	 * Returns embed code
	 *
	 * @access	public
	 * @param	string	$type	Embed type
	 * @return	object|false
	 */
	public function get_embed_code($type)
	{
		if(!isset($this->_store->embed[$type]) or !is_array($this->_store->embed[$type]))
		{
			return false;	
		}
		return $this->_store->embed[$type];
	}

	/**
	 * Xu_embed_api::_get_embed_store()
	 *
	 * Returns embed store
	 *
	 * @access	private
	 * @return	object
	 */
	private function _get_embed_store()
	{
		return $this->_store;
	}

	/**
	 * Xu_embed_api::_put_embed_store()
	 *
	 * Put embed store
	 *
	 * @access	private
	 * @param	string	$store Embed store
	 * @return	void
	 */
	private function _put_embed_store($store)
	{
		$this->_store = $store;
	}
}

/* End of file Xu_embed_api.php */
/* Location: ./application/libraries/api/Xu_embed_api.php */

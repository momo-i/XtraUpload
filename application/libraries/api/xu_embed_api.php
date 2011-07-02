<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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

// ------------------------------------------------------------------------

/**
 * XtraUpload XU_API Hooks Library
 *
 * @package		XtraUpload
 * @subpackage	Library
 * @category	Library
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/api/hooks
 */

// ------------------------------------------------------------------------

class Xu_embed_api
{
	private $store;
	private $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "XtraUpload Embed Code API Class Initialized");
		$this->_init();
	}
	
	private function _init()
	{
		$this->store = new stdClass();
		$this->store->embed = array();
	}
	
	public function add_embed_type($type, $data)
	{
		$this->store->embed[$type] = $data;
	}
	
	public function remove_embed_type($type)
	{
		unset($this->store->embed[$type]);
	}
	
	public function get_embed_code($type)
	{
		if(!isset($this->store->embed[$type]) or !is_array($this->store->embed[$type]))
		{
			return false;	
		}
		return $this->store->embed[$type];
	}
	
	private function _get_embed_store()
	{
		return $this->store;
	}
	
	private function _put_embed_store($store)
	{
		$this->store = $store;
	}
}

/* End of file xu_embed_api.php */
/* Location: ./application/libraries/api/xu_embed_api.php */

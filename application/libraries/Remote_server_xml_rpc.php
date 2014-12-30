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
 * XtraUpload Remote Server XML_RPC Library
 *
 * Usage:
 *
 * <code>
 * $this->load->library('Remote_server_xml_rpc');
 * $this->remote_server_api->update_cache();
 * </code>
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/code/libraries/remote_server_xml_rpc
 */
class Remote_server_xml_rpc {

	/**
	 * CodeIgniter
	 *
	 * @access	private
	 * @var		object
	 */
	private $CI = '';
	
	/**
	 * Constructor
	 *
	 * AN XML_RPC server/client system for sending commands to file servers.
	 *
	 * @access	public
	 * @return	void
	 */
    public function __construct()
    {
		$this->CI =& get_instance();
		$this->CI->load->library('xmlrpc');
    }

	/**
	 * Remote_server_xml_rpc::update_cache()
	 *
	 * Remote cache update
	 *
	 * @access	public
	 * @return	void
	 */
	public function update_cache()
	{
		$this->CI->load->model('server/server_db');
		$servers = $this->CI->server_db->get_servers();
				
		foreach($servers->result() as $server)
		{
			$this->CI->xmlrpc->server($server->url.'remote', 80);
			$this->CI->xmlrpc->method('remote_server_api.update_cache');
			
			//$request = '';
			$request = array($this->CI->config->config['encryption_key']);
			$this->CI->xmlrpc->request($request);
			
			if( ! $this->CI->xmlrpc->send_request())
			{
				log_message('error', 'Server('.$server->url.') did not respond to XML_RPC request to update cache');
			}
		}
	}
}

/* End of file Remote_server_xml.php */
/* Location: ./application/libraries/Remote_server_xml.php */

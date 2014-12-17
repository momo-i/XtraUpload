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
 * XtraUpload Remote Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Remote extends CI_Controller {

	/**
	 * Home()
	 *
	 * The home page controller constructor
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
	}

	// ------------------------------------------------------------------------

	/**
	 * Remote->index()
	 *
	 * Route RPC requests
	 *
	 * @access  public
	 * @return  none
	 */
	public function index()
	{
		$config['functions']['remote_server_api.updateCache'] = array('function' => 'Remote._update_cache');
		//$config['debug'] = true;

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}

	// ------------------------------------------------------------------------

	/**
	 * Remote->_update_config()
	 *
	 * The home page for XtraUpload, containing the flash uploader
	 *
	 * @access  private
	 * @param   object
	 * @return  none
	 */
	private function _update_cache($request)
	{
		$parameters = $request->output_parameters();
		$enc = $parameters['0'];

		if($enc != $this->config->config['encryption_key'])
		{
			return $this->xmlrpc->send_error_message('404', 'Method not Found!');
		}

		// get encrypted filenames
		$skin_name = md5($this->config->config['encryption_key'].'skin_name');
		$config_file_name = md5($this->config->config['encryption_key'].'site_config');
		$extend_file_name = md5($this->config->config['encryption_key'].'extend');

		// update skin cache
		$skin = $this->db->get_where('skin', array('active' => '1'))->row()->name;
		file_put_contents(CACHEPATH . $skin_name , $skin);

		// update config cache
		$q = $this->db->get('config');
		foreach($q->result() as $row)
		{
			$site_config[$row->name] = $row->value;
		}
		file_put_contents(CACHEPATH . $config_file_name, base64_encode(json_encode($site_config)));

		// update group config
		$q = $this->db->get('groups');
		foreach($q->result() as $row)
		{
			$group_file_name = md5($this->config->config['encryption_key'].'group_'.$row->id);
			$group_config = $row;
			file_put_contents(CACHEPATH . $group_file_name, base64_encode(json_encode($group_config)));
		}

		// update extend cache
		$data = array();
		$db1 = $this->db->get_where('extend', array('active' => 1));
		foreach($db1->result() as $plugin)
		{
			$data[] = $plugin->file_name;
		}

		if(empty($data))
		{
			@unlink(CACHEPATH . $extend_file_name);
		}
		else
		{
			$final = base64_encode(json_encode($data));
			file_put_contents(CACHEPATH . $extend_file_name, $final);
		}

		// send responce
		$response = array(
			array(
				'return' => 'true',
			),
			'struct'
		);

		return $this->xmlrpc->send_response($response);
	}
}

/* End of file remote.php */
/* Location: ./application/controllers/remote.php */

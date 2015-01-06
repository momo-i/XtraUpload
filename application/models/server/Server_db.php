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
 * XtraUpload Servers DB Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class Server_db extends CI_Model {

    public function __construct()
    {
		// Call the Model constructor
        parent::__construct();
    }
	
	// ------------------------------------------------------------------------

	public function get_servers()
	{
		return $this->db->get('servers');
	}
	
	// ------------------------------------------------------------------------

	public function get_server($id)
	{
		return $this->db->get_where('servers', array('id' => $id))->row();
	}
		
	// ------------------------------------------------------------------------
	
	public function get_server_for_download($file)
	{
		if(!$file->mirror)
		{
			if(substr($file->server, -1) != '/')
			{
				$file->server .= '/';
			}
			return $file->server;
		}
		else
		{
			$server = $file->server;
			$arr = json_decode($servers);
			$serv = $arr[rand(0, (count($arr)-1))];
			if(substr($serv, -1) != '/')
			{
				$serv .= '/';
			}
			return $serv;
		}
	}
	
	// ------------------------------------------------------------------------

	public function get_random_server()
	{
		$this->db->order_by('id', 'RANDOM');
		$get = $this->db->get_where('servers', array('status' => '1'), 1, 0);
		
		if($get->num_rows() != 1)
		{
			$this->db->order_by('id', 'RANDOM');
			$get = $this->db->get('servers', 1, 0);
			return $get->row();
		}
		else
		{
			return $get->row();
		}
	}
	
	// ------------------------------------------------------------------------

	public function get_server_by_id($id)
	{
		return $this->db->get_where('servers', array('id' => $id), 1, 0)->row();
	}
	
	// ------------------------------------------------------------------------
	
	public function edit_server($id, $data)
	{
		$this->db->where('id', $id)->update('servers', $data);
	}
	
	// ------------------------------------------------------------------------
	
	public function add_server($data)
	{
		$this->db->insert('servers', $data);
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------
	
	public function delete_server($id)
	{
		$this->db->delete('servers', array('id' => $id));
	}
}

/* End of file Server_db.php */
/* Location: ./application/models/server/Server_db.php */

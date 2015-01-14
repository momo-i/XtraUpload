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
 * XtraUpload Admin Access Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class Admin_logger extends CI_Model {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	 * Admin_logger::add_log()
	 *
	 * Add admin log
	 *
	 * @access	public
	 * @param	int	$valid	Valid
	 * @return	void
	 */
    public function add_log($valid = 0)
    {
    	$data['user'] = $this->session->userdata('id');
    	$data['user_name'] = $this->session->userdata('username');
    	$data['ip'] = $this->input->ip_address();
    	$data['date'] = time();
		$data['valid'] = $valid;

		if($data['user'])
		{
	    	$this->db->insert('login_refrence', $data);
		}
    }

	/**
	 * Admin_logger::get_logs()
	 *
	 * Get admin log
	 *
	 * @access	public
	 * @param	int		$limit	Limit
	 * @param	int		$offset	Offset
	 * @param	string	$select	Database query
	 * @return	object
	 */    
    public function get_logs($limit=100, $offset=0, $select='')
	{
		$this->db->order_by("date", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get('login_refrence', $limit, $offset);
		return $query;	
	}
}

/* End of file Admin_logger.php */
/* Location: ./application/models/Admin_logger.php */

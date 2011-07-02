<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * XtraUpload Users DB Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

class Users_db extends CI_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//------------------------
	// Blog Viewing Functions
	//------------------------
	public function get_all_users($sort, $direction, $limit=10, $offset=0)
	{
		$posts = array();
		$this->db->order_by($sort, $direction); 
		$this->db->limit($limit, $offset); 
		return $this->db->get('users');
	}
	
	public function get_all_users_search($query, $sort, $direction, $limit=10, $offset=0)
	{
		$this->db->like('username', $query)->or_like('email', $query)->or_like('ip', $query)->order_by($sort, $direction)->limit($limit, $offset); 
		return $this->db->get('users');
	}
	
	public function get_user_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		return $query->row();
	}
	
	public function get_num_users()
	{
		$query = $this->db->get('users');
		return $query->num_rows();
	}
	
	public function get_num_users_search($query)
	{
		$query = $this->db->like('username', $query)->or_like('email', $query)->or_like('ip', $query)->get('users');
		return $query->num_rows();
	}
	
	public function give_admin_rights($id)
	{
		$data = array(  	
			'admin' => 1
		);
		
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	public function set_user_status($status, $id)
	{
	    $data = array(  	
			'status' => $status
		);
		
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	public function take_admin_rights($id)
	{
		$data = array(  	
			'admin' => 0
		);
		
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	public function add_user($data)
	{
		$this->load->model('users');
		
		// create new user and set their status to active
		$id = $this->users->new_user($data, false);
		$this->set_user_status(1, $id);
	}
	
	public function edit_user($id, $user, $pass, $email, $group)
	{
		$data = array();
		if(!empty($user))
		{
			$data['username'] = $user;
		}
		
		if(!empty($email))
		{
			$data['email'] = $email;
		}
		
		if(!empty($pass))
		{
			$data['password'] = md5($this->config->config['encryption_key'].$pass);
		}
		
		if(!empty($group))
		{
			$data['group'] = $group;
		}
		
		if(count($data) == 0)
		{
			return true;
		}
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	public function delete_user($id)
	{
		$this->db->delete('users', array('id' => $id));
	}
}

/* End of file users_db.php */
/* Location: ./application/models/users/users_db.php */

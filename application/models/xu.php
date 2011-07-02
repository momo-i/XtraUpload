<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class XU extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_version()
	{
		$query = $this->db->query('SHOW TABLES');
		if($query->num_rows == 0)
		{
			return false;
		}
		$query = $this->db->get_where('config', array('name' => '_db_version'));
		if( ! $query)
		{
			return null;
		}
		$config = $query->row();
		return $config->value;
	}

}

/* End of file xu.php */
/* Location: ./application/models/xu.php */

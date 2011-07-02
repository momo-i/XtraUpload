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
 * XtraUpload Ban Access Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

class Admin_menu_shortcuts_db extends CI_Model {

	public $ban_list = array();
	public $ban_file = 'ban_list_file';
	// ------------------------------------------------------------------------

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function get_shortcuts($limit=100, $offset=0, $select='')
	{
		$this->db->order_by("order", "asc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get('admin_menu_shortcuts', $limit, $offset);
		return $query;	
	}
	
	public function get_num_shortcuts()
	{
		return $this->db->select('id')->where('status', 1)->count_all_results('admin_menu_shortcuts');
	}
	
	public function get_shortcut($id, $limit=100, $offset=0, $select='')
	{
		$this->db->order_by("order", "asc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get_where('admin_menu_shortcuts', array('id' => $id), $limit, $offset);
		return $query;	
	}
	
	public function add_shortcut($data)
	{
		$query = $this->db->insert('admin_menu_shortcuts', $data);
		return $query;	
	}
	
	public function edit_shortcut($id, $data = '')
	{
		if($data == '' and is_array($id))
		{
			$array = $id;
			foreach($array as $id => $data)
			{
				$this->db->where('id', $id)->update('admin_menu_shortcuts', $data);
			}
		}
		else
		{
			$this->db->where('id', $id)->update('admin_menu_shortcuts', $data);
		}
	}
	
	public function delete_shortcut($id)
	{
		$this->db->delete('admin_menu_shortcuts', array('id' => $id));
	}
}

/* End of file admin_menu_shortcuts_db.php */
/* Location: ./application/models/admin/menu_shortcuts/admin_menu_shortcuts_db.php */

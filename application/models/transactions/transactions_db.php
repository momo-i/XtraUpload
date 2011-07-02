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
 * XtraUpload Trasnactions DB Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/code/models/transactions
 */

// ------------------------------------------------------------------------

class Transactions_db extends CI_Model {

    public function __construct($select='')
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//------------------------
	// Transaction Viewing Functions
	//------------------------
	public function get_transactions($limit=100, $offset=0, $select='')
	{
		$posts = array();
		$this->db->order_by("transactions.time", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query =$this->db->get('transactions', $limit, $offset);
		return $query;	
	}
	
	public function get_transactions_by_user($user, $limit=100, $offset=0, $select='')
	{
		$posts = array();
		$this->db->order_by("refrence.id", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get_where('transactions', array('user' => $user), $limit, $offset);
		return $query;	
	}
	
	public function get_num_transactions()
	{
		$query = $this->db->select('id');
		return $query->count_all_results('transactions');
	}
	
	public function get_num_user_transactions($user)
	{
		$query = $this->db->select('id')->where('user', $user);
		return $query->count_all_results('transactions');
	}
	
	public function get_transaction_by_id($id, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$query =$this->db->get_where('transactions', array('id' => $id), $limit, $offset);
		return $query->row();
	}
	
	public function insert($data)
	{
		$this->db->insert('transaction', $data);
		return $this->db->insert_id();
	}
	
	public function edit($id, $data)
	{
		$this->db->where('id', $id)->delete('transaction', $data);
	}
	
	public function delete($id)
	{
		$this->db->delete('transaction', array('id' => $id));
	}
}

/* End of file transactions_db.php */
/* Location: ./application/models/transactions/transactions_db.php */

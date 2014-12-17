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
 * XtraUpload Ads DB Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

class Ads_db extends CI_Model {

    public function __construct()
    {
		// Call the Model constructor
        parent::__construct();
    }
	
	// ------------------------------------------------------------------------

	public function get_ads()
	{
		return $this->db->get('ads');
	}
	
	// ------------------------------------------------------------------------

	public function get_ads_one($id)
	{
		return $this->db->get_where('ads', array('id' => $id))->row();
	}
		
	// ------------------------------------------------------------------------
	
	public function edit_ads($id, $data)
	{
		$this->db->where('id', $id)->update('ads', $data);
	}
	
	// ------------------------------------------------------------------------
	
	public function add_ads($data)
	{
		$this->db->insert('ads', $data);
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------
	
	public function delete_ads($id)
	{
		$this->db->delete('ads', array('id' => $id));
	}

}

/* End of file server_db.php */
/* Location: ./application/models/server/server_db.php */

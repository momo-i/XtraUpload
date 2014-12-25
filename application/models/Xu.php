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
 * XtraUpload Xu Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class Xu extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_version()
	{
		$query = $this->db->query('SHOW TABLES');
		if($query->num_rows() == 0)
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

/* End of file Xu.php */
/* Location: ./application/models/Xu.php */

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
class Admin_access extends CI_Model {

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
		
		// Determine of the user has Admin privliges
		$is_admin = $this->startup->group_config->admin;
		if(!$is_admin)
		{
			$this->admin_logger->add_log(0);
			redirect('user/login');
		}
		else
		{
			if($this->session->userdata('ip_logged') == false)
			{
				$this->session->set_userdata('ip_logged', true);
				$this->admin_logger->add_log(1);
			}
		}
    }
}

/* End of file Admin_access.php */
/* Location: ./application/models/Admin_access.php */

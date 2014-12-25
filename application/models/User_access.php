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
 * XtraUpload User Access Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/f
 */
class user_access extends CI_Model {

	// ------------------------------------------------------------------------

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		// Determine if the users is Logged into the system
		$is_user = $this->session->userdata('login');
		if(!$is_user)
		{
			redirect('user/login');
		}
    }
}

/* End of file User_access.php */
/* Location: ./application/models/User_access.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package	 XtraUpload
 * @author	  Matthew Glinski
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @license	 http://xtrafile.com/docs/license
 * @link		http://xtrafile.com
 * @since	   Version 2.0
 * @filesource
 */

/**
 * XtraUpload Stats Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Stats extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	public function index()
	{
		redirect('admin/stats/view');
	}

	public function view()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Site Stats')));
		$this->load->view($this->startup->skin.'/admin/stats');
		$this->load->view($this->startup->skin.'/footer');
	}
}

/* End of file admin/stats.php */
/* Location: ./application/controllers/admin/stats.php */

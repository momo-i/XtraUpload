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
 * XtraUpload Stats Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Stats extends CI_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @see		Admin_access
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	/**
	 * Stats::index()
	 *
	 * Redirect Stats::view()
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		redirect('admin/stats/view');
	}

	/**
	 * Stats::view()
	 *
	 * Show statistics page
	 *
	 * @accses	public
	 * @return	void
	 */
	public function view()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Site Stats'), 'include_chart_js' => TRUE));
		$this->load->view($this->startup->skin.'/admin/stats');
		$this->load->view($this->startup->skin.'/footer');
	}
}

/* End of file admin/Stats.php */
/* Location: ./application/controllers/admin/Stats.php */

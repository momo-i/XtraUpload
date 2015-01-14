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
 * XtraUpload Charts Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Charts extends CI_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @see		Admin_access
	 * @see		Server_db
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('server/server_db');
	}

	/**
	 * Charts::index()
	 *
	 * Nothing to do.
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
	}

	/**
	 * Charts::all_downloads()
	 *
	 * Show all downloads charts
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_downloads($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_downloads', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::all_images_vs_regular()
	 *
	 * Show all images vs regular charts
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_images_vs_regular($height = 300, $width = 300, $ignore = '')
    {
		$data['regular'] = $this->db->get_where('refrence', array('is_image' => false))->num_rows();
		$data['image'] = $this->db->get_where('refrence', array('is_image' => true))->num_rows();
        $this->load->view($this->startup->skin.'/api/charts/all_images_vs_regular', array('data' => $data));
    }

	/**
	 * Charts::all_remote_vs_host_uploads()
	 *
	 * Show all remote upload vs host uploads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_remote_vs_host_uploads($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_remote_vs_host_uploads', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::all_server_uploads()
	 *
	 * Show all server uploads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_server_uploads($height = 300, $width = 300, $ignore = '')
    {
        $data = array('width' => $width, 'height' => $height, 'servers' => $this->server_db->get_servers());
        $this->load->view($this->startup->skin.'/api/charts/all_server_uploads', $data);
    }

	/**
	 * Charts::all_server_used_space()
	 *
	 * Show all server used spaces
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_server_used_space($height = 300, $width = 300, $ignore = '')
    {
        $data = array('width' => $width, 'height' => $height, 'servers' => $this->server_db->get_servers());
        $this->load->view($this->startup->skin.'/api/charts/all_server_used_space', $data);
    }

	/**
	 * Charts::all_uploads()
	 *
	 * Show all uploads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_uploads($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_uploads');
    }

	/**
	 * Charts::downloads_weekly()
	 *
	 * Show weekly downloads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function downloads_weekly($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/downloads_weekly', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::images_vs_regular_weekly()
	 *
	 * Show weekly images vs regular
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function images_vs_regular_weekly($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/images_vs_regular_weekly', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::remote_vs_host_uploads_weekly()
	 *
	 * Show weelky remote upload vs host uploads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function remote_vs_host_uploads_weekly($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/remote_vs_host_uploads_weekly', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::total_new_users_weekly()
	 *
	 * Show weekly total new users
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 */
    public function total_new_users_weekly($height = 300, $width = 300, $ignore = '')
    {
        $this->load->view($this->startup->skin.'/api/charts/total_new_users_weekly', array('width' => $width, 'height' => $height));
    }

	/**
	 * Charts::uploads_weekly()
	 *
	 * Show weekly uploads
	 *
	 * @access	public
	 * @param	int		$height	Charts height
	 * @param	int		$width	Charts width
	 * @param	int		$ignore	not use
	 * @return	void
	 */
	public function uploads_weekly($height = 300, $width = 300, $ignore = '')
    {
		$data = array();
		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM')))->num_rows();

		$data['1day']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['1day']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM')))->num_rows();

		$data['2days']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['2days']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM')))->num_rows();

		$data['3days']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['3days']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM')))->num_rows();

		$data['4days']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['4days']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM')))->num_rows();

		$data['5days']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['5days']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM')))->num_rows();

		$data['6days']['d'] = date('Y-m-d', strtotime('-6 days'));
		$data['6days']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM')))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/uploads_weekly', array('data' => $data));
    }
}

/* End of file api/Charts.php */
/* Location: ./application/controllers/api/Charts.php */

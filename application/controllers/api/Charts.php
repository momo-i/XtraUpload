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
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_downloads($ignore = '')
    {
		$data['title'] = lang('All downloads');

		$data['registered']['name'] = lang('Registered');
		$data['registered']['num'] = $this->db->where('user !=', '0')->count_all_results('downloads');

		$data['anonymous']['name'] = lang('Anonymous');
		$data['anonymous']['num'] = $this->db->where('user', '0')->count_all_results('downloads');

        $this->load->view($this->startup->skin.'/api/charts/all_downloads', $data);
    }

	/**
	 * Charts::all_images_vs_regular()
	 *
	 * Show all images vs regular charts
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_images_vs_regular($ignore = '')
    {
		$data['title'] = lang('All Uploads >> Files vs Images');

		$data['regular']['name'] = lang('Regular');
		$data['regular']['num'] = $this->db->get_where('refrence', array('is_image' => false))->num_rows();

		$data['image']['name'] = lang('Image');
		$data['image']['num'] = $this->db->get_where('refrence', array('is_image' => true))->num_rows();
        $this->load->view($this->startup->skin.'/api/charts/all_images_vs_regular', $data);
    }

	/**
	 * Charts::all_remote_vs_host_uploads()
	 *
	 * Show all remote upload vs host uploads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_remote_vs_host_uploads($ignore = '')
    {
		$data['title'] = lang('Uploads >> Local vs Remote');

		$data['local']['name'] = lang('Local');
		$data['local']['num'] = $this->db->get_where('refrence', array('remote' => false))->num_rows();

		$data['remote']['name'] = lang('Remote');
		$data['remote']['num'] = $this->db->get_where('refrence', array('remote' => true))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/all_remote_vs_host_uploads', $data);
    }

	/**
	 * Charts::all_server_uploads()
	 *
	 * Show all server uploads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_server_uploads($ignore = '')
    {
		$data['title'] = lang('File-Server Uploads Distrubition');
        $servers = $this->server_db->get_servers();
		$i = 0;
		foreach($servers->result() as $server)
		{
			$data['servers'][$i]['name'] = $server->name;
			$data['servers'][$i]['num'] = $this->db->get_where('files', array('server' => $server->url))->num_rows();
			$i++;
		}
		$data['totals'] = $this->files_db->get_admin_num_files();

        $this->load->view($this->startup->skin.'/api/charts/all_server_uploads', $data);
    }

	/**
	 * Charts::all_server_used_space()
	 *
	 * Show all server used spaces
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_server_used_space($ignore = '')
    {
		$data['title'] = lang('Servers >> Used Space');
        $servers = $this->server_db->get_servers();
		$i = 0;
		foreach($servers->result() as $server)
		{
			$server_space = $this->db->select_sum('size')->get_where('files', array('server' => $server->url))->row()->size;
			$data['servers'][$i]['name'] = $server->name;
			$data['servers'][$i]['num'] = round($server_space / 1024);
		}
        $this->load->view($this->startup->skin.'/api/charts/all_server_used_space', $data);
    }

	/**
	 * Charts::all_uploads()
	 *
	 * Show all uploads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function all_uploads($ignore = '')
    {
		$data['title'] = lang('All uploads');

		$data['registered']['name'] = lang('Registered');
		$data['registered']['num'] = $this->db->get_where('refrence', array('user' => '0'))->num_rows();

		$data['anonymous']['name'] = lang('Anonymous');
		$data['anonymous']['num'] = $this->db->get_where('refrence', array('user !=' => '0'))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/all_uploads', $data);
    }

	/**
	 * Charts::downloads_weekly()
	 *
	 * Show weekly downloads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function downloads_weekly($ignore = '')
    {
		$data['title'] = lang('Past 7 Days Downloads');;
		$data['xlabel'] = lang('Date');
		$data['ylabel'] = lang('Counts');

		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['today']['num'] = $this->db->where('time >', strtotime('today 12:00 AM'))->where('time <', strtotime('today 11:59:59 PM'))->count_all_results('downloads');

		$data['day1']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['day1']['num'] = $this->db->where('time >', strtotime('-1 days 12:00 AM'))->where('time <', strtotime('-1 days 11:59:59 PM'))->count_all_results('downloads');

		$data['day2']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['day2']['num'] = $this->db->where('time >', strtotime('-2 days 12:00 AM'))->where('time <', strtotime('-2 days 11:59:59 PM'))->count_all_results('downloads');

		$data['day3']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['day3']['num'] = $this->db->where('time >', strtotime('-3 days 12:00 AM'))->where('time <', strtotime('-3 days 11:59:59 PM'))->count_all_results('downloads');

		$data['day4']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['day4']['num'] = $this->db->where('time >', strtotime('-4 days 12:00 AM'))->where('time <', strtotime('-4 days 11:59:59 PM'))->count_all_results('downloads');

		$data['day5']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['day5']['num'] = $this->db->where('time >', strtotime('-5 days 12:00 AM'))->where('time <', strtotime('-5 days 11:59:59 PM'))->count_all_results('downloads');

		$data['day6']['d'] = date('Y-m-d', strtotime('-6 days'));
		$data['day6']['num'] = $this->db->where('time >', strtotime('-6 days 12:00 AM'))->where('time <', strtotime('-6 days 11:59:59 PM'))->count_all_results('downloads');

        $this->load->view($this->startup->skin.'/api/charts/downloads_weekly', $data);
    }

	/**
	 * Charts::images_vs_regular_weekly()
	 *
	 * Show weekly images vs regular
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function images_vs_regular_weekly($ignore = '')
    {
		$data['title'] = lang('New Uploads >> Files vs Images >> Weekly');
		$data['xlabel'] = lang('Date');
		$data['ylabel'] = lang('Counts');

		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['day1']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['day2']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['day3']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['day4']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['day5']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['day6']['d'] = date('Y-m-d', strtotime('-6 days'));


		$data['images']['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day1']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day2']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day3']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day4']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day5']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM'), 'is_image' => 1))->num_rows();
		$data['images']['day6']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM'), 'is_image' => 1))->num_rows();

		$data['regular']['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day1']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day2']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day3']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day4']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day5']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM'), 'is_image' => 0))->num_rows();
		$data['regular']['day6']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM'), 'is_image' => 0))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/images_vs_regular_weekly', $data);
    }

	/**
	 * Charts::remote_vs_host_uploads_weekly()
	 *
	 * Show weelky remote upload vs host uploads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
    public function remote_vs_host_uploads_weekly($ignore = '')
    {
		$data['title'] = lang('New Uploads >> Local vs Remote >> Weekly');
		$data['xlabel'] = lang('Date');
		$data['ylabel'] = lang('Counts');

		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['day1']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['day2']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['day3']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['day4']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['day5']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['day6']['d'] = date('Y-m-d', strtotime('-6 days'));

		$data['remote']['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day1']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day2']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day3']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day4']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day5']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM'), 'remote' => 1))->num_rows();
		$data['remote']['day6']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM'), 'remote' => 1))->num_rows();

		$data['local']['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day1']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day2']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day3']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day4']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day5']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM'), 'remote' => 0))->num_rows();
		$data['local']['day6']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM'), 'remote' => 0))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/remote_vs_host_uploads_weekly', $data);
    }

	/**
	 * Charts::total_new_users_weekly()
	 *
	 * Show weekly total new users
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 */
    public function total_new_users_weekly($ignore = '')
    {
		$data['title'] = lang('New Users >> Weekly');
		$data['xlabel'] = lang('Date');
		$data['ylabel'] = lang('Counts');

		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['today']['num'] = $this->db->get_where('users', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM')))->num_rows();

		$data['day1']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['day1']['num'] = $this->db->get_where('users', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM')))->num_rows();

		$data['day2']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['day2']['num'] = $this->db->get_where('users', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM')))->num_rows();

		$data['day3']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['day3']['num'] = $this->db->get_where('users', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM')))->num_rows();

		$data['day4']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['day4']['num'] = $this->db->get_where('users', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM')))->num_rows();

		$data['day5']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['day5']['num'] = $this->db->get_where('users', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM')))->num_rows();

		$data['day6']['d'] = date('Y-m-d', strtotime('-6 days'));
		$data['day6']['num'] = $this->db->get_where('users', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM')))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/total_new_users_weekly', $data);
    }

	/**
	 * Charts::uploads_weekly()
	 *
	 * Show weekly uploads
	 *
	 * @access	public
	 * @param	int		$ignore	not use
	 * @return	void
	 */
	public function uploads_weekly($ignore = '')
    {
		$data['title'] = lang('Past 7 Days Uploads');;
		$data['xlabel'] = lang('Date');
		$data['ylabel'] = lang('Counts');

		$data['today']['d'] = date('Y-m-d', strtotime('today'));
		$data['today']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM')))->num_rows();

		$data['day1']['d'] = date('Y-m-d', strtotime('-1 days'));
		$data['day1']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-1 days 12:00 AM'), 'time <' => strtotime('-1 days 11:59:59 PM')))->num_rows();

		$data['day2']['d'] = date('Y-m-d', strtotime('-2 days'));
		$data['day2']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-2 days 12:00 AM'), 'time <' => strtotime('-2 days 11:59:59 PM')))->num_rows();

		$data['day3']['d'] = date('Y-m-d', strtotime('-3 days'));
		$data['day3']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-3 days 12:00 AM'), 'time <' => strtotime('-3 days 11:59:59 PM')))->num_rows();

		$data['day4']['d'] = date('Y-m-d', strtotime('-4 days'));
		$data['day4']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-4 days 12:00 AM'), 'time <' => strtotime('-4 days 11:59:59 PM')))->num_rows();

		$data['day5']['d'] = date('Y-m-d', strtotime('-5 days'));
		$data['day5']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-5 days 12:00 AM'), 'time <' => strtotime('-5 days 11:59:59 PM')))->num_rows();

		$data['day6']['d'] = date('Y-m-d', strtotime('-6 days'));
		$data['day6']['num'] = $this->db->get_where('refrence', array('time >' => strtotime('-6 days 12:00 AM'), 'time <' => strtotime('-6 days 11:59:59 PM')))->num_rows();

        $this->load->view($this->startup->skin.'/api/charts/uploads_weekly', $data);
    }
}

/* End of file api/Charts.php */
/* Location: ./application/controllers/api/Charts.php */

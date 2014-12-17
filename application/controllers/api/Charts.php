<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @license     http://xtrafile.com/docs/license
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

/**
 * XtraUpload Charts Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        http://xtrafile.com/docs
 */
class Charts extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('server/server_db');
	}

	public function index()
	{
		return;
	}

    public function all_downloads($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_downloads', array('width' => $width, 'height' => $height));
    }

    public function all_images_vs_regular($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_images_vs_regular', array('width' => $width, 'height' => $height));
    }

    public function all_remote_vs_host_uploads($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_remote_vs_host_uploads', array('width' => $width, 'height' => $height));
    }

    public function all_server_uploads($height=300,$width=300,$ignore='')
    {
        $data = array('width' => $width, 'height' => $height, 'servers' => $this->server_db->get_servers());
        $this->load->view($this->startup->skin.'/api/charts/all_server_uploads', $data);
    }

    public function all_server_used_space($height=300,$width=300,$ignore='')
    {
        $data = array('width' => $width, 'height' => $height, 'servers' => $this->server_db->get_servers());
        $this->load->view($this->startup->skin.'/api/charts/all_server_used_space', $data);
    }

    public function all_uploads($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/all_uploads', array('width' => $width, 'height' => $height));
    }

    public function downloads_weekly($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/downloads_weekly', array('width' => $width, 'height' => $height));
    }

    public function images_vs_regular_weekly($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/images_vs_regular_weekly', array('width' => $width, 'height' => $height));
    }

    public function remote_vs_host_uploads_weekly($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/remote_vs_host_uploads_weekly', array('width' => $width, 'height' => $height));
    }

    public function total_new_users_weekly($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/total_new_users_weekly', array('width' => $width, 'height' => $height));
    }

	public function uploads_weekly($height=300,$width=300,$ignore='')
    {
        $this->load->view($this->startup->skin.'/api/charts/uploads_weekly', array('width' => $width, 'height' => $height));
    }
}

/* End of file api/charts.php */
/* Location: ./application/controllers/api/charts.php */

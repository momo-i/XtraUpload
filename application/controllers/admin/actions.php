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
 * XtraUpload Actions Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Actions extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	public function index()
	{
		redirect('admin/email/view');
	}

	public function view()
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Maintenance Actions')));
		$this->load->view($this->startup->skin.'/admin/actions/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function php_info()
	{
		phpinfo();
	}

	public function run_cron()
	{
		define('IN_CRON', TRUE);

		$dir = APPPATH."cron";
		$files = opendir($dir);

		// Look in the folder for cron files
		while ($file = readdir($files))
		{
			$code = substr($file, 0, 2);
			if((substr($file, -4, 4) == '.php') && ! is_dir($dir .'/'. $file) && ! stristr($file, '_no_load'))
			{
				$name = str_replace('.php', '', $file);
				include_once($dir .'/'. $file);
				$cron_extend = new $name(base_url());
				unset($cron_extend);
			}
		}
		closedir ($files);

		$this->session->set_flashdata('msg', lang('Cron Actions Completed!'));
		redirect('admin/actions/view');
	}

	public function clear_cache()
	{
		$this->load->helper('file');

		delete_files(CACHEPATH);
		$html = <<<EOF
<html>
<head>
	<title>403 Forbidden</title>
</head>
<body>

<p>Directory access is forbidden.<p>

</body>
</html>

EOF;
		$gitignore = <<<EOF
!index.html
*

EOF;
		write_file(CACHEPATH.'index.html', $html);
		write_file(CACHEPATH.'.gitignore', $gitignore);

		$this->session->set_flashdata('msg', lang('Cache Files Deleted!'));
		redirect('admin/actions/view');
	}

	public function update_server_cache()
	{
		$this->load->library('Remote_server_xml_rpc');
		$this->remote_server_xml_rpc->update_cache();
		$this->session->set_flashdata('msg', lang('Server Cache Updating Complete!'));
		redirect('admin/actions/view');
	}
}

/* End of file admin/actions.php */
/* Location: ./application/controllers/admin/actions.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @author      momo-i
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @copyright   Copyright (c) 2011-, www.momo-i.org
 * @license     http://www.opensource.org/licenses/Apache-2.0
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

/**
 * XtraUpload Actions Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        https://gitorious.org/xtraupload-v3
 */
class Actions extends CI_Controller {

	/**
	 * Constructor
	 *
	 * Load admin_access model
	 *
	 * @see    Admin_access
	 * @author Matthew Glinski
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	/**
	 * index
	 *
	 * Redirect actions view
	 *
	 * @see	   view()
	 * @author Matthew Glinski
	 * @return void
	 */
	public function index()
	{
		redirect('admin/actions/view');
	}

	/**
	 * view
	 *
	 * Show some actions view.
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
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

	/**
	 * php_info
	 *
	 * Display phpinfo()
	 *
	 * @author Matthew Glinski
	 * @author momo-i
	 * @return void
	 */
	public function php_info($type = INFO_ALL)
	{
		ob_start();
		phpinfo(1 | 4 | 8 | 16 | 32);
		$phpinfo = ob_get_contents();
		ob_end_clean();
		$phpinfo = preg_replace('#(.*\n)+<body.*#', '', $phpinfo);
		$phpinfo = preg_replace('#</div></body></html>#', '', $phpinfo);
		$phpinfo = preg_replace('#h1 class="(.*?)"#', 'h1', $phpinfo);
		$phpinfo = preg_replace('#tr class="(.*?)"#', 'tr', $phpinfo);
		$phpinfo = preg_replace('#td class="(.*?)"#', 'td', $phpinfo);
		$phpinfo = preg_replace('#table border="0" cellpadding="3" width="600"#', 'table id="file_list_table"', $phpinfo);
		$data['phpinfo'] = $phpinfo;
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('PHP Info')));
		$this->load->view($this->startup->skin.'/admin/actions/info', $data);
		$this->load->view($this->startup->skin.'/footer');
		//phpinfo();
	}

	/**
	 * run_cron
	 *
	 * Run cron jobs.
	 *
	 * @return void
	 */
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

	/**
	 * clear_cache
	 *
	 * Cleaning cache files.
	 *
	 * @return void
	 */
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
*
!index.html

EOF;
		write_file(CACHEPATH.'index.html', $html);
		write_file(CACHEPATH.'.gitignore', $gitignore);

		$this->session->set_flashdata('msg', lang('Cache Files Deleted!'));
		redirect('admin/actions/view');
	}

	/**
	 * update_server_cache
	 *
	 * Update Remote Server Cache.
	 *
	 * @see Remote_server_xml_rpc::update_cache
	 */
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

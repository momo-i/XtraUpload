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
 * XtraUpload Cron Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Cron extends CI_Controller {

	private $server = false;

	public function __construct()
	{
		parent::__construct();
		if(!defined('IN_CRON'))
		{
			show_404();
		}

		$this->server = base_url();
		$this->_run_cron();
	}

	public function index()
	{
		return;
	}

	private function _run_cron()
	{
		// run plugin cron files
		$this->_extend_cron();
	}

	private function _extend_cron()
	{
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
				$cron_extend = new $name($this->server);
				unset($cron_extend);
			}
		}
		closedir ($files);
	}
}

/* End of file cron.php */
/* Location: ./application/controllers/cron.php */

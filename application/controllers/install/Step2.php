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
 * XtraUpload Step2 Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Step2 extends CI_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Step2::index()
	 *
	 * Show step2 page
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$data = $this->_check_vers();
		$this->load->view('install/header');
		$this->load->view('install/step2', $data);
		$this->load->view('install/footer');
	}

	/**
	 * Step2::_check_vers()
	 *
	 * Get required application version for install
	 *
	 * @access	private
	 * @return	array
	 */
	private function _check_vers()
	{
		$chmod = array();
		$base = realpath(BASEPATH.'/../');
		$chmod[$base.'/application/config/config.php'] = "0666";
		$chmod[$base.'/application/config/database.php'] = "0666";
		$chmod[$base.'/filestore'] = "0777";
		$chmod[$base.'/temp'] = "0777";
		$chmod[$base.'/application/cache'] = "0777";
		$chmod[$base.'/thumbstore'] = "0777";
		$chmod[$base.'/application/logs'] = "0777";
		$data['is_chmod'] = true;
		$data['pass_fail'] = array();
		foreach($chmod as $file => $perm)
		{
			$data['pass_fail'][$file]['perm'] = $perm;
			if(!is_writeable($file))
			{
				if( ! @chmod($file, (int)$perm))
				{
					$data['is_chmod'] = false;
					$data['pass_fail'][$file]['html'] = '<span style="color:#FF0000; font-size:4"><strong>'.lang('Failed')."</strong></span>\n";
				}
				else
				{
					$data['pass_fail'][$file]['html'] = '<span style="color:#009900; font-size:4"><strong>'.lang('Passed')."</strong></span>\n";
				}
			}
			else
			{
				$data['pass_fail'][$file]['html'] = '<span style="color:#009900; font-size:4"><strong>'.lang('Passed')."</strong></span>\n";
			}
		}

		$phpver = (int)str_replace('.', '', (string)phpversion());
		if($phpver < 530)
		{
			$data['is_chmod'] = false;
			$data['phpver']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['phpver'] .= sprintf(lang('Please update php to the latest version in the %s'), '<a href="http://php.net/downloads.php">'.lang('v5.6 code branch').'</a>')."\n";
		}
		elseif($phpver < 560)
		{
			$data['phpver'] = '<span style="color: #FF4000; font-size: 4"><strong>'.lang('Warning').'</strong></span><br>';
			$data['phpver'] .= sprintf(lang('Please update php to the latest version in the %s'), '<a href="http://php.net/downloads.php">'.lang('v5.6 code branch').'</a>')."\n";
		}
		else
		{
			$data['phpver'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		if(strcmp(PHP_OS, 'Linux') === 0)
		{
			$arch = 'Unknown';
			if(function_exists('posix_uname'))
			{
				$osinfo = posix_uname();
				$arch = $osinfo['machine'];
			}
			else
			{
				$osinfo = @shell_exec('uname -m');
				if($osinfo)
				{
					$arch = trim($osinfo);
				}
			}
			if(strcmp($arch, 'x86_64') === 0)
			{
				$data['arch'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
			}
			elseif(preg_match('#i[0-9]+#', $arch))
			{
				$data['arch'] = '<span style="color: #FF4000; font-size: 4"><strong>'.lang('Warning').'</strong></span><br>';
				$data['arch'] = lang('Your OS architecture is 32 bit. Maybe some functions will be restricted.');
			}
			$data['archver'] = $arch;
		}

		if((bool)ini_get('safe_mode') && $phpver < 530)
		{
			$data['safemode'] = lang('On');
			$data['safemsg'] = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['safemsg'] = lang('XtraUpload requires SafeMode Off.');
		}
		else
		{
			$data['safemode'] = lang('Off');
			$data['safemsg'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		if(function_exists('gd_info'))
		{
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$data['gd_ver'] = $match[0];
		}
		else
		{
			$data['gd_ver'] = "0";
		}

		if(( (int)str_replace('.', '', (string)$data['gd_ver']) < 2))
		{
			$data['is_chmod'] = false;
			$data['gdmsg']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['gdmsg'] .= sprintf(lang('Please recompile php with %s'), '<a href="http://php.net/manual/image.installation.php">'.lang('GD2 support')."</a>\n");
		}
		else
		{
			$data['gdmsg'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		if(isset($ver_info["FreeType Support"]))
		{
			$data['fts'] = lang('Yes');
			$fts = true;
		}
		else
		{
			$data['fts'] = lang('No');
			$fts = false;
		}

		if(!$fts)
		{
			$data['ftsmsg']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['ftsmsg'] .= sprintf(lang('Please recompile php with %s'), '<a href="http://php.net/manual/image.installation.php">'.lang('FreeType support')."</a>\n");
		}
		else
		{
			$data['ftsmsg'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		if(function_exists('simplexml_load_file'))
		{
			$data['slf'] = lang('Yes');
			$slf = true;
		}
		else
		{
			$data['slf'] = lang('No');
			$slf = false;
		}

		if(!$slf)
		{
			$data['slfmsg']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['slfmsg'] .= sprintf(lang('Please recompile php with %s'), '<a href="http://php.net/manual/book.simplexml.php">'.lang('SimpleXML support')."</a>\n");
		}
		else
		{
			$data['slfmsg'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		if(defined('FTP_ASCII'))
		{
			$data['ftpc'] = lang('Yes');
			$ftpc = true;
		}
		else
		{
			$data['ftpc'] = lang('No');
			$ftpc = false;
		}

		if(!$ftpc)
		{
			$data['ftpcmsg']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['ftpcmsg'] .= sprintf(lang('Please recompile php with %s'), '<a href="http://php.net/manual/book.ftp.php">'.lang('FTP support')."</a>\n");
		}
		else
		{
			$data['ftpcmsg'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
		}

		return $data;
	}

}

/* End of file install/Step2.php */
/* Location: ./application/controllers/install/Step2.php */

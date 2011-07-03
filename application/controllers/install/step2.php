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
 * XtraUpload Step2 Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Step2 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = $this->_check_vers();
		$this->load->view('install/header');
		$this->load->view('install/step2', $data);
		$this->load->view('install/footer');
	}

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

		if(( (int)str_replace('.', '', (string)phpversion()) < 530))
		{
			$data['is_chmod'] = false;
			$data['phpver']  = '<span style="color: #FF0000; font-size: 4"><strong>'.lang('Failed').'</strong></span><br>';
			$data['phpver'] .= sprintf(lang('Please update php to the latest version in the %s'), '<a href="http://php.net/downloads.php">'.lang('v5.3 code branch').'</a>')."\n";
		}
		else
		{
			$data['phpver'] = '<span style="color: #009900; font-size: 4"><strong>'.lang('Passed').'</strong></span>'."\n";
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

/* End of file install/step2.php */
/* Location: ./application/controllers/install/step2.php */

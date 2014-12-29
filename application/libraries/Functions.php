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
 * XtraUpload Functions Library
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class Functions {

	/**
	 * Functions::get_rand_id()
	 *
	 * Returns random strings
	 *
	 * @access	public
	 * @param	int		$length	Password length
	 * @return	string	random strings
	 */
	public function get_rand_id($length=10)
	{
		$password = "";
		$vals = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-'; 
		
		while (strlen($password) < $length) 
		{
			$num = mt_rand() % strlen($vals);
			
			if($num > 60)
			{
				$num = mt_rand(0, 60);
			}
			
			$password .= substr($vals, $num+4, 1);
		}
		return $password;
	}

	/**
	 * Functions::get_server_load()
	 *
	 * Get server load average
	 *
	 * @access	public
	 * @param	int		$moving_average	moving average
	 * @return	int
	 */
	public function get_server_load($moving_average=0) 
    { 
		if(is_readable('/proc/loadavg'))
		{
			$fp = fopen('/proc/loadavg', 'r');
			$stats = substr(fgets($fp), 2, 2);
    	    return str_replace(',', '', $stats[$moving_average]);
		}
		else
		{
			return 0;
		}
    }

	/**
	 * Functions::gen_pass()
	 *
	 * Generate password
	 *
	 * @access	public
	 * @param	int		$length	Password length
	 * @param	bool	$caps	Use upper case or not
	 * @return	string	password
	 */
	public function gen_pass($length, $caps=true)
	{
		$password = "";
		if(!$caps)
		{
			$vals = 'abchefghjkmnpqrstuvwxyz0123456789'; 
		}
		else
		{
			$vals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabchefghjkmnpqrstuvwxyz0123456789'; 
		}
		$length;
		while (strlen($password) < $length) 
		{
			mt_getrandmax();
			$num = rand() % strlen($vals);
			$password .= substr($vals, $num+4, 1);
		}
		return $password;
	}

	/**
	 * Functions::check_login
	 *
	 * Check user logged-in or not
	 *
	 * @access	public
	 * @param	string	$send	Redirect path
	 * @return	void
	 */
	public function check_login($send='/user/login')
	{
		$CI =& get_instance();
		if(!$CI->session->userdata('id'))
		{
			redirect($send);
			exit();
		}
	}

	/**
	 * Functions::elipsis()
	 *
	 * Elipsis strings
	 *
	 * @access	public
	 * @param	string	$str	Want to elipsis string
	 * @param	int		$count	Want to elipsis count
	 * @return	string
	 */
	public function elipsis($str, $count = 13)
	{
		if(mb_strlen($str) <= ($count*3))
		{
			return $str;
		}
		
		$parts = $this->_str_split_unicode($str, 3);
		$i=0;
		$return='';
		while(($count-3) >= ($i))
		{
			$return .= $parts[$i];
			$i++;
		}
		
		$return .= '&hellip;';
		$return .= $parts[(count($parts) - 3)].$parts[(count($parts) - 2)].end($parts);
		return $return;
	}

	/**
	 * Functions::is_image()
	 *
	 * Check file is image or not
	 *
	 * @access	public
	 * @param	string	$file	Want to check filename
	 * @return	bool	true|false
	 */
	public function is_image($file)
	{
		$img_ext = array('jpg', 'gif', 'jpeg', 'png');
		log_message('debug', "file: $file");
		if(!preg_match('#\.#', basename($file)))
		{
			log_message('debug', 'No extension.');
			return FALSE;
		}
		if(function_exists('pathinfo'))
		{
			$pathinfo = pathinfo($file);
			$file_ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : "";
		}
		else
		{
			list($name, $file_ext) = explode('.', basename($file));
		}
		if (in_array(strtolower($file_ext), $img_ext))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}

	/**
	 * Functions::get_file_type_icon()
	 *
	 * Returns file type icon
	 *
	 * @access	public
	 * @param	string	$type	File type
	 * @return	string	Icon filename
	 */
	public function get_file_type_icon($type)
	{
		if(file_exists(ROOTPATH.'/assets/images/files/'.$type.'.png'))
		{
			return $type.'.png';
		}
		else
		{
			return 'default.png';
		}
	}

	/**
	 * Functions::get_json_filetype_list()
	 *
	 * Returns file type list to json
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_json_filetype_list()
	{
		return '"3gp", "7z", "aca", "ai", "api", "app", "as", "ascx", "asmx", "asp", "aspx", "avi", "avs", "axt", "bash", "bat", "bmp", "c", "cab", "cal", "cat", "cda", "cf", "chm", "cnf", "conf", "config", "cpl", "cpp", "crt", "cs", "csproj", "css", "csv", "cue", "dar", "db", "dbp", "dem", "disco", "dll", "dng", "doc", "dot", "dpk", "dpr", "dps", "dtq", "dun", "etp", "exe", "fdb", "fhf", "fla", "flv", "fnd", "fon", "gif", "gz", "h", "hlp", "hol", "htm", "html", "htt", "hxc", "hxi", "hxk", "hxs", "hxt", "icm", "ini", "ins", "iqy", "iso", "its", "jar", "java", "jbf", "job", "jpeg", "jpf", "jpg", "js", "lnk", "m3u", "m3v", "m4a", "m4p", "m4v", "mad", "map", "mapup", "mat", "mdb", "mdf", "mht", "mml", "mov", "mp3", "mp4", "mpeg", "mpg", "msc", "msg", "msi", "ncd", "nfo", "none", "nrg", "ogg", "ost", "otf", "pas", "pdf", "pdi", "pet", "pfm", "php", "pif", "plg", "pmc", "", "pot", "ppk", "pps", "ppt", "prf", "psd", "psp", "pub", "qbb", "rar", "rb", "rc", "rct", "rdp", "refresh", "reg", "res", "resx", "rmvb", "rss", "rtf", "sdl", "sea", "sh", "shs", "sln", "sql", "suo", "swf", "tar", "tdf", "tdl", "theme", "tiff", "ttf", "txt", "url", "vb", "vbproj", "vbs", "vcard", "vcf", "vob", "vsmacros", "wab", "wma", "wmv", "wpl", "wri", "wsc", "xhtml", "xla", "xls", "xml", "xpi", "xsd", "xsl", "xslt", "xsn", "zip"';
	}
	
	/**
	 * Functions::get_filesize_prefix()
	 *
	 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
	 *
	 * This function deprecated, use byte_format()
	 *
	 * @deprecated	3.1.0
	 * @access	public
	 * @param	int		$size	Filesize
	 * @return	string
	 */
	public function get_filesize_prefix($size)
	{
	    if( ! function_exists('byte_format'))
	    {
	        $ci =& get_instance();
	        $ci->load->helper('number');
	    }
	    return byte_format($size);
	}

	/**
	 * Functions::parse_version()
	 *
	 * Parse valid version or not
	 *
	 * @access	public
	 * @param	string	$v			Version number
	 * @param	bool	$details	Show Alpha/Beta/RC/STABLE or not
	 * @return	string
	 */
	public function parse_version($v, $details=true)
	{
		if(!stristr($v, ','))
		{
			return lang('Not Valid Version Number!');
		}
		
		$parts = explode(',',$v);
		$version = $parts[0];
		
		if($details)
		{
			$part = (int)str_replace('.','',$parts[1]);
			if($part < 10)
			{
				$ver = explode('.',$parts[1]);
				$part = (int)($ver[3]);
				$version .= ' [ALPHA-'.round($part / 1).']';
			}
			else if($part < 100)
			{
				$ver = explode('.',$parts[1]);
				$part = (int)($ver[2].'0');
				$version .= ' [BETA-'.round($part / 10).']';
			}
			else if($part < 1000)
			{
				$ver = explode('.',$parts[1]);
				$part = (int)($ver[1].'00');
				$version .= ' [RC-'.round($part / 100).']';
			}
			else
			{
				if($part > 1000)
				{
					//$version .= '; Build: '.(int)substr($part,1,3);
					$version .= ' r'.(int)substr($part,1,3).' STABLE';
				}
				else
				{
					$version .= ' [STABLE]';
				}
			}
		}
		return $version;
	}

	/**
	 * Functions::_str_split_unicode()
	 *
	 * str_split for unicode
	 *
	 * @access	private
	 * @param	string	$str	String
	 * @param	int		$l		Length
	 * @return	string
	 */
	private function _str_split_unicode($str, $l = 0)
	{
		if ($l > 0)
		{
			$ret = array();
			$len = mb_strlen($str, "UTF-8");
			for ($i = 0; $i < $len; $i += $l)
			{
				$ret[] = mb_substr($str, $i, $l, "UTF-8");
			}
			return $ret;
		}
		return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

}

/* End of file Functions.php */
/* Location: ./application/libraries/Functions.php */

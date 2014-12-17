<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
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

// ------------------------------------------------------------------------

/**
 * XtraUpload Remote File Upload Library
 *
 * @package		XtraUpload
 * @subpackage	Library
 * @category	Library
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

class CI_Remotefile {

	public $buffer = 204800;
	public $tmp_dir = ROOTPATH.'/temp';
	public $error = '';
	private $headers = '';
	public $CI = '';

	/**
	 * File Download Constructor
	 *
	 * The constructor sets up the download system as ready for files
	 */		
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "Remote File Transfer Class Initialized");
	}
	
	/**
	 * Setup
	 *
	 * Sets Config Vars
	 *
	 * @access	public
	 * @param	Config Array
	 * @return	null
	 */
	public function setup($config = array())
	{
		if (count($config) > 0)
		{
			$this->_initialize($config);
		}
	}
	
	public function get_headers($url)
	{
		$this->headers = get_headers($url, true);
		return $this->headers['Content-Length'];
	}
	
	public function headers_to_array($string)
	{
		$lines = explode("\n", $string);
		foreach( $lines as $line)
		{
			$header = explode(":", $line);
			$this->headers[$header[0]] = (isset($header[1]) ? $header[1] : '' );
		}
	}
	
	public function remote_size()
	{
		return $this->headers['Content-Length'];
	}
	
	public function get_referer()
	{
		if(isset($this->headers['Referer']))
		{
			return $this->headers['Referer'];
		}
		else
		{
			return false;
		}
	}
	
	public function is_redirect()
	{
		if(isset($this->headers['Location']))
		{
			return $this->headers['Location'];
		}
		else
		{
			return false;
		}
	}
	 
	public function fetch_file($url, $fid, $max_size, $fp=NULL)
	{
		$parsedurl = parse_url($url, PHP_URL_SCHEME);
		
		if($parsedurl == 'http')
		{
			return $this->_http_transfer($url, $fid, $max_size, $fp);
		}
		else if($parsedurl == 'ftp')
		{
			return $this->_ftp_transfer($url, $fid, ($max_size * 1024 * 1024));
		}
		else
		{
			exit;
		}
	}
	
	private function _http_transfer($url, $fid, $max_size, $fp=NULL)
	{
		$nurl = $url;
		$parsedurl = parse_url($url);
		if(isset($parsedurl['user']))
		{
			$user = $parsedurl['user'];
		}
		
		if(isset($parsedurl['pass']))
		{
			$pass = $parsedurl['pass'];
    	}
		
		$host = $parsedurl["host"];
		$hostname = $host;
		$port = "80";
		$query = "";
		$port = $port ? $port : "80";
		
		// Follow redirection
		$this->get_headers($url);
		$is_redirect = false;
		while($this->is_redirect())
		{
			$nurl = $this->is_redirect();
			$is_redirect = true;
			$this->get_headers($nurl);
		}
		
		if($is_redirect)
		{
			$url = $nurl;
			$parsedurl = parse_url($url);
			
			// Get items of new url
			$referer = $this->get_referer();
			$host = $parsedurl["host"];
			$hostname = $host;
			$port = $port ? $port : "80";
		}

		$sh = fsockopen($host, $port, $errid, $errmsg, 30);
		if (!$sh)
		{
			return false;
		}

		if (!$parsedurl["path"])
		{
			$parsedurl["path"] = "/";
		}

		$request = "";
		$request.= "GET ".$parsedurl["path"].(isset($parsedurl["query"]) ? '?'.$parsedurl["query"] : '')." HTTP/1.0\r\n";
		$request.= "Host: $hostname\r\n";
		
		if (isset($referer) && $referer != "")
		{
			$request.= "Referer: ".$referer."\r\n";
		}
		
		if (isset($pass) || isset($user))
		{
			$request.= "Authorization: Basic ".base64_encode($user.":".$pass)."\r\n";
		}
		
		$request.= "\r\n";

		//Send The Request 
		fwrite($sh, $request);

		// if no filepointer is given, make a temp file and open it for writing
		if(!$fp)
		{
			$send_file_name = true;
			$tmpfname = tempnam($this->tmp_dir, "RFT-");
			$fp = fopen($tmpfname, "wb");
		}
		
		$size = $this->remote_size();
		if($size > ($max_size * 1024*1024))
		{
			fclose($sh);
			fclose($fp);
			return false;
		}
		
		$this->CI->db->insert('progress', array('progress' => 0, 'curr_time' => $_SERVER['REQUEST_TIME'] , 'total' =>  $size, 'start_time' => $_SERVER['REQUEST_TIME'], 'fid' => $fid));
		
		
		$i = $p = 0;
		$end_headers = false;
		$rstr='';
		// download the file
		while(!feof($sh))
		{
			$string = fread($sh, $this->buffer);
			if(!$end_headers)
			{
				if($test = stristr($string, "\r\n\r\n"))
				{
					if($is_redirect)
					{
						$headers = explode("\r\n\r\n", $string);
						$headers = $headers[0];
						$this->headers_to_array($headers);
						$size = $this->remote_size();
						$this->CI->db->where('fid', $fid);
						$this->CI->db->update('progress', array('total' => $size));
					}
					$string = str_replace("\r\n\r\n", '', $test);
					$end_headers = true;
				}
				else
				{
					continue;
				}
			}
			$p += strlen($string);
			fwrite($fp, $string);
			$string = NULL;
			if($i % 10 == 0)
			{
				$this->CI->db->where('fid', $fid);
				$this->CI->db->update('progress', array('progress' => $p, 'curr_time' => time()));
			}
		}
		fclose($sh);
		fclose($fp);
		
		// if passed a file pointer return true, if not return temp file name
		if(!$send_file_name)
		{
			return true;
		}
		else
		{
			return $tmpfname;
		}
	}
	
	private function _ftp_transfer($url, $fid, $max_size, $fp=NULL)
	{
		$url = trim($url);
		$nurl = $url;
		$parsedurl = parse_url($url);
		
		$user = "anonymous";
		if(isset($parsedurl['user']))
		{
			$user = $parsedurl['user'];
		}
		
		$pass = 'anonymous@example.com';
		if(isset($parsedurl['pass']))
		{
			$pass = $parsedurl['pass'];
    	}
		
		$host = $hostname = $parsedurl["host"];
		$port = isset($parsedurl["port"]) ? $parsedurl["port"] : "21";
		$path = substr($parsedurl['path'], 1);
		
		$this->CI->load->library('ftp');
		
		$config['hostname'] = $hostname;
		$config['username'] = $user;
		$config['password'] = $pass;
		$config['port']     = $port;
		$config['passive']  = TRUE;
		$config['debug']    = FALSE;
		$this->CI->ftp->connect($config);
		
		if($this->CI->ftp->error)
		{
			$error = $this->CI->ftp->get_error();
			log_message('error', $error);
			$this->error = $error;
			show_error($error);
			return false;
		}		
		
		$size = $this->CI->ftp->remote_filesize($path);
		if(!$size or ($max_size < $size))
		{
			log_message('error', "CAN NOT FTP SIZE");
			show_error("CAN NOT FTP SIZE");
			$this->error = 'CAN NOT FTP SIZE';
			return false;
		}
		
		$this->CI->db->insert('progress', 
			array(
				'progress' => 0, 
				'curr_time' => $_SERVER['REQUEST_TIME'], 
				'total' =>  $size, 
				'start_time' => $_SERVER['REQUEST_TIME'], 
				'fid' => $fid)
		);
		
		$fname = $this->CI->ftp->download_xu2($path, $fid, $max_size);
		if(!$fname)
		{
			$this->error = 'CAN NOT FTP TRANSFER';
			log_message('error', "CAN NOT FTP TRANSFER");
			show_error("CAN NOT FTP TRANSFER");
			return false;
		}
		
		$this->CI->ftp->close();
		
		return $fname;
	}
}

/* End of file remotefile.php */
/* Location: ./application/libraries/remotefile.php */

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
 * XtraUpload File Download Class
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XU_Filedownload {

	/**
	 * Download file
	 *
	 * @access	public
	 * @var		string
	 */
	public $file = null;

	/**
	 * Resume flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $resume = true;

	/**
	 * Download filename
	 *
	 * @access	public
	 * @var		string
	 */
	public $filename = null;

	/**
	 * Mime type
	 *
	 * @access	public
	 * @var		string
	 */
	public $mime = null;

	/**
	 * Download speed
	 *
	 * @access	public
	 * @var		int
	 */
	public $speed = 0;

	/**
	 * Bandwidth
	 *
	 * @access	public
	 * @var		int
	 */
	public $bandwidth = 0;

	/**
	 * CodeIgniter
	 *
	 * @access	private
	 * @var		object
	 */
	private $CI;

	/**
	 * File length
	 *
	 * @access	private
	 * @var		int
	 */
	private $_file_len = 0;

	/**
	 * File mod
	 *
	 * @access	private
	 * @var		int
	 */
	private $_file_mod = 0;

	/**
	 * File type
	 *
	 * @access	private
	 * @var		int
	 */
	private $_file_type = 0;

	/**
	 * File section
	 *
	 * @access	private
	 * @var		int
	 */
	private $_file_section = 0;

	/**
	 * Buffer size
	 *
	 * @access	private
	 * @var		int
	 */
	private $_bufsize = 8192;

	/**
	 * Seek start
	 *
	 * @access	private
	 * @var		int
	 */
	private $_seek_start = 0;

	/**
	 * Seek end
	 *
	 * @access	private
	 * @var		int
	 */
	private $_seek_end = -1;

	/**
	 * Setup
	 *
	 * @access	private
	 * @var		bool
	 */
	private $_setup = false;

	/**
	 * File Download Constructor
	 *
	 * The constructor sets up the download system as ready for files
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "XtraUpload Download Class Initialized");
	}

	/**
	 * Filedownload::set_config()
	 *
	 * Sets Config Vars
	 *
	 * @access	public
	 * @param	array	$config	Config
	 * @return	void
	 */
	 public function set_config($config = array())
	 {
		if (count($config) > 0)
		{
			$this->_initialize($config);
		}
	 }

	/**
	 * Filedownload::send_download
	 *
	 * Begins download
	 *
	 * @access	public
	 * @param	array	$config	Config
	 * @return	int|false
	 */
	public function send_download($config = array())
	{
		$this->CI =& get_instance();
		// Setup the download, or die on error.
		$this->_initialize($config);

		// Grab some vars
		$seek = $this->_seek_start;
		$speed = $this->speed;
		$bufsize = $this->_bufsize;
		$packet = 1;

		// Make sure we dont timeout wheil serving the download

		@set_time_limit(0);
		$this->bandwidth = 0;

		// THIS IS VERY IMPORTANT, DO NOT REMOVE THIS CALL UNDER ANY CIRCUMSTANCES
		// --------------------
		// START IMPORTANT CALL
		session_write_close();
		// END IMPORTANT CALL
		// --------------------

		// Get the filesize and filename
		$size = filesize($this->file);
		if ($seek > ($size - 1)) $seek = 0;
		if ($this->filename == null) $this->filename = basename($this->file);

		// Open a file pointer to the file
		$res = fopen($this->file,'rb');

		// If partial request skip to the part we want
		if ($seek) fseek($res , $seek);
		if ($this->_seek_end < $seek) $this->_seek_end = $size - 1;

		$this->_send_headers($size, $seek, $this->_seek_end); //always use the last seek
		$size = $this->_seek_end - $seek + 1;

		$packet = 0;

		// While the user is connected
		while (!($user_aborted = connection_aborted() || connection_status() == 1) && $size > 0)
		{
			$startpacket = microtime(1);

			if ($size < $bufsize)
			{
				echo $this->fullread($res , $size);
				$this->bandwidth += $size;
			}
			else
			{
				echo $this->fullread($res , $bufsize);
				$this->bandwidth += $bufsize;
			}

			$size -= $bufsize;
			flush();

			if($speed > 0)
			{
				$timeend = microtime(1);

				$packettime = $timeend - $startpacket;
				$microsleep = ($bufsize / ($speed * 1024))*1000*1000 - $packettime;
				usleep($microsleep);
			}
		}
		fclose($res);
		return $this->bandwidth;
	}

	/**
	 * Filedownload::fullread()
	 *
	 * Read a file segment
	 *
	 * @access	public
	 * @param	resource	$fh		File handle
	 * @param	int			$size	file size
	 * @return	int
	 */
	public function fullread($fh, $size)
	{
		$buffer ='';
		$done = 0;
		while($done < $size)
		{
			if ($size - $done > $this->_bufsize)
			{
				$thisbuff = fread($fh, $this->_bufsize);
				$buffer .= $thisbuff;
				$did = strlen($thisbuff);
			}
			else
			{
				$thisbuff = fread($fh, $size - $done);
				$buffer .= $thisbuff;
				$did = strlen($thisbuff);
			}
			$done = $done + $did;
		}
		return $buffer;
	}

	/**
	 * Filedownload::_initialize()
	 *
	 * Initialize the user preference
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @access	private
	 * @param	array	$config	config preferences
	 * @return	true|void
	 */
	private function _initialize($config = array())
	{
		if($this->_setup)
		{
			return true;
		}
		// Set Each Config Value
		foreach ($config as $key => $val)
		{
			$this->$key = $val;
		}

		if($this->mime == NULL)
		{
			// Grab the file extension
			if(preg_match('#\.#', $this->file))
			{
				$x = explode('.', $this->file);
				$extension = end($x);
			}
			else
			{
				$extension = "";
			}

			// Load the mime types
			@include(APPPATH.'config/mimes.php');

			// Set a default mime if we can't find it
			if ( ! isset($mimes[$extension]))
			{
				$this->mime = 'application/octet-stream';
			}
			else
			{
				$this->mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
			}
		}

		// Is the client requesting a partial download?
		if ($this->CI->input->server('HTTP_RANGE'))
		{
			// What part of the file is the client requesting
			$seek_range = substr($this->CI->input->server('HTTP_RANGE') , strlen('bytes='));
			$range = explode('-',$seek_range);

			if ($range[0] > 0)
			{
				$this->_seek_start = intval($range[0]);
			}

			if ($range[1] > 0)
			{
				$this->_seek_end = intval($range[1]);
			}
			else
			{
				$this->_seek_end = -1;
			}

			// Do we want to serve a partial request?
			if (!$this->resume)
			{
				$this->_seek_start = 0;
			}
			else
			{
				$this->_file_section = 1;
			}

		}
		else
		{
			// Serve the whole file, from the beginning
			$this->_seek_start = 0;
			$this->_seek_end = -1;
		}
	}

	/**
	 * Filedownload::_send_headers
	 *
	 * Sends Download Headers to the client, describing the download
	 *
	 * @access	private
	 * @param	int		$size		Size of file
	 * @param	int		$seek_start	Begining of file
	 * @param	int		$seek_end	End of file
	 * @return	void
	 */
	private function _send_headers($size, $seek_start = null, $seek_end = null)
	{
		// Generate the server headers
		header('Content-type: ' . $this->mime);
		header('Content-Disposition: attachment; filename="' . $this->filename . '"');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');

		if ($this->_file_section && $this->resume)
		{
			header("HTTP/1.0 206 Partial Content");
			header("Status: 206 Partial Content");
			header("Accept-Ranges: bytes");
			header("Content-Range: bytes $seek_start-$seek_end/$size");
			header("Content-Length: " . ($seek_end - $seek_start + 1));
		}
		else
		{
			header("Content-Length: $size");
		}

		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
		{
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else
		{
			header('Pragma: no-cache');
		}
	}
}

/* End of file Filedownload.php */
/* Location: ./application/libraries/Filedownload.php */

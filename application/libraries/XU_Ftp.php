<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FTP Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/ftp.html
 */
class XU_FTP extends CI_FTP {

	private $_types = array();

	/**
	 * Constructor
	 *
	 * @param	array	$config
	 * @return	void
	 */
	public function __construct($config = array())
	{
		empty($config) OR $this->initialize($config);
		log_message('debug', 'FTP Class Initialized');

		$this->_types = array(
			'ftp_no_connection' => lang('Unable to locate a valid connection ID. Please make sure you are connected before peforming any file routines.'),
			'ftp_unable_to_connect' => lang('Unable to connect to your FTP server using the supplied hostname.'),
			'ftp_unable_to_login' => lang('Unable to login to your FTP server. Please check your username and password.'),
			'ftp_unable_to_makdir' => lang('Unable to create the directory you have specified.'),
			'ftp_unable_to_changedir' => lang('Unable to change directories.'),
			'ftp_unable_to_chmod' => lang('Unable to set file permissions. Please check your path. Note: This feature is only available in PHP 5 or higher.'),
			'ftp_unable_to_upload' => lang('Unable to upload the specified file. Please check your path.'),
			'ftp_unable_to_download' => lang('Unable to download the specified file. Please check your path.'),
			'ftp_no_source_file' => lang('Unable to locate the source file. Please check your path.'),
			'ftp_unable_to_rename' => lang('Unable to rename the file.'),
			'ftp_unable_to_delete' => lang('Unable to delete the file.'),
			'ftp_unable_to_move' => lang('Unable to move the file. Please make sure the destination directory exists.')
		);

	}

	// --------------------------------------------------------------------

	/**
	 * Download a file
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @param   bool
	 * @return  bool
	 */
	public function download_xu($remote_file, $fid, $max_size)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = TRUE;

		// if no filepointer is given, make a temp file and open it for writing
		$tmpfname = tempnam(ROOTPATH.'/temp', "RFT-");
		$l_fp = fopen($tmpfname, "wb");

		// Initate the download
		$i=0;
		$CI =& get_instance();

		$ret = ftp_nb_fget($this->conn_id, $l_fp, $remote_file, FTP_BINARY);
		while ($ret == FTP_MOREDATA)
		{
			if($i % 10 == 0)
			{
				$fstat = fstat($l_fp);
				$p = $fstat[7];
				$CI->db->where('fid', $fid);
				$CI->db->update('progress', array('progress' => $p, 'curr_time' => time()));
			}

			// Continue downloading...
			$ret = ftp_nb_continue($this->conn_id);
			$i++;
		}

		// Has it finished completly?
		if ($ret != FTP_FINISHED)
		{
			log_message('error', "FTP TRANSFER FAILED");
			$result = FALSE;
		}

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$msg = 'ftp_unable_to_download';
				$this->_error($msg);
			}
			else
			{
				$this->error = TRUE;
				$this->error_msg = 'ftp_unable_to_download';
			}
			return FALSE;
		}

		return $tmpfname;
	}

	// --------------------------------------------------------------------

	/**
	 * Display error message
	 *
	 * @param	string	$line
	 * @return	void
	 */
	protected function _error($line)
	{
		$CI =& get_instance();
		show_error($this->_types[$line]);
	}

}

/* End of file XU_Ftp.php */
/* Location: ./application/libraries/XU_Ftp.php */

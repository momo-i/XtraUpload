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
 * XtraUpload FTP Class
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XU_FTP extends CI_FTP {

	private $_types = array();

	/**
	 * Constructor
	 *
	 * @access	public
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

	/**
	 * XU_Ftp::download_xu()
	 *
	 * Download a file
	 *
	 * @access  public
	 * @param   string	$remote_file	Remote filename
	 * @param   string	$fid			File ID
	 * @param   bool	$max_size		Max file size
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

	/**
	 * Display error message
	 *
	 * @access	protected
	 * @param	string	$line	Error message
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

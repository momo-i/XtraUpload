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
 * XtraUpload Step4 Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Step4 extends CI_Controller {

	/**
	 * Encryption key
	 *
	 * @access	private
	 * @var		string
	 */
	private $_encryption_key;

	/**
	 * Base url
	 *
	 * @access	private
	 * @var		string
	 */
	private $_url;

	/**
	 * Cookie prefix
	 *
	 * @access	private
	 * @var		string
	 */
	private $_cookie_prefix;

	/**
	 * Step4::index()
	 *
	 * Show step4 page
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		if($this->_validate_post())
		{
			$this->load->view('install/header');
			$this->load->view('install/step4', array('enc' => $this->_encryption_key, 'url' => $this->_url));
			$this->load->view('install/footer');
		}
		else
		{
			redirect('install/step/3');
		}
	}

	/**
	 * Step4::_validate_post()
	 *
	 * Post validation
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _validate_post()
	{
		if($this->input->post('url'))
		{
			$this->_cookie_prefix = $this->input->post('cookie_prefix');
			$this->_encryption_key = $this->input->post('encryption_key');

			if(empty($this->_cookie_prefix))
			{
				$this->_cookie_prefix = substr( uniqid(md5(rand(1,99999999))) , 0, -16);
			}

			if(empty($this->_encryption_key))
			{
				$this->_encryption_key = uniqid(md5(rand(1,99999999)));
			}

			$this->_url = $this->input->post('url');
			$this->_url = str_replace('index.php', '', $this->_url);
			if(substr($this->_url, -1) != '/')
			{
				$this->_url .= '/';
			}

			if(!$this->_write_config() OR !$this->_write_database() OR $this->_write_htaccess())
			{
				return false;
			}
			return true;
		}
	}

	/**
	 * Step4::_write_config()
	 *
	 * Write config.php
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _write_config()
	{
		$template = ROOTPATH.'/application/config/config.php.template';
		$config = ROOTPATH.'/application/config/config.php';
		$fp = fopen($template, 'r');
		$out = fopen($config, 'w');
		if(!$fp)
		{
			return false;
		}
		do {
			$line = trim(fgets($fp));
			if(empty($line))
			{
				continue;
			}
			$line = str_replace('@BASEURL@', $this->_url, $line);
			$line = str_replace('@LANGUAGE@', $this->config->item('language'), $line);
			$line = str_replace('@ENCRYPTKEY@', $this->_encryption_key, $line);
			$line = str_replace('@COOKIEPREFIX@', $this->_cookie_prefix, $line);
			fwrite($out, $line . "\n");
		} while(!feof($fp));
		fclose($fp);
		fclose($out);
		return true;
	}

	/**
	 * Step4::_write_database()
	 *
	 * Write database.php
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _write_database()
	{
		$template = ROOTPATH.'/application/config/database.php.template';
		$config = ROOTPATH.'/application/config/database.php';
		$fp = fopen($template, 'r');
		$out = fopen($config, 'w');
		if(!$fp)
		{
			return false;
		}
		do {
			$line = trim(fgets($fp));
			if(empty($line))
			{
				continue;
			}
			$line = str_replace('@DBHOST@', $this->input->post('sql_server'), $line);
			$line = str_replace('@DBUSER@', $this->input->post('sql_user'), $line);
			$line = str_replace('@DBPASS@', $this->input->post('sql_pass'), $line);
			$line = str_replace('@DATABASE@', $this->input->post('sql_name'), $line);
			$line = str_replace('@DBENGINE@', $this->input->post('sql_engine'), $line);
			$line = str_replace('@DBPREFIX@', $this->input->post('sql_prefix'), $line);
			fwrite($out, $line . "\n");
		} while(!feof($fp));
		return true;
	}

	/**
	 * Step4::_write_htaccess()
	 *
	 * Write .htaccess
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _write_htaccess()
	{
		$orig_htaccess = ROOTPATH.'/application/config/htaccess';
		$new_htaccess = ROOTPATH.'/.htaccess';
		if(file_exists($new_htaccess))
		{
			return true;
		}
		if(@copy($orig_htacess, $new_htaccess))
		{
			return true;
		}
		return false;
	}

}

/* End of file install/Step4.php */
/* Location: ./application/controllers/install/Step4.php */

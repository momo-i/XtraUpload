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
 * XtraUpload Step4 Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Step4 extends CI_Controller {

	private $encryption_key;
	private $url;

	public function index()
	{

		if($this->_validate_post())
		{
			$this->load->view('install/header');
			$this->load->view('install/step4', array('enc' => $this->encryption_key, 'url' => $this->url));
			$this->load->view('install/footer');
		}
		else
		{
			redirect('install/step/3');
		}
	}

	private function _validate_post()
	{
		if($this->input->post('url'))
		{
			$this->cookie_prefix = $this->input->post('cookie_prefix');
			$this->encryption_key = $this->input->post('encryption_key');

			if(empty($this->cookie_prefix))
			{
				$this->cookie_prefix = substr( uniqid(md5(rand(1,99999999))) , 0, -16);
			}

			if(empty($this->encryption_key))
			{
				$this->encryption_key = uniqid(md5(rand(1,99999999)));
			}

			$this->url = $this->input->post('url');
			$this->url = str_replace('index.php', '', $this->url);
			if(substr($this->url, -1) != '/')
			{
				$this->url .= '/';
			}

			if(!$this->_write_config() OR !$this->_write_database())
			{
				return false;
			}
			return true;
		}
	}

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
			$line = str_replace('@BASEURL@', $this->url, $line);
			$line = str_replace('@LANGUAGE@', $this->config->item('language'), $line);
			$line = str_replace('@ENCRYPTKEY@', $this->encryption_key, $line);
			$line = str_replace('@COOKIEPREFIX@', $this->cookie_prefix, $line);
			fwrite($out, $line . "\n");
		} while(!feof($fp));
		fclose($fp);
		fclose($out);
		return true;
	}

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

}

/* End of file install/step4.php */
/* Location: ./application/controllers/install/step4.php */

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
 * XtraUpload Upload Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Upload extends CI_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		log_message('debug', 'XtraUpload Upload Controller Initialized !!');
		parent::__construct();
		$this->load->model('server/server_db');
	}

	/**
	 * Upload::index()
	 *
	 * Redirect Home::index()
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		redirect('home');
	}

	/**
	 * Upload::url()
	 *
	 * Show URL Upload page
	 *
	 * @access	public
	 * @return	void
	 */
	public function url()
	{
		$data = array(
			'server' => $this->server_db->get_random_server()->url,
			'file_icons' => $this->functions->get_json_filetype_list()
		);

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('URL Upload'), 'include_url_upload_js' => true));
		$this->load->view($this->startup->skin.'/upload/url', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Upload::get_progress()
	 *
	 * Get file upload progress
	 *
	 * @access	public
	 * @param	string	$id	File ID
	 * @return	void
	 */
	public function get_progress($fid)
	{
		$db = $this->db->get_where('progress', array('fid' => $fid));
		if($db->num_rows() != 1)
		{
			$json = '[{"total":"100", "sofar":"0", "start_time":"'.time().'"}]';
			log_message('debug', $json);
			echo $json;
		}
		else
		{
			$pro = $db->row();
			$json = '[{"total":"'.$pro->total.'", "sofar":"'.$pro->progress.'", "start_time":"'.$pro->start_time.'"}]';
			log_message('debug', $json);
			echo $json;
		}
	}

	/**
	 * Upload::url_process()
	 *
	 * Get URL upload process
	 *
	 * @access	public
	 * @return	void
	 */
	public function url_process()
	{
		$this->load->library('remotefile');
		// Load post data
		$uid = $this->input->post('fid');
		$url = $this->input->post('link');
		$user = $this->input->post('user');

		if(intval($user) != 0)
		{
			$userobj = $this->users->get_user_by_id($user);
			$this->startup->get_group($userobj->group);
			unset($userobj);
		}

		session_write_close();
		$file = $this->remotefile->fetch_file($url, $uid, intval($this->startup->group_config->upload_size_limit));

		if(is_file($file))
		{
			$is_image = $this->functions->is_image($file);
			$nfile = ROOTPATH.'/temp/'.basename($url);
			rename($file, $nfile);

			$this->files_db->new_file($nfile, $uid, $user, (bool)$is_image, base_url(), true);
			echo lang('URL Upload Complete!');
		}
		else
		{
			echo lang('URL Upload Failed!'.$this->remotefile->error_msg[0]);
		}
	}

	/**
	 * Upload::process()
	 *
	 * Upload process
	 *
	 * @access	public
	 * @param	string	$secid	Upload ID
	 * @param	int		$user	User ID
	 * @return	void
	 */
	public function process($secid='', $user=0)
	{
		if(intval($user) !== 0 && $this->session->userdata('login') !== TRUE)
		{
			log_message('debug', 'User not found?');
			echo intval($user)."\n\n";
			$userobj = $this->users->get_user_by_id(intval($user));
			$this->startup->get_group(intval($userobj->group));
			unset($userobj);
		}

		$allowed_types = !empty($this->startup->group_config->files_types) ? $this->startup->group_config->files_types : "*";
		$config['upload_path'] = ROOTPATH.'/temp/';
		$config['allowed_types'] = $allowed_types;
		$config['max_size'] = (1024 * intval($this->startup->group_config->upload_size_limit));
		$this->load->library('upload', $config);

		log_message('debug', 'Class: '.__CLASS__.' Function: '.__FUNCTION__.' Upload start '.print_r($config, true));
		if($this->upload->do_upload('file'))
		{
			$data = $this->upload->data();
			$file = $data['full_path'];

			$this->files_db->new_file($file, $secid, $user, (bool)$data['is_image'], base_url(), false);
			if ($this->input->post('no_flash'))
			{
				redirect('upload/complete/'.$secid);
			}
			else
			{
				log_message('debug', 'Upload Completed');
				echo "true|".lang('Upload Completed!');
			}
		}
		else
		{
			$this->files_db->set_upload_failed($secid, $this->upload->error_msg[0]);
			if ($this->input->post('no_flash'))
			{
				redirect('upload/failed/'.$secid);
				print "<pre>";
				var_dump($_FILES);
				print "</pre>";
			}
			else
			{
				print "<pre>";
				var_dump($_FILES);
				print "</pre>";
				echo "false|".lang('Upload Failed!');
			}
		}
	}

	/**
	 * Upload::get_links()
	 *
	 * Show link page
	 *
	 * @access	public
	 * @param	string	$secid	File ID
	 * @return	void
	 */
	public function get_links($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);
		$this->load->view($this->startup->skin.'/upload/links', $data);
	}

	/**
	 * Upload::failed()
	 *
	 * Show failed page
	 *
	 * @access	public
	 * @param	string	$secid	File ID
	 * @return	void
	 */
	public function failed($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);
		$this->load->view($this->startup->skin.'/header');
		$this->load->view($this->startup->skin.'/upload/failed', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Upload::complete()
	 *
	 * Show complete page
	 *
	 * @access	public
	 * @return	void
	 */
	public function complete($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);
		$this->load->view($this->startup->skin.'/header');
		$this->load->view($this->startup->skin.'/upload/complete', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Upload::file_upload_props()
	 *
	 * Return upload props
	 *
	 * @access	public
	 * @return	void
	 */
	public function file_upload_props()
	{
		$data = array(
			'descr' => $this->input->post('desc', true),
			'pass' => $this->input->post('password', true),
			'tags' => $this->input->post('tags', true),
			'feature' => intval($this->input->post('featured'))
		);

		foreach ($data as $key => $val)
		{
			if($val == 'undefined')
			{
				$data[$key] = '';
			}
		}

		if(!$fid = $this->input->post('fid', true))
		{
			return;
		}

		$this->files_db->update_file_info($fid, $data);
		echo lang('OK');
	}

	/**
	 * Upload::blank()
	 *
	 * Use in remote file upload javascript
	 *
	 * @access	public
	 * @return	void
	 */
	public function blank()
	{
		return;
	}

	public function plupload($secid='', $user=0)
	{
		if(intval($user) !== 0 && $this->session->userdata('login') !== TRUE)
		{
			log_message('debug', 'User not found?');
			echo intval($user)."\n\n";
			$userobj = $this->users->get_user_by_id(intval($user));
			$this->startup->get_group(intval($userobj->group));
			unset($userobj);
		}

		$allowed_types = !empty($this->startup->group_config->files_types) ? $this->startup->group_config->files_types : "*";
		$config['upload_path'] = ROOTPATH.'/temp/';
		$config['allowed_types'] = $allowed_types;
		$config['max_size'] = (1024 * intval($this->startup->group_config->upload_size_limit));
		$this->load->library('upload', $config);
		log_message('debug', '$_REQUEST: '.print_r($_REQUEST, true));
		log_message('debug', '$_FILES: '.print_r($_FILES, true));
		//$this->files_db->new_file($file, $secid, $user, (bool)$data['is_image'], base_url(), false);
		echo $this->upload->process_upload($_REQUEST, $_FILES);
	}

}

/* End of file Upload.php */
/* Location: ./application/controllers/Upload.php */

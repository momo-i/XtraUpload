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
 * XtraUpload Upload Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Upload extends CI_Controller {

	public function __construct()
	{
		log_message('debug', 'Upload Controller Initialized !!');
		parent::__construct();
		$this->load->model('server/server_db');
	}

	public function index()
	{
		redirect('home');
	}

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

	public function get_progress($fid)
	{
		$db = $this->db->get_where('progress', array('fid' => $fid));
		if($db->num_rows() != 1)
		{
			echo '[{"total":"100", "sofar":"0", "start_time":"'.time().'"}]';
		}
		else
		{
			$pro = $db->row();
			echo '[{"total":"'.$pro->total.'", "sofar":"'.$pro->progress.'", "start_time":"'.$pro->start_time.'"}]';
		}
	}

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
			echo lang('Upload Complete!');
		}
		else
		{
			echo lang('Upload Failed!');
		}
	}

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

		$config['upload_path'] = ROOTPATH.'/temp/';
		$config['allowed_types'] = $this->startup->group_config->files_types;
		$config['max_size'] = (1024 * intval($this->startup->group_config->upload_size_limit));
		$this->load->library('upload', $config);

		log_message('debug', 'Class: '.__CLASS__.' Function: '.__FUNCTION__.' Upload start');
		if($this->upload->do_upload('Filedata'))
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
			//$this->files_db->set_upload_failed($secid, str_replace('upload_', '', $this->upload->error_num[0]));
			$this->files_db->set_upload_failed($secid, str_replace('upload_', '', $this->upload->error_msg[0]));
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

	public function get_links($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);
		$this->load->view($this->startup->skin.'/upload/links', $data);
	}

	public function failed($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);
		$this->load->view($this->startup->skin.'/header');
		$this->load->view($this->startup->skin.'/upload/failed', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function complete($secid)
	{
		$data['link'] = $this->files_db->get_links($secid);

		$this->load->view($this->startup->skin.'/header');
		$this->load->view($this->startup->skin.'/upload/complete', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

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
}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */

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
 * XtraUpload Server Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Server extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('server/server_db');
	}

	public function index()
	{
		redirect('admin/server/view');
	}

	public function view()
	{
		$this->load->helper('string');
		$data['flash_message'] = '';

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['servers'] = $this->server_db->get_servers();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Servers')));
		$this->load->view($this->startup->skin.'/admin/servers/view',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function add()
	{
		if($this->input->post('valid'))
		{
			unset($_POST['valid']);

			if( ! isset($_POST['status']))
			{
				$_POST['status'] = 0;
			}

			if(substr($_POST['url'], -1) != '/')
			{
				$_POST['url'] .= '/';
			}

			$id = $this->server_db->add_server($_POST);

			$this->session->set_flashdata('msg', sprintf(lang('New Server Installed, %s'), anchor('admin/server/install/'.$id, lang('FTP Install this server?'))));

			redirect('admin/server/view');
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Add New Server')));
		$this->load->view($this->startup->skin.'/admin/servers/add');
		$this->load->view($this->startup->skin.'/footer');
	}

	public function edit($id)
	{
		if($this->input->post('valid'))
		{
			unset($_POST['valid']);

			if( ! isset($_POST['status']))
			{
				$_POST['status'] = 0;
			}

			if(substr($_POST['url'], -1) != '/')
			{
				$_POST['url'] .= '/';
			}

			$this->server_db->edit_server($id, $_POST);

			$this->session->set_flashdata('msg', lang('Server Edited'));
			redirect('admin/server/view');
		}

		$data['server'] = $this->server_db->get_server($id);
		$data['id'] = $id;

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit Server')));
		$this->load->view($this->startup->skin.'/admin/servers/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function install($id='')
	{
		if(empty($id))
		{
			redirect('admin/server/view');
		}

		$server = $this->server_db->get_server_by_id($id);
		$this->load->vars(array('sid' => $id, 'server' => $server, ));

		if( ! file_exists(realpath('./server_package')) OR ! file_exists(realpath('./server_package/index.php')))
		{
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Install Server Software')));
			$this->load->view($this->startup->skin.'/admin/servers/install_error');
			$this->load->view($this->startup->skin.'/footer');
			return;
		}

		$this->load->library('ftp');

		if($this->input->post('step-1'))
		{
			$config['hostname'] = str_replace('http://', '', $this->input->post('ftp_url'));
			$config['username'] = $this->input->post('ftp_user');
			$config['password'] = $this->input->post('ftp_pass');
			$config['port']	 = $this->input->post('ftp_port');
			$config['passive']  = TRUE;
			$config['debug']	= FALSE;

			$path = $this->input->post('ftp_path');
			if(substr($this->input->post('ftp_path'), -1, 1) != '/')
			{
				$path .= '/';
			}
			else
			{

			}

			$i = 0;
			while($i == 0)
			{
				$this->ftp->connect($config);
				if($this->ftp->error){$i++;break;}

				$this->ftp->mkdir($path.'test/', DIR_WRITE_MODE);
				if($this->ftp->error){$i++;break;}

				$this->ftp->upload('server_package/index.php', $path.'test/index.php', 'ascii', 0777);
				if($this->ftp->error){$i++;break;}

				$this->ftp->chmod($path.'test/index.php', 0777);
				if($this->ftp->error){$i++;break;}

				$this->ftp->delete_file($path.'test/index.php');
				if($this->ftp->error){$i++;break;}

				$i++;
			}

			if($this->ftp->error)
			{
				$error = $this->ftp->get_error();
				$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Install Server Software')));
				$this->load->view($this->startup->skin.'/admin/servers/install', array('error' => $error));
				$this->load->view($this->startup->skin.'/footer');
				$this->ftp->close();
				return;
			}

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Install Server Software')));
			$this->load->view($this->startup->skin.'/admin/servers/install-step1');
			$this->load->view($this->startup->skin.'/footer');
			return;
		}

		if($this->input->post('step-2'))
		{
			// copy some files first...
			file_put_contents(
				'server_package/system/application/config/config.php',
				str_replace(
					$this->config->config['base_url'],
					$server->url,
					file_get_contents('system/application/config/config.php')
				)
			);

			include('system/application/config/database.php');

			if($db['default']['hostname'] == "localhost")
			{
				$ip = gethostbyname(parse_url($this->config->config['base_url'], PHP_URL_HOST));

				$db['default']['hostname'] = $ip;
				file_put_contents(
					'server_package/system/application/config/database.php',
					'<'.'?php'."\n".'$active_group = "default";'."\n".'$active_record = TRUE;'."\n".'$db = '.var_export($db, true).';'."\n".'?'.'>'
				);
			}
			else
			{
				file_put_contents(
					'server_package/system/application/config/database.php',
					file_get_contents('system/application/config/database.php')
				);
			}

			// Connect to FTP
			$config['hostname'] = str_replace('http://', '', $this->input->post('ftp_url'));
			$config['username'] = $this->input->post('ftp_user');
			$config['password'] = $this->input->post('ftp_pass');
			$config['port']	 = $this->input->post('ftp_port');
			$config['passive']  = TRUE;
			$config['debug']	= FALSE;

			$path = $this->input->post('ftp_path');
			if(substr($this->input->post('ftp_path'), -1, 1) != '/')
			{
				$path .= '/';
			}
			$this->ftp->connect($config);

			// Upload the files
			$this->ftp->mirror('./server_package/', $path);
			if($this->ftp->error){die('MIRROR ERROR: '. $this->ftp->get_error());}

			// Correct folder permissions
			$this->ftp->chmod($path.'system/cache/', 0777);
			$this->ftp->chmod($path.'system/logs/', 0777);
			$this->ftp->chmod($path.'filestore/', 0777);
			$this->ftp->chmod($path.'temp/', 0777);
			$this->ftp->chmod($path.'thumbstore/', 0777);

			// Close the connection
			$this->ftp->close();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Install Server Software')));
			$this->load->view($this->startup->skin.'/admin/servers/install-step2');
			$this->load->view($this->startup->skin.'/footer');
			return;
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Install Server Software')));
		$this->load->view($this->startup->skin.'/admin/servers/install', array('error' => false));
		$this->load->view($this->startup->skin.'/footer');
	}

	public function turn_on($id)
	{
		$this->session->set_flashdata('msg', lang('Server turned On'));
		$this->server_db->edit_server($id, array('status' => '1'));
		redirect('admin/server/view');
	}

	public function turn_off($id)
	{
		$this->session->set_flashdata('msg', lang('Server turned Off'));
		$this->server_db->edit_server($id, array('status' => '0'));
		redirect('admin/server/view');
	}

	public function delete($id)
	{
		$this->session->set_flashdata('msg', lang('Server removed'));
		$this->server_db->delete_server($id);
		redirect('admin/server/view');
	}
}

/* End of file admin/server.php */
/* Location: ./application/controllers/admin/server.php */

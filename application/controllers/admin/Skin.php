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
 * XtraUpload Skin Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Skin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('skin/skin_db');
	}

	public function index()
	{
		redirect('admin/skin/view');
	}

	public function view()
	{
		$this->load->helper('string');
		$data['flash_message'] = '';

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['skins'] = $this->skin_db->get_all_skins();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Skins')));
		$this->load->view($this->startup->skin.'/admin/skins/view',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function install_new()
	{
		if ($handle = opendir(APPPATH.'views'))
		{
			$i = 0;
			while (false !== ($file = readdir($handle)))
			{
				if (($file != "." && $file != ".." && $file != ".svn" && $file != "_protected") && is_dir(APPPATH.'views/'.$file))
				{
					$skin = $this->db->get_where('skin', array('name' => $file));
					if($skin->num_rows() == '0')
					{
						$this->skin_db->install_skin($file);
						$i++;
					}
				}
			}
			closedir($handle);
		}
		$this->session->set_flashdata('msg', sprintf(lang('%s New Skin(s) were installed.'), $i));
		redirect('admin/skin/view');
	}

	public function delete($file='')
	{
		if($file != '' and $file != 'default')
		{
			$this->skin_db->delete_skin($file);
			$this->session->set_flashdata('msg', sprintf(lang('The Skin "%s" has been Uninstalled.'), '<strong>'.ucwords(str_replace('_',' ',$file)).'</strong>'));
		}
		redirect('admin/skin/view');
	}


	public function set_active($name)
	{
		$skin_name = md5($this->config->config['encryption_key'].'skin_name');

		$this->session->set_flashdata('msg', sprintf(lang('Skin "%s" set as active.'), ucwords(str_replace('_', ' ', $name))));
		$this->skin_db->set_active_skin($name);

		// Save the config object to cache for increased performance
		file_put_contents(CACHEPATH . $skin_name , $name);

		// Send updates to all servers
		$this->load->library('Remote_server_xml_rpc');
		$this->remote_server_xml_rpc->update_cache();

		redirect('admin/skin/view');
	}

	private function _new_skins_to_install()
	{
		if ($handle = opendir(APPPATH.'views/'))
		{
			$i = 0;
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != ".." && is_dir($file))
				{
					$skin = $this->db->get_where('skin', array('name' => $file));
					if($skin->num_rows() == '0')
					{
						return true;
					}
				}

			}
			closedir($handle);
		}
		return false;
	}
}

/* End of file admin/skin.php */
/* Location: ./application/controllers/admin/skin.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @author      momo-i
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @copyright   Copyright (c) 2011-, www.momo-i.org
 * @license     http://www.opensource.org/licenses/Apache-2.0
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

/**
 * XtraUpload Extend Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        https://gitorious.org/xtraupload-v3
 */
class Extend extends CI_Controller {

	// {{{ property

	/**
	 * Installed packages
	 *
	 * @var array
	 */
	private $installed='';

	/**
	 * Not installed packages
	 *
	 * @var array
	 */
	private $not_installed='';

	// }}}

	/**
	 * Constructor
	 *
	 * @see Admin_access
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->helper('string');
		$this->load->helper('text');
	}

	/**
	 * index
	 *
	 * Redirect extend view
	 *
	 * @return void
	 */
	public function index()
	{
		redirect('admin/extend/view');
	}

	/**
	 * view
	 *
	 * Install, uninstall plugins.
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
	public function view()
	{
		$this->_get_installed_plugins();
		$this->_get_not_installed_plugins();

		$data['installed']=array();
		$data['not_installed']=array();

		foreach($this->installed as $name)
		{
			$data['installed'][$name] = simplexml_load_file(APPPATH."extend/".$name."/".$name.".xml");
		}

		foreach($this->not_installed as $name)
		{
			$data['not_installed'][$name] = simplexml_load_file(APPPATH."extend/".$name."/".$name.".xml");
		}

		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Plugin Manager')));
		$this->load->view($this->startup->skin.'/admin/extend/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * install
	 *
	 * Install plugin
	 *
	 * @param  string $name Plugin name
	 * @author Matthew Glinski
	 * @return void
	 */
	public function install($name)
	{
		log_message('debug', "Install Plugin $name.");
		$name = str_replace(array('../', '..'), '', $name);
		$num_rows = $this->db->get_where('extend', array('file_name' => $name))->num_rows();
		if(file_exists(APPPATH."extend/".$name.'/'.$name.'.php') && file_exists(APPPATH."extend/".$name."/".$name.".xml") && $num_rows == 0)
		{
			log_message('debug', "Parsing Plugin.");
			$xml = simplexml_load_file(APPPATH."extend/".$name."/".$name.".xml");
			$data = array(
				'data' => json_encode($xml),
				'file_name' => $name,
				'date' => time(),
				'active' => '1',
				'uid' => $this->session->userdata('id'),
			);

			$this->db->insert('extend', $data);

			$this->load->extention($name);
			$this->$name->install();

			$this->session->set_flashdata('msg', sprintf(lang('Plugin "%s" Installed'), ucwords(str_replace('_', ' ', $name))));
		}
		$this->_update_cache();
		redirect('admin/extend/view');
	}

	/**
	 * remove
	 *
	 * Uninstall Extends.
	 *
	 * @param  string $name Plugin name
	 * @author Matthew Glinski
	 * @return void
	 */
	public function remove($name)
	{	 $name = str_replace(array('../', '..'), '', $name);
		$this->load->extention($name);
		$this->$name->uninstall();

		$this->db->delete('extend', array('file_name' => $name));
		$this->session->set_flashdata('msg', sprintf(lang('Plugin "%s" Uninstalled'), ucwords(str_replace('_', ' ', $name))));
		$this->_update_cache();
		redirect('admin/extend/view');
	}

	/**
	 * turn_on
	 *
	 * Activate Plugin
	 *
	 * @param  string $name Plugin name
	 * @author Matthew Glinski
	 * @return void
	 */
	public function turn_on($name)
	{
		$this->db->where('file_name', $name)->update('extend', array('active' => 1));
		$this->session->set_flashdata('msg', sprintf(lang('Plugin "%s" Activated'), ucwords(str_replace('_', ' ', $name))));
		$this->_update_cache();
		redirect('admin/extend/view');
	}

	/**
	 * turn_off
	 *
	 * Deactivate Plugins
	 *
	 * @param  string $name Plugin name
	 * @author Matthew Glinski
	 * @return void
	 */
	public function turn_off($name)
	{
		$this->db->where('file_name', $name)->update('extend', array('active' => 0));
		$this->session->set_flashdata('msg', sprintf(lang('Plugin "%s" Deactivated'), ucwords(str_replace('_', ' ', $name))));
		$this->_update_cache();
		redirect('admin/extend/view');
	}

	/**
	 * _update_cache
	 *
	 * Update Cache file
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
	private function _update_cache()
	{
		$extend_file_name = md5($this->config->config['encryption_key'].'extend');

		$data = array();
		$db1 = $this->db->get_where('extend', array('active' => 1));
		foreach($db1->result() as $plugin)
		{
			$data[] = $plugin->file_name;
		}

		if(empty($data))
		{
			@unlink(CACHEPATH . $extend_file_name);
		}
		else
		{
			$final = base64_encode(json_encode($data));
			file_put_contents(CACHEPATH . $extend_file_name, $final);
		}

		$this->load->library('remote_server_xml_rpc');
		$this->remote_server_xml_rpc->update_cache();
	}

	/**
	 * _get_installed_plugins
	 *
	 * Get Installed plugins
	 *
	 * @author Matthew Glinski
	 * @return array Installed Plugins
	 */
	private function _get_installed_plugins()
	{
		if(is_array($this->installed))
		{
			return $this->installed;
		}

		$this->installed = array();
		$db1 = $this->db->get('extend');
		foreach($db1->result() as $plugin)
		{
			$this->installed[] = $plugin->file_name;
		}
		return $this->installed;
	}

	/**
	 * _get_not_installed_plugins
	 *
	 * Get not installed plugins
	 *
	 * @author Matthew Glinski
	 * @return array Not installed plugins
	 */
	private function _get_not_installed_plugins()
	{
		if(is_array($this->not_installed))
		{
			return $this->not_installed;
		}

		$this->not_installed = array();
		$dir = APPPATH."extend/";

		// Open a known directory, and proceed to read its contents
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false)
				{
					if(is_dir($dir . $file) && $file != '.' && $file != '..' && $file != '.svn' && ! in_array($file, $this->installed))
					{
						$this->not_installed[] = $file;
					}
				}
				closedir($dh);
			}
		}
		return $this->not_installed;
	}
}

/* End of file admin/extend.php */
/* Location: ./application/controllers/admin/extend.php */

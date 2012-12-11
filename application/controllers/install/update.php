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
 * XtraUpload Setup Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Update extends CI_Controller {

	public $updated = FALSE;
	private $_verions = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->dbforge();
		$this->_versions = $this->_populate_version();
	}

	public function index()
	{
		foreach ($this->_versions as $version)
		{
			if($version['version'] > XU_DB_VERSION)
			{
				$this->updated = TRUE;
				$data['flash_message']  = "<li><strong>Version: {$version['version']}</strong><br>";
				$data['flash_message'] .= "{$version['description']}<br></li>\n";
			}
		}
		if($this->updated === FALSE)
		{
			$data['flash_message'] = "<span class\"info\">".lang('No Updates Needed')."</span>";
		}
		$this->load->view('install/header');
		$this->load->view('install/update', $data);
		$this->load->view('install/footer');
	}

	public function do_update()
	{
		if($this->updated === FALSE)
		{
			$this->_set_db_version();
			$data['flash_message'] = "<span class\"info\">".lang('No Updates Needed')."</span>\n";
			$data['flash_message'] = '<a href="'.site_url().'">'.lang('Back Home').'</a>';
		}
		else
		{
			$ret = $this->_update(XU_DB_VERSION, XU_VERSION);
			if($ret === TRUE)
			{
				redirect('install/update/done');
			}
			else
			{
				$data['error_message'] = sprintf(lang('Cannot Update %s from %s'), XU_VERSION_READ, XU_DB_VERSION_READ);
			}
		}
		$this->load->view('install/header');
		$this->load->view('install/update', $data);
		$this->load->view('install/footer');
	}

	public function done()
	{
		$this->load->view('install/header');
		$this->load->view('install/update_done');
		$this->load->view('install/footer');
	}

	private function _update($prev, $new)
	{
		$prev = str_replace(array(',', '.'), '', $prev);
		$new = str_replace(array(',', '.'), '', $new);
		if($prev < '3000000')
		{
			$data['error_message'] = lang('Database version too old, please re-install fresh.');
			$this->load->view('install/header');
			$this->load->view('install/update', $data);
			$this->load->view('install/footer');
			return FALSE;
		}

		$methods = get_class_methods('Update');
		if( ! is_array($this->_versions))
		{
			$this->_versions = $this->_populate_version();
		}

		foreach ($this->_versions as $version)
		{
			if($version['version'] > $prev)
			{
				$update_function = "_update_".$version['version'];
				if(in_array($update_function, $methods))
				{
					$success = call_user_func(array('Update',$update_function));
					return $success;
				}
			}
		}
		return FALSE;
	}

	private function _populate_version()
	{
		$version = array();
		$update_string = '- Language system changed Zend from CI.<br>';

		$version[] = array('version' => '3000002', 'description' => $update_string);

		$update_string = '- New Ads System.<br>';
		$version[] = array('version' => '3001000', 'description' => $update_string);

		return $version;
	}

	private function _update_3000002()
	{
		$query = $this->db->get_where('config', array('name' => 'locale'));
		if($query->num_rows() > 0)
		{
			$this->updated = FALSE;
			$this->_set_db_version();
			return FALSE;
		}

		$data = array('id' => NULL, 'name' => 'site_locale', 'value' => $this->input->post('locale'), 'description1' => 'Site Locale', 'description2' => '', 'group' => 0, 'type' => 'select', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('locale' => array('type'=>'VARCHAR', 'default'=>'en_US', 'constraint'=>6));
		$this->dbforge->add_column('users', $data);
		$this->_set_db_version();

		return TRUE;
	}

	private function _update_3000100()
	{
		$this->_set_db_version();
		return TRUE;
	}

	private function _update_3000200()
	{
		$this->_set_db_version();
		return TRUE;
	}

	private function _update_3000300()
	{
		$this->_set_db_version();
		return TRUE;
	}

	private function _set_db_version()
	{
		$data = array('value' => XU_VERSION);
		$this->db->where('name', '_db_version');
		$this->db->update('config', $data);
	}

}

/* End of file install/update.php */
/* Location: ./application/controllers/install/update.php */

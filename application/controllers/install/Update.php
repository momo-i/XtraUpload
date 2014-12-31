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
 * XtraUpload Update Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Update extends CI_Controller {

	/**
	 * Update flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $updated = FALSE;

	/**
	 * Versions
	 *
	 * @access	private
	 * @var		array
	 */
	private $_verions = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->dbforge();
		$this->_versions = $this->_populate_version();
		$this->_xu_db_version = str_replace(array('.', ','), '', XU_DB_VERSION);
	}

	/**
	 * Update::index()
	 *
	 * Show update page
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		foreach ($this->_versions as $version)
		{
			if($version['version'] > $this->_xu_db_version)
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

	/**
	 * Update::do_update()
	 *
	 * Update XtraUpload
	 *
	 * @access	public
	 * @return	void
	 */
	public function do_update()
	{
		$this->_check_versions();
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

	/**
	 * Update::done()
	 *
	 * Show update done page
	 *
	 * @access	public
	 * @return	void
	 */
	public function done()
	{
		$this->load->view('install/header');
		$this->load->view('install/update_done');
		$this->load->view('install/footer');
	}

	/**
	 * Update::_update()
	 *
	 * Call update version function
	 *
	 * @access	private
	 * @param	string	$prev	Previous version
	 * @param	string	$new	New version
	 * @return	bool
	 */
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

	/**
	 * Update::_populate_version()
	 *
	 * Populate versions
	 *
	 * @access	private
	 * @return	array
	 */
	private function _populate_version()
	{
		$version = array();
		$update_string = '- Language system changed Zend from CI.<br>';

		$version[] = array('version' => '3000002', 'description' => $update_string);

		$update_string = '- New Ads System.<br>';
		$version[] = array('version' => '3000010', 'description' => $update_string);

		// 3.0.0 Beta4
		$update_string = '- Captcha Image Size editable in admin page';
		$version[] = array('version' => '3000040', 'description' => $update_string);

		// 3.0.0 Beta5
		$update_string = '- Player Side editable in admin page';
		$version[] = array('version' => '3000050', 'description' => $update_string);

		// 3.0.0 RC1
		$update_string = '- Rewrite code for CodeIgniter 3.x';
		$version[] = array('version' => '3000100', 'description' => $update_string);

		// 3.0.0 RC2
		$update_string = '- Fix installation<br>';
		$update_string .= '- Update show/hide about message on admin page.';
		$version[] = array('version' => '3000200', 'description' => $update_string);

		// 3.0.0 RC3
		$update_string = '- Update upload_failures table';
		$version[] = array('version' => '3000300', 'description' => $update_string);

		// 3.0.0 RC4
		$update_string = '- Fixed session database table';
		$version[] = array('version' => '3000400', 'description' => $update_string);

		// 3.0.0 STABLE
		$update_string = '- Stable release!!!!!';
		$version[] = array('version' => '3001000', 'description' => $update_string);

		return $version;
	}

	/**
	 * Update::_update_3000002()
	 *
	 * Update version 3.0.0 Alpha2
	 *
	 * @access	private
	 * @return	bool
	 */
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

	/**
	 * Update::_update_3000010()
	 *
	 * Update Beta1
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000010()
	{
		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000020()
	 *
	 * Update Beta2
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000020()
	{
		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000030()
	 *
	 * Update Beta3
	 *
	 * @access	public
	 * @return	bool
	 */
	private function _update_3000030()
	{
		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000040()
	 *
	 * Update Beta4
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000040()
	{
		$query = $this->db->get_where('config', array('name' => 'captcha_width'));
		if($query->num_rows() > 0)
		{
			$this->updated = FALSE;
			$this->_set_db_version();
			return FALSE;
		}

		$data = array('id' => NULL,'name' => 'captcha_width','value' => '70','description1' => 'Captcha image width:','description2' => '','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);
		$data = array('id' => NULL,'name' => 'captcha_height','value' => '20','description1' => 'Captcha image height:','description2' => '','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);

		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000050()
	 *
	 * Update Beta5
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000050()
	{
		$query = $this->db->get_where('config', array('name' => 'player_width'));
		if($query->num_rows() > 0)
		{
			$this->updated = FALSE;
			$this->_set_db_version();
			return FALSE;
		}

		$data = array('id' => NULL,'name' => 'player_width','value' => '470','description1' => 'Player width:','description2' => '','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);
		$data = array('id' => NULL,'name' => 'player_height','value' => '320','description1' => 'Player height:','description2' => '','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);

		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000100()
	 *
	 * Update RC1
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000100()
	{
		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000200()
	 *
	 * Update RC2
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000200()
	{
		$query = $this->db->get_where('config', array('name' => 'show_about'));
		if($query->num_rows() > 0)
		{
			$this->updated = FALSE;
			$this->_set_db_version();
			return false;
		}

		$data = array('id' =>  NULL, 'name' => 'show_about', 'value' => '1', 'description1' => 'Show About Messages', 'description2' => 'Yes|-|No<br /><br />Show a list of the About messages?', 'group' => 0, 'type' => 'yesno', 'invincible' => 1);
		$this->db->insert('config', $data);

		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000300()
	 *
	 * Update RC3
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000300()
	{
		$fields = array(
			'reason' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
			)
		);
		$this->dbforge->modify_column('upload_failures', $fields);

		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3000400()
	 *
	 * Update RC4
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _update_3000400()
	{
		$this->dbforge->drop_table('sessions');
		$fields = array(
			'session_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 40,
				'default' => 0,
				'null' => false,
			),
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 45,
				'default' => 0,
				'null' => false
			),
			'user_agent' => array(
				'type' => 'VARCHAR',
				'null' => false,
				'constraint' => 120
			),
			'last_activity' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'default' => '0',
				'constraint' => 10,
				'null' => FALSE,
			),
			'user_data' => array(
				'type' => 'TEXT',
				'null' => false
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key(array('session_id', 'ip_address', 'user_agent'), true);
		$this->dbforge->add_key('last_activity');
		$this->dbforge->create_table('sessions');

		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_update_3001000
	 *
	 * Stable release
	 *
	 * @access	private
	 * @return	true
	 */
	private function _update_3001000()
	{
		$this->_set_db_version();
		return TRUE;
	}

	/**
	 * Update::_set_db_version()
	 *
	 * Set version on database
	 *
	 * @access	private
	 * @return	void
	 */
	private function _set_db_version()
	{
		$data = array('value' => XU_VERSION);
		$this->db->where('name', '_db_version');
		$this->db->update('config', $data);
	}

	/**
	 * Update::_check_versions()
	 *
	 * Check update version
	 *
	 * @access	private
	 * @return	void
	 */
	private function _check_versions()
	{
		foreach ($this->_versions as $version)
		{
			if($version['version'] > $this->_xu_db_version)
			{
				$this->updated = TRUE;
			}
		}
	}

}

/* End of file install/Update.php */
/* Location: ./application/controllers/install/Update.php */

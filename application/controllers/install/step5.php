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
 * XtraUpload Step5 Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Step5 extends CI_Controller {

	private $_db_version;
	private $_version = null;

	public function __construct()
	{
		parent::__construct();
		include(ROOTPATH.'/xu_ver.php');
		$this->_db_version = $version;
		$this->load->model('xu');
		$this->_version = $this->xu->get_version();
	}

	public function index()
	{
		$this->load->database();
		$this->load->dbforge();

		if($this->input->post('username') AND empty($this->_version))
		{
			$this->_load_database();
		}
		$this->load->view('install/header');
		$this->load->view('install/step5');
		$this->load->view('install/footer');
	}

	private function _load_database()
	{
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'md5' => array(
				'type' => 'VARCHAR',
				'constraint' => '32'
			),
			'name' => array(
				'type' =>'TEXT'
			),
			'type' => array(
				'type' => 'VARCHAR',
				'default' => 'file',
				'constraint' => 30
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'default' => '0.0.0.0',
				'constraint' => 15
			),
			'user' => array(
				'type' => 'VARCHAR',
				'default' => 0,
				'constraint' => 150
			),
			'time' => array(
				'type' => 'VARCHAR',
				'default' => 0,
				'constraint' => 22
			),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('md5', false);
		$this->dbforge->add_key('type', false);
		$this->dbforge->create_table('bans');

		// Captcha Table
		$fields = array(
			'captcha_id' => array(
				'type' => 'BIGINT',
				'constraint' => 13,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'captcha_time' => array(
				'type' => 'TEXT'
			),
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 15,
				'default' => '0'
			),
			'word' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('captcha_id', true);
		$this->dbforge->add_key('word', false);
		$this->dbforge->create_table('captcha');

		// Config Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 64
			),
			'value' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'description1' => array(
				'type' => 'TEXT'
			),
			'description2' => array(
				'type' => 'TEXT'
			),
			'group' => array(
				'type' => 'VARCHAR',
				'constraint' => 32,
				'default' => '0'
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 12
			),
			'invincible' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('config');

		// Main Settings => 0
		// INSERT initnal data
		$data = array('id' => NULL,'name' => 'sitename','value' => 'XtraUpload v3','description1' => 'Site Name:','description2' => '(Site Name)','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' => NULL,'name' => 'slogan','value' => 'Preview','description1' => 'Your Site Slogan','description2' => '','group' => 0,'type' => 'text','invincible' => 1);
		$this->db->insert('config', $data);

		$data = array( 'id' =>  NULL, 'name' => 'site_email', 'value' => 'admin@localhost', 'description1' => 'Site EMail', 'description2' => 'Email address used to send emails', 'group' => 0, 'type' => 'text', 'invincible' => 1);
		$this->db->insert('config', $data);
		$data = array( 'id' =>  NULL, 'name' => 'title_separator', 'value' => '-', 'description1' => 'Title Separator', 'description2' => '', 'group' => 0, 'type' => 'text', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => 'no_php_images', 'value' => '0', 'description1' => 'Use Static Image Links', 'description2' => 'Yes|-|No<br /><br />Use actual filesystem URLs to serve image thumbnails and direct images. Will save memory and server cycles on large sites.', 'group' => 0, 'type' => 'yesno', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => 'allow_version_check', 'value' => '1', 'description1' => 'Allow Version Check', 'description2' => 'Yes|-|No<br /><br />Allow XtraUpload to call home to check for new versions and security updates?', 'group' => 0, 'type' => 'yesno', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => 'home_info_msg', 'value' => '', 'description1' => 'Home Page Message', 'description2' => 'Message to display to all your users on the home page. Like an announcement', 'group' => 0, 'type' => 'box', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => 'show_preview', 'value' => '1', 'description1' => 'Show File Preview', 'description2' => 'Yes|-|No<br /><br />Show a preview of some file types on download(mp3, wmv, mov) and an embed code.', 'group' => 0, 'type' => 'yesno', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => 'show_recent_uploads', 'value' => '1', 'description1' => 'Show Recent Uploads', 'description2' => 'Yes|-|No<br /><br />Show a list of the 5 most recently uploaded files?', 'group' => 0, 'type' => 'yesno', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' =>  NULL, 'name' => '_db_version', 'value' => $this->_db_version, 'description1' => '', 'description2' => '', 'group' => 0, 'type' => 'text', 'invincible' => 1);
		$this->db->insert('config', $data);

		$data = array('id' => NULL, 'name' => 'locale', 'value' => $this->input->post('locale'), 'description1' => 'Site Locale', 'description2' => '', 'group' => 0, 'type' => 'select', 'invincible' => 1);
		$this->db->insert('config', $data);

		// counters Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'downloads' => array(
				'type' => 'VARCHAR',
				'constraint' => 8
			),
			'bandwidth' => array(
				'type' => 'VARCHAR',
				'constraint' => 8
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('counters');

		// dlinks Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'fid' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'time' => array(
				'type' => 'VARCHAR',
				'constraint' => 22
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'stream' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('dlinks');

		// dlsessions Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'fid' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'user' => array(
				'type' => 'INT',
				'constraint' => 11
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('fid');
		$this->dbforge->add_key('ip');
		$this->dbforge->create_table('dlsessions');

		// Downloads Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'file_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'user' => array(
				'type' => 'VARCHAR',
				'constraint' => 20
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'size' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'sent' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'time' => array(
				'type' => 'VARCHAR',
				'constraint' => 25
			)
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('file_id');
		$this->dbforge->add_key('user');
		$this->dbforge->add_key('ip');
		$this->dbforge->create_table('downloads');

		// Extend Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'file_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100
			),
			'data' => array(
				'type' => 'TEXT'
			),
			'date' => array(
				'type' => 'VARCHAR',
				'constraint' => 22
			),
			'uid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE
			),
			'active' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			)
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('file_name');
		$this->dbforge->create_table('extend');

		// Files Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'filename' => array(
				'type' => 'TEXT'
			),
			'size' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'md5' => array(
				'type' => 'VARCHAR',
				'constraint' => 32
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => 4
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'prefix' => array(
				'type' => 'VARCHAR',
				'constraint' => 2
			),
			'is_image' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'thumb' => array(
				'type' => 'TEXT'
			),
			'server' => array(
				'type' => 'VARCHAR',
				'constraint' => 250
			),
			'mirror' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('prefix');
		$this->dbforge->add_key('md5');
		$this->dbforge->add_key('server');
		$this->dbforge->create_table('files');

		// Folder Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'f_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'name' => array(
				'type' => 'TEXT'
			),
			'descr' => array(
				'type' => 'TEXT'
			),
			'pass' => array(
				'type' => 'VARCHAR',
				'constraint' => 150,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('f_id', false);
		$this->dbforge->create_table('folder');

		// Galleries Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'g_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'name' => array(
				'type' => 'TEXT'
			),
			'descr' => array(
				'type' => 'TEXT'
			),
			'pass' => array(
				'type' => 'VARCHAR',
				'constraint' => 150,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('g_id', false);
		$this->dbforge->create_table('gallery');

		// g_items Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 8
			),
			'thumb' => array(
				'type' => 'TEXT'
			),
			'direct' => array(
				'type' => 'TEXT'
			),
			'fid' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'view' => array(
				'type' => 'TEXT'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('gid', false);
		$this->dbforge->create_table('g_items');

		// groups Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'descr' => array(
				'type' => 'TEXT'
			),
			'price' => array(
				'type' => 'VARCHAR',
				'constraint' => 8
			),
			'repeat_billing' => array(
				'type' => 'VARCHAR',
				'constraint' => 5
			),
			'speed_limit' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'upload_size_limit' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'wait_time' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'files_types' => array(
				'type' => 'TEXT'
			),
			'file_types_allow_deny' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'download_captcha' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'auto_download' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'upload_num_limit' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'storage_limit' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'can_search' => array(
				'type' => 'TINYINT',
				'default' => '0',
				'constraint' => 1
			),
			'can_flash_upload' => array(
				'type' => 'TINYINT',
				'default' => '1',
				'constraint' => 1
			),
			'can_url_upload' => array(
				'type' => 'TINYINT',
				'default' => '1',
				'constraint' => 1
			),
			'file_expire' => array(
				'type' => 'INT',
				'default' => '30',
				'constraint' => 11
			),
			'admin' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => '0'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('groups');

		// Insert Free Group
		$data = array(
			'id' => '1',
			'name' => 'Free',
			'status' => 1,
			'price' => 0,
			'descr' => 'Free Users',
			'admin' => 0,
			'speed_limit' => '250',
			'upload_size_limit' => '100',
			'wait_time' => '10',
			'files_types' => 'exe|php|sh|bat|cgi|pl',
			'file_types_allow_deny' => 0,
			'download_captcha' => 1,
			'auto_download' => 0,
			'can_search' => '0',
			'can_flash_upload' => '1',
			'can_url_upload' => '1',
			'file_expire' => '30',
			'upload_num_limit' => 10
		);
		$this->db->insert('groups', $data);

		// Insert Admin Group
		$data = array(
			'id' => '2',
			'name' => 'Admins',
			'status' => 0,
			'price' => 0,
			'descr' => 'Administrators',
			'admin' => 1,
			'speed_limit' => '2500',
			'upload_size_limit' => '500',
			'wait_time' => '1',
			'files_types' => '',
			'file_types_allow_deny' => 0,
			'download_captcha' => 0,
			'auto_download' => 1,
			'can_search' => 1,
			'can_flash_upload' => 1,
			'can_url_upload' => 1,
			'file_expire' => '0',
			'upload_num_limit' => 500
		);
		$this->db->insert('groups', $data);
		// Insert Admin Group
		$data = array(
			'id' => '3',
			'name' => 'Premium',
			'status' => 1,
			'price' => 9.99,
			'descr' => 'Premium Users',
			'admin' => 0,
			'repeat_billing' => 'm',
			'speed_limit' => '500',
			'upload_size_limit' => '250',
			'wait_time' => '1',
			'files_types' => '',
			'file_types_allow_deny' => 0,
			'download_captcha' => 0,
			'auto_download' => 0,
			'can_search' => 0,
			'can_flash_upload' => 1,
			'can_url_upload' => 1,
			'file_expire' => '90',
			'upload_num_limit' => 50
		);
		$this->db->insert('groups', $data);

		// f_items Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'folder_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 8
			),
			'file_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'view' => array(
				'type' => 'TEXT'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('folder_id', false);
		$this->dbforge->create_table('f_items');

		// progress Table
		$fields = array(
		'id' => array(
			 'type' => 'INT',
			 'constraint' => 11,
			 'unsigned' => TRUE,
			 'auto_increment' => TRUE
		),
		'progress' => array(
			'type' => 'BIGINT',
			'constraint' => 1
		),
		'curr_time' => array(
			 'type' => 'TEXT'
		),
		'total' => array(
			'type' => 'VARCHAR',
			'constraint' => 50
		),
		'start_time' => array(
			 'type' => 'TEXT'
		),

		'fid' => array(
			 'type' => 'VARCHAR',
			'constraint' => 16
		)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('fid', false);
		$this->dbforge->create_table('progress');

		// Refrence Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'unsigned' => true,
				'auto_increment' => true
			),
			'file_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '16'
			),
			'descr' => array(
				'type' => 'TEXT'
			),
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => '32'
			),
			'o_filename' => array(
				'type' => 'TEXT'
			),
			'secid' => array(
				'type' => 'VARCHAR',
				'constraint' => '32'
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => '32'
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'constraint' => '15'
			),
			'link_name' => array(
				'type' => 'TEXT'
			),
			'feature' => array(
				'type' => 'TINYINT',
				'constraint' => '32'
			),
			'user' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '10'
			),
			'time' => array(
				'type' => 'VARCHAR',
				'constraint' => '20'
			),
			'pass' => array(
				'type' => 'VARCHAR',
				'constraint' => '32'
			),
			'rate_num' => array(
				'type' => 'INT',
				'constraint' => '32'
			),
			'rate_total' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'is_image' => array(
				'type' => 'TINYINT',
				'constraint' => '32'
			),
			'link_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '16'
			),
			'downloads' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'featured' => array(
				'type' => 'TINYINT',
				'constraint' => '32'
			),
			'remote' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0'
			),
			'last_download' => array(
				'type' => 'VARCHAR',
				'constraint' => '22'
			),
			'direct_bw' => array(
				'type' => 'VARCHAR',
				'constraint' => '50'
			),
			'direct' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0'
			),
			'tags' => array(
				'type' => 'VARCHAR',
				'constraint' => 200
			)
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('feature');
		$this->dbforge->add_key('file_id');
		$this->dbforge->create_table('refrence');

		// Servers Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 150
			),
			'url' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'num_files' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true
			),
			'free_space' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'used_space' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'total_space' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			),
			'status' => array(
				'type' => 'INT',
				'default' => '0',
				'constraint' => 4
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('url');
		$this->dbforge->create_table('servers');

		$data = array('id' => NULL, 'name' => 'main', 'url' => $this->input->post('url'), 'status' => 1);
		$this->db->insert('servers', $data);

		// Sessions Table
		$fields = array(
			'session_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 40
			),
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 16,
				'default' => 0,
				'null' => false
			),
			'active' => array(
				'type' => 'TINYINT',
				'unsigned' => TRUE,
				'default' => '0',
				'constraint' => 1
			),
			'user_agent' => array(
				'type' => 'VARCHAR',
				'null' => false,
				'constraint' => 50
			),
			'last_activity' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'default' => '0',
				'constraint' => 10
			),
			'user_data' => array(
				'type' => 'TEXT',
				'null' => false
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('session_id', true);
		$this->dbforge->create_table('sessions');

		// Admin Menu Shortcuts Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => false
			),
			'link' => array(
				'type' => 'TEXT',
				'null' => false
			),
			'order' => array(
				'type' => 'VARCHAR',
				'null' => false,
				'constraint' => 4
			),
			'status' => array(
				'type' => 'TINYINT',
				'null' => false,
				'default' => '0',
				'constraint' => 1
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('admin_menu_shortcuts');

		// Login Refrence Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			),
			'date' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'null' => false,
				'constraint' => 15
			),
			'user' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			),
			'user_name' => array(
				'type' => 'VARCHAR',
				'null' => false,
				'default' => '0',
				'constraint' => 200
			),
			'valid' => array(
				'type' => 'TINYINT',
				'null' => false,
				'default' => '0',
				'constraint' => 1
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('login_refrence');

		// Skins Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			),
			'name' => array(
				'type' => 'TEXT',
			),
			'active' => array(
				'type' => 'TINYINT',
				'unsigned' => TRUE,
				'default' => '0',
				'constraint' => 1
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('skin');

		$data = array('id' => NULL, 'name' => 'default', 'active' => 1);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'vector_lover', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'urban_artist', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'tech_junkie', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'citrus_island', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'style_vantage_orange', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'style_vantage_blue', 'active' => 0);
		$this->db->insert('skin', $data);

		$data = array('id' => NULL, 'name' => 'style_vantage_green', 'active' => 0);
		$this->db->insert('skin', $data);

		// Sessions Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'username' => array(
				'type' => 'VARCHAR',
				'constraint' => 16
			),
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => 32
			),
			'time' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'last_login' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'ip' => array(
				'type' => 'VARCHAR',
				'constraint' => 15
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'group' => array(
				'type' => 'TINYINT',
				'constraint' => 4
			),
			'gateway' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'default' => '0'
			),
			'public' => array(
				'type' => 'TINYINT',
				'default' => 0,
				'constraint' => 1
			),
			'locale' => array(
				'type' => 'VARCHAR',
				'default' => 'en_US',
				'constraint' => 6
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('email');
		$this->dbforge->add_key('group');
		$this->dbforge->create_table('users');

		// Insert Admin User
		$data = array(
			'id' => NULL,
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('enc').$this->input->post('password')),
			'time' => time(),
			'last_login' => 0,
			'status' => 1,
			'public' => 0,
			'gateway' => '0',
			'ip' => $this->input->ip_address(),
			'email' => $this->input->post('email'),
			'group' => 2,
			'locale' => $this->input->post('locale')
		);
		$this->db->insert('users', $data);

		// Gateways Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 150
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'config' => array(
				'type' => 'TEXT'
			),
			'settings' => array(
				'type' => 'TEXT'
			),
			'slug' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => '0'
			),
			'default' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'display_name' => array(
				'type' => 'TEXT',
				'constraint' => 1,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('gateways');

		$data = array('id' => 1, 'name' => 'paypal', 'status' => 1, 'config' => 'a:2:{s:5:"email";s:4:"text";s:8:"currency";s:4:"text";}', 'settings' => 'a:2:{s:5:"email";s:20:"PAYPAL_EMAIL_ADDRESS";s:8:"currency";s:3:"USD";}', 'slug' => 'paypal', 'default' => 1, 'display_name' => 'PayPal'
		 );
		$this->db->insert('gateways', $data);

		$data = array('id' => 2, 'name' => 'authorize', 'status' => 1, 'config' => 'a:2:{s:5:"login";s:4:"text";s:6:"secret";s:4:"text";}', 'settings' => 'a:2:{s:5:"login";s:8:"LOGIN_ID";s:6:"secret";s:11:"SECRET_CODE";}', 'slug' => 'auth', 'default' => 0, 'display_name' => 'Authorize.net'
		 );
		$this->db->insert('gateways', $data);

		$data = array('id' => 3, 'name' => '2co', 'status' => 1, 'config' => 'a:2:{s:9:"vendor_id";s:4:"text";s:8:"currency";s:4:"text";}', 'settings' => 'a:2:{s:9:"vendor_id";s:9:"VENDOR_ID";s:8:"currency";s:3:"USD";}', 'slug' => 'twoco', 'default' => 0, 'display_name' => '2CheckOut'
		 );
		$this->db->insert('gateways', $data);

		// Transactions Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'time' => array(
				'type' => 'VARCHAR',
				'constraint' => 20
			),

			'config' => array(
				'type' => 'TEXT'
			),
			'settings' => array(
				'type' => 'TEXT'
			),
			'gateway' => array(
				'type' => 'VARCHAR',
				'constraint' => 20
			),
			'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1
			),
			'ammount' => array(
				'type' => 'VARCHAR',
				'constraint' => 10
			),
			'user' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('transactions');

		// upload_failures Table
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'secid' => array(
				'type' => 'VARCHAR',
				'constraint' => 32
			),
			'date' => array(
				'type' => 'INT',
				'constraint' => 16,
			),
			'reason' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('date', false);
		$this->dbforge->add_key('secid', false);
		$this->dbforge->create_table('upload_failures');
	}

}

/* End of file install/step5.php */
/* Location: ./application/controllers/install/step5.php */

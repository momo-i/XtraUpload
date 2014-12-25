<?php
/* vim: set ts=4 sw=4 sts=0: */

/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package		XtraUpload
 * @author		momo-i
 * @copyright	Copyright (c) 2014, www.momo-i.org
 * @license		http://www.opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @link		http://www.momo-i.org
 * @since		Version 2.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * XtraUpload Example Extend Class
 *
 * @package		XtraUpload
 * @subpackage	Controller
 * @category	Model
 * @author		momo-i
 * @link		https://github.com/momo-i/xtraupload-v3
 */
class Example extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$id = $this->xu_api->menus->add_admin_menu(lang('Example'));
		$this->xu_api->menus->add_admin_menu_link($id, 'admin/example/manage', lang('Example Manage'), base_url().'img/other/plugin_16.png');
		$this->xu_api->menus->add_admin_menu_link($id, 'example', lang('Example'), base_url().'img/other/plugin_16.png');
	}

	public function install()
	{
		$this->load->dbforge();
		// Example field
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'msg' => array(
				'type' => 'VARCHAR',
				'constraint' => '20'
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('example');
	}

	public function uninstall()
	{
		$this->load->dbforge();
		$this->dbforge->drop_table('example');
		return;
	}

	public function assign_libraries()
	{
		return true;
	}
}

/* End of file extends/example/example.php */
/* Location: ./application/extends/example/example.php */

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->xu_api->menus->add_admin_menu_link('admin/example/manage', lang('Example'), base_url().'img/other/plugin_16.png');
		$this->xu_api->menus->add_admin_menu_link('example', lang('Example'), base_url().'img/other/plugin_16.png');
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
}

/* End of file main.php */
/* Location: ./application/extends/example/main.php */

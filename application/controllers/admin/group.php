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
 * XtraUpload Group Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Group extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	public function index()
	{
		redirect('admin/group/view');
	}

	public function view()
	{
		$this->load->helper('string');

		$data['flash_message'] = '';

		$data['groups'] = $this->db->get('groups');

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<p><span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span></p>';
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Groups')));
		$this->load->view($this->startup->skin.'/admin/groups/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function edit($id)
	{
		if($this->input->post('name'))
		{
			// Save changes
			$this->db->where('id', $id)->update('groups', $_POST);

			// Encrypt the cache filename for security
			$group_file_name = md5($this->config->config['encryption_key'].'group_'.$id);

			// Get group object from DB
			$group_config = $this->db->get_where('groups', array('id' => $id))->row();

			// Save the group object to cache for increased performance
			file_put_contents(CACHEPATH . $group_file_name, base64_encode(json_encode($group_config)));

			// Send updates to all servers
			$this->load->library('Remote_server_xml_rpc');
			$this->remote_server_xml_rpc->update_cache();

			$this->session->set_flashdata('msg', lang('Group Edited!'));
			redirect('/admin/group/view');
		}

		$data['group'] = $this->db->get_where('groups', array('id' => $id))->row();
		$data['real_name'] = $this->_get_real_names();
		$data['real_descr'] = $this->_get_real_descriptions();
		$data['real_type'] = $this->_get_real_types();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit User Group')));
		$this->load->view($this->startup->skin.'/admin/groups/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function add()
	{
		if($this->input->post('name'))
		{
			// Insert new group
			$this->db->insert('groups', $_POST);
			$group = $this->db->insert_id();

			// Encrypt the cache filename for security
			$group_file_name = md5($this->config->config['encryption_key'].'group_'.$group);

			// Get group object from DB
			$group_config = $this->db->get_where('groups', array('id' => $group))->row();

			// Save the group object to cache for increased performance
			file_put_contents(CACHEPATH . $group_file_name, base64_encode(json_encode($group_config)));

			// Send updates to all servers
			$this->load->library('Remote_server_xml_rpc');
			$this->remote_server_xml_rpc->update_cache();

			// Send back to the main page
			$this->session->set_flashdata('msg', lang('Group Edited!'));
			redirect('/admin/group/view');
		}

		$data['group'] = $this->db->get_where('groups', array('id' => '1'))->row();
		$data['real_name'] = $this->_get_real_names();
		$data['real_descr'] = $this->_get_real_descriptions();
		$data['real_type'] = $this->_get_real_types();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Add New User Group')));
		$this->load->view($this->startup->skin.'/admin/groups/add', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function turn_on($id)
	{
		if($id > 2)
		{
			$this->db->where('id', $id);
			$this->db->update('groups', array('status' => 1));
			$this->session->set_flashdata('msg', lang('Group is now Public'));
		}
		redirect('admin/group/view');
	}

	public function turn_off($id)
	{
		if($id > 2)
		{
			$this->db->where('id', $id);
			$this->db->update('groups', array('status' => 0));
			$this->session->set_flashdata('msg', lang('Group is now Private'));
		}
		redirect('admin/group/view');
	}

	public function delete($id)
	{
		if($id > 2)
		{
			$this->db->delete('groups', array('id' =>$id));
			$this->session->set_flashdata('msg', lang('Group has been deleted'));
		}
		redirect('admin/group/view');
	}

	private function _get_real_names()
	{
		$group = array(
			'name' => lang('Group Name'),
			'price' => lang('Registration Price'),
			'descr' => lang('Group Description'),
			'admin' => lang('Can Access Admin Panel?'),
			'can_search' => lang('Allow Search Access'),
			'speed_limit' => lang('Download speed limit'),
			'upload_size_limit' => lang('File Size Limit'),
			'wait_time' => lang('Download Wait Time'),
			'files_types' => lang('File Types'),
			'file_types_allow_deny' => lang('File Types are Allowed/Restricted'),
			'download_captcha' => lang('Captcha for downloads'),
			'auto_download' => lang('Direct Links?'),
			'upload_num_limit' => lang('Mass Upload File Count'),
			'storage_limit' => lang('Account Storage Limit'),
			'repeat_billing' => lang('Billing Interval'),
			'can_flash_upload' => lang('Can Upload Using Flash'),
			'can_url_upload' => lang('Can Upload Using URLs'),
			'file_expire' => lang('File Expire Time')
		);
		return $group;
	}

	private function _get_real_descriptions()
	{
		$group = array(
			'name' => lang('The name for your Group'),
			'price' => lang('The price someone has to pay to register or extend a user account with this group'),
			'descr' => lang('The group description'),
			'admin' => lang('Are users in this group allowed to access the admin panel?'),
			'can_search' => lang('Can users in this group use the sitewide file search(only for public files)?'),
			'speed_limit' => lang('The download speed limit, in KBps'),
			'upload_size_limit' => lang('The max filesize for uploads, in MB'),
			'wait_time' => lang('The time a user has to wait before they can download a file.'),
			'files_types' => lang('A Pipe("|") seperated list of file types to allow or deny on upload. <br>Leave blank to remove restrictions. The setting below controls this setting.'),
			'file_types_allow_deny' => lang('Allow or deny the above file list.'),
			'download_captcha' => lang('Force a captcha on users in this group before they can download a file'),
			'auto_download' => lang('If captcha is off, the wait time is 1 or less and this is set to yes, file links will auto download.'),
			'upload_num_limit' => lang('The number of files a user can upload at once without refreshing the page.'),
			'storage_limit' => lang('The total size in megabytes that any user in thes group can store at one time.'),
			'repeat_billing' => lang('The period of time where an account is billed for service'),
			'can_search' => lang('Can the user search through public files?'),
			'can_flash_upload' => lang('Can the user upload files using flash?'),
			'can_url_upload' => lang('Can the user upload files from other sites(URL Upload)?'),
			'file_expire' => lang('The ammount of time in days that a file uploaded by this group is kept on the server.'),
		);
		return $group;
	}

	private function _get_real_types()
	{
		$group = array(
			'name' => '',
			'price' => '',
			'descr' => 'area',
			'admin' => 'yesno',
			'can_search' => 'yesno',
			'speed_limit' => '',
			'upload_size_limit' => '',
			'wait_time' => '',
			'files_types' => '',
			'file_types_allow_deny' => 'allowdeny',
			'auto_download' => 'yesno',
			'upload_num_limit' => '',
			'storage_limit' => '',
			'file_expire' => '',
			'can_search' => 'yesno',
			'can_flash_upload' => 'yesno',
			'can_url_upload' => 'yesno',
			'repeat_billing' => array(
				'0' => lang('None'),
				'd' => lang('Daily'),
				'w' => lang('Weekly'),
				'm' => lang('Monthly'),
				'y' => lang('Yearly'),
				'dy' => lang('Bi-Yearly'),
			),
			'download_captcha' => array(
				'0' => lang('Never'),
				'1' => lang('First Download'),
				'2' => lang('Always')
			)
		);
		return $group;
	}
}

/* End of file admin/group.php */
/* Location: ./application/controllers/admin/group.php */

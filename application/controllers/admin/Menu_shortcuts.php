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
 * XtraUpload Menu_shortchuts Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Menu_shortcuts extends CI_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @see		Admin_menu_shortcuts_db
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/menu_shortcuts/admin_menu_shortcuts_db');
	}

	/**
	 * Menu_shortcuts::index()
	 *
	 * Redirect Menu_shortcuts::view()
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		redirect('admin/menu_shortcuts/view');
	}

	/**
	 * Menu_shortcuts::view()
	 *
	 * Show menu shortcut page
	 *
	 * @access	public
	 * @return	void
	 */
	public function view()
	{
		$this->load->library('pagination');
		$this->load->helper('admin/sort');
		$this->load->helper('string');
		$this->load->helper('date');

		$per_page = 20;
		$data['flash_message'] = '';
		$data['per_page'] = $per_page;

		$config['base_url'] = site_url('admin/menu_shortcuts/view');
		$config['total_rows'] = $this->admin_menu_shortcuts_db->get_num_shortcuts();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$config['attributes'] = array('class' => 'prevnext');

		$this->pagination->initialize($config);

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['shortcuts'] = $this->admin_menu_shortcuts_db->get_shortcuts($per_page, $this->uri->segment(4));
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Files')));
		$this->load->view($this->startup->skin.'/admin/menu_shortcuts/view',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Menu_shortcuts::edit()
	 *
	 * Show shortcut edit page, and edit shortcut
	 *
	 * @access	public
	 * @param	int		$id	Shortcut ID
	 * @return	void
	 */
	public function edit($id)
	{
		if($this->input->post('status'))
		{
			$this->admin_menu_shortcuts_db->edit_shortcut($id, $this->input->post());

			$this->session->set_flashdata('msg', lang('Shortcut Edited'));
			redirect('admin/menu_shortcuts/view');
			return false;
		}

		$data['id'] = $id;
		$data['shortcut'] = $this->admin_menu_shortcuts_db->get_shortcut($id)->row();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit Shortcut')));
		$this->load->view($this->startup->skin.'/admin/menu_shortcuts/edit',$data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Menu_shortcuts::add()
	 *
	 * Show shortcut add page, and add shortcut
	 *
	 * @access	public
	 * @param	string	$link	Shortcut link
	 * @param	strin	$title	Shortcut title
	 * @return	void
	 */
	public function add($link= '', $title='')
	{
		if($this->input->post('link'))
		{
			$this->admin_menu_shortcuts_db->add_shortcut($this->input->post());

			$this->session->set_flashdata('msg', lang('New Shortcut Added'));
			redirect('admin/menu_shortcuts/view');
			return false;
		}

		$data['link'] = '';
		if($link != '')
		{
			$data['link'] = base64_decode($link);
		}

		$data['title'] = '';
		if($title != '')
		{
			$data['title'] = base64_decode($title);
		}

		$data['order'] = $this->admin_menu_shortcuts_db->get_num_shortcuts();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Add Shortcut')));
		$this->load->view($this->startup->skin.'/admin/menu_shortcuts/add', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Menu_shortcuts::delete()
	 *
	 * Delete shortcut
	 *
	 * @access	public
	 * @param	int		$id	Shortcut ID
	 * @return	void
	 */
	public function delete($id)
	{
		$this->admin_menu_shortcuts_db->delete_shortcut($id);
		$this->session->set_flashdata('msg', lang('Shortcut Deleted'));
		redirect('admin/menu_shortcuts/view');
	}

	/**
	 * Menu_shortcuts::turn_off()
	 *
	 * Turn off shortcut
	 *
	 * @access	public
	 * @param	int		$id	Shortcut ID
	 * @return	void
	 */
	public function turn_off($id)
	{
		$this->admin_menu_shortcuts_db->edit_shortcut($id, array('status' => 0));
		$this->session->set_flashdata('msg', lang('Shortcut Hidden'));
		redirect('admin/menu_shortcuts/view');
	}

	/**
	 * Menu_shortcuts::turn_on()
	 *
	 * Turn on shortcut
	 *
	 * @access	public
	 * @param	int		$id	Shortcut ID
	 * @return	void
	 */
	public function turn_on($id)
	{
		$this->admin_menu_shortcuts_db->edit_shortcut($id, array('status' => 1));
		$this->session->set_flashdata('msg', lang('Shortcut Shown'));
		redirect('admin/menu_shortcuts/view');
	}
}

/* End of file admin/Menu_shortchuts.php */
/* Location: ./application/controllers/admin/Menu_shortchuts.php */

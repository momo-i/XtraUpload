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
 * XtraUpload Gateways Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Gateways extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	public function index()
	{
		redirect('admin/gateways/view');
	}

	public function manage()
	{
		redirect('admin/gateways/view');
	}

	public function home()
	{
		redirect('admin/gateways/view');
	}

	public function view()
	{
		$this->load->library('pagination');
		$this->load->helper('string');

		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info">'.$this->session->flashdata('msg').'</span>';
		}

		$data['gates'] = $this->db->get('gateways');

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Payment Gateways')));
		$this->load->view($this->startup->skin.'/admin/gateways/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function edit($id)
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info">'.$this->session->flashdata('msg').'</span>';
		}

		$data['gate'] = $this->db->get_where('gateways', array('id' => intval($id)))->row();
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit Payment Gateway')));
		$this->load->view($this->startup->skin.'/admin/gateways/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	// ------------------------------------------------------------------------

	/**
	 * update()
	 *
	 * Process a new config object save request
	 *
	 * @access  public
	 * @return  none
	 */
	public function update($id)
	{
		// If the user has posted new values
		if($this->input->post('valid'))
		{
			$gate = $this->db->get_where('gateways', array('id' => $id))->row();
			$settings = @unserialize($gate->settings);
			foreach($settings as $key => $type)
			{
				$data[$key] = $this->input->post($key);
			}

			$this->db->where('id', $id);
			$this->db->update('gateways', array('settings' => serialize($data)));
			$this->session->set_flashdata('msg', lang('Payment Gateway Edited!'));
			redirect('admin/gateways/view');
		}
		else
		{
			// Redirect back to main page
			 redirect('admin/config');
		}
	}

	public function set_default($id)
	{
		// If the user has posted new values
		$this->db->where('default', '1');
		$this->db->update('gateways', array('default' => '0'));

		$this->db->where('id', $id);
		$this->db->update('gateways', array('default' => '1'));

		$this->session->set_flashdata('msg', lang('New Payment Gateway Set as Default!'));
		redirect('admin/gateways/view');
	}

	public function turn_on($id)
	{
		$this->db->where('id', $id);
		$this->db->update('gateways', array('status' => '1'));

		$this->session->set_flashdata('msg', lang('Payment Gateway turned on!'));
		redirect('admin/gateways/view');
	}

	public function turn_off($id)
	{
		$this->db->where('id', $id);
		$this->db->update('gateways', array('status' => '0'));

		$this->session->set_flashdata('msg', lang('Payment Gateway turned off!'));
		redirect('admin/gateways/view');
	}
}

/* End of file admin/gateways.php */
/* Location: ./application/controllers/admin/gateways.php */

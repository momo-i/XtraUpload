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
 * XtraUpload Transactions Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Transactions extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('transactions/transactions_db');
	}

	public function index()
	{
		redirect('admin/user/view');
	}

	public function manage()
	{
		redirect('admin/user/view');
	}

	public function home()
	{
		redirect('admin/user/view');
	}

	public function view()
	{
		$this->load->library('pagination');
		$this->load->helper('string');
		$this->load->helper('date');

		$per_page = 50;

		$data['flash_message'] = '';
		$data['per_page'] = $per_page;

		$config['base_url'] = site_url('admin/user/view');
		$config['total_rows'] = $this->transactions_db->get_num_transactions();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);

		$data['transactions'] = $this->transactions_db->get_transactions($per_page, $this->uri->segment(4));

		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<p><span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span></p>';
		}

		$data['pagination'] = $this->pagination->create_links();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Transactions')));
		$this->load->view($this->startup->skin.'/admin/transactions/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function edit($id)
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info">'.$this->session->flashdata('msg').'</span>';
		}

		$data['transaction'] = $this->db->get_where('transactions', array('id' => intval($id)))->row();
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit Transaction')));
		$this->load->view($this->startup->skin.'/admin/transactions/edit', $data);
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
			$gate = $this->db->get_where('transactions', array('id' => $id))->row();
			$settings = json_decode($gate->settings);
			foreach($settings as $key => $type)
			{
				$data[$key] = $this->input->post($key);
			}

			$this->transactions_db->edit($id, $data);
			$this->session->set_flashdata('msg', lang('Transaction Edited!'));
			redirect('admin/transactions/view');
		}
		else
		{
			// Redirect back to main page
			 redirect('admin/config');
		}
	}

	public function delete($id)
	{
		// If the user has posted new values
		$this->transactions_db->delete($id);
		$this->session->set_flashdata('msg', lang('Transaction Deleted!'));
		redirect('admin/transactions/view');
	}
}

/* End of file admin/transactions.php */
/* Location: ./application/controllers/admin/transactions.php */

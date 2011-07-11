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
 * XtraUpload Email Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        https://gitorious.org/xtraupload-v3
 */
class Email extends CI_Controller {

	/**
	 * Constructor
	 *
	 * @see    Admin_access
	 * @author Matthew Glinski
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
	}

	/**
	 * index
	 *
	 * Redirect email view
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
	public function index()
	{
		redirect('admin/email/view');
	}

	/**
	 * view
	 *
	 * mass email view
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
	public function view()
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Bulk Email')));
		$this->load->view($this->startup->skin.'/admin/email', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * send
	 *
	 * Send mail
	 *
	 * @author Matthew Glinski
	 * @return void
	 */
	public function send()
	{
		if($this->input->post('group') == '0')
		{
			$users = $this->db->get('users');
		}
		else
		{
			$users = $this->db->get_where('users', array('group' => $this->input->post('group')));
		}

		// Load the email library
		$this->load->library('email');

		foreach($users->result() as $user)
		{
			if(trim($user->email) == '')
			{
				continue;
			}
			$this->email->clear();

			// Set email options
			$this->email->from($this->startup->site_config->site_email, $this->startup->site_config->sitename.' Support');
			$this->email->to($user->email);
			$this->email->subject($this->input->post('subject'));
			$this->email->message($this->input->post('msg'));

			// Send the email
			$this->email->send();
		}

		$this->session->set_flashdata('msg', lang('Email sent!'));
		redirect('admin/email/view');
	}
}

/* End of file admin/email.php */
/* Location: ./application/controllers/admin/email.php */

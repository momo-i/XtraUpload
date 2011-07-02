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
 * XtraUpload Ads Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Ads extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('ads/ads_db');
	}

	public function index()
	{
		redirect('admin/ads/view');
	}

	public function view()
	{
		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['ads'] = $this->ads_db->getAds();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Advert Manager')));
		$this->load->view($this->startup->skin.'/admin/ads/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function add()
	{
		if($this->input->post('name'))
		{
			$this->ads_db->insert($_POST);
			$this->session->set_flashdata('msg', lang('Advert Added!'));
			redirect();
		}

		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['ads'] = $this->ads_db->get_ads();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Advert Manager')));
		$this->load->view($this->startup->skin.'/admin/ads/add', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function edit($id)
	{
		if(intval($id) == 0))
		{
			redirect('admin/ads/view');
		}

		if($this->input->post('name'))
		{
			$this->ads_db->insert($_POST);
			$this->session->set_flashdata('msg', lang('Advert Edited!'));
			redirect();
		}

		$data['flash_message'] = '';
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		$data['ads'] = $this->ads_db->get_ads();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Advert Manager')));
		$this->load->view($this->startup->skin.'/admin/ads/edit', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function turn_on($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->turn_on(intval($id));
			$this->session->set_flashdata('msg', lang('Advert Turned On!'));
			redirect();
		}
	}

	public function turn_off($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->turn_off(intval($id));
			$this->session->set_flashdata('msg', lang('Advert Turned Off!'));
			redirect();
		}
	}
	public function delete($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->delete(intval($id));
			$this->session->set_flashdata('msg', lang('Advert Deleted!'));
			redirect();
		}
	}
}

/* End of file admin/ads.php */
/* Location: ./application/controllers/admin/ads.php */

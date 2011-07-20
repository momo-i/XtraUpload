<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package		XtraUpload
 * @author		Matthew Glinski
 * @author      momo-i
 * @copyright	Copyright (c) 2006, XtraFile.com
 * @copyright	Copyright (c) 2011-, www.momo-i.org
 * @license		http://www.opensource.org/licenses/Apache-2.0
 * @link		http://xtrafile.com
 * @since		Version 2.0
 * @filesource
 */

/**
 * XtraUpload Ads Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        https://gitorious.org/xtraupload-v3
 * @todo        Create Models, and Views
 */
class Ads extends CI_Controller {

	/**
	 * Constructor
	 *
	 * Load admin_access and ads_db model
	 *
	 * @see    Admin_access
	 * @see    Ads_db
	 * @author Matthew Glinski
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_access');
		$this->load->model('ads/ads_db');
	}

	/**
	 * index
	 *
	 * Redirect ads view
	 *
	 * @see    view()
	 * @author Matthew Glinski
	 * @return void
	 */
	public function index()
	{
		redirect('admin/ads/view');
	}

	/**
	 * view
	 *
	 * Advert Setting View
	 *
	 * @see    Ads_db::get_ads()
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

		$data['ads'] = $this->ads_db->get_ads();

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Advert Manager')));
		$this->load->view($this->startup->skin.'/admin/ads/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * add
	 *
	 * Advert Add
	 *
	 * @see    Ads_db::get_ads()
	 * @see    Ads_db::insert()
	 * @author Matthew Glinski
	 * @return void
	 */
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

	/**
	 * edit
	 *
	 * Advert Edit
	 *
	 * @see    Ads_db::get_ads()
	 * @see    Ads_db::insert()
	 * @author Matthew Glinski
	 * @param  integer $id Advert ID
	 * @return void
	 */
	public function edit($id)
	{
		if(intval($id) == 0))
		{
			redirect('admin/ads/view');
		}

		if($this->input->post('name'))
		{
			$this->ads_db->edit_ads($id, $_POST);
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

	/**
	 * turn_on
	 *
	 * Activate Advert
	 *
	 * @see    Ads_db::turn_on()
	 * @author Matthew Glinski
	 * @param  integer $id Advert ID
	 * @return void
	 */
	public function turn_on($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->edit_ads(intval($id), array('status' => '1'));
			$this->session->set_flashdata('msg', lang('Advert Turned On!'));
			redirect();
		}
	}

	/**
	 * turn_off
	 *
	 * Deactivate Advert
	 *
	 * @see    Ads_db::turn_off
	 * @author Matthew Glinski
	 * @param  integer $id Advert ID
	 * @return void
	 */
	public function turn_off($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->edit_ads(intval($id), array('status' => '1');
			$this->session->set_flashdata('msg', lang('Advert Turned Off!'));
			redirect();
		}
	}

	/**
	 * delete
	 *
	 * Delete Advert
	 *
	 * @see    Ads_db::delete()
	 * @author Matthew Glinski
	 * @param  integer $id Advert ID
	 * @return void
	 */
	public function delete($id)
	{
		if(intval($id) != 0))
		{
			$this->ads_db->delete_ads(intval($id));
			$this->session->set_flashdata('msg', lang('Advert Deleted!'));
			redirect();
		}
	}
}

/* End of file admin/ads.php */
/* Location: ./application/controllers/admin/ads.php */

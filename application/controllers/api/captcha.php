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
 * XtraUpload Captcha Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Captcha extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		return;
	}

	public function get($old)
	{
		if($this->session->flashdata('captcha'))
		{
			$old_captcha = str_replace('.jpg', '', $this->session->flashdata('captcha'));
			$this->db->delete('captcha', array('captcha_time' => $old_captcha));
			//echo $old_captcha."<br />";
			@unlink(ROOTPATH.'/temp/'.$old_captcha.'.jpg');
		}

		$captcha = $this->_get_captcha();
		echo $captcha;
	}

	private function _get_captcha()
	{
		$this->load->helper('captcha');

		$vals = array(
			'img_path'  => ROOTPATH.'/temp/',
			'word'	  => $this->users->gen_pass(3, false),
			'img_width' => 70,
			'img_height' => 20,
			'img_url'   => base_url().'temp/',
			'font_path' => BASEPATH.'fonts/MyriadWebPro-Bold.ttf'
		);

		$cap = create_captcha($vals);

		if(!$cap)
			echo '||';

		$data = array(
			'captcha_time'  => $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'		  => $cap['word']
		);

		$this->db->insert('captcha', $data);
		$this->session->set_flashdata('captcha', floatval($cap['time']).'.jpg');

		return $cap['image'];
	}

}

/* End of file api/captcha.php */
/* Location: ./application/controllers/api/captcha.php */

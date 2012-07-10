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
 * XtraUpload User Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->helper('form');
	}

	public function index()
	{
		if($this->session->userdata('id'))
		{
			redirect('user/login');
		}
		else
		{
			redirect('user/manage');
		}
	}

	public function forgot()
	{
		redirect('forgot_password');
	}

	public function compare()
	{
		$data['group1'] = $this->db->get_where('groups', array('id' => 1))->row();
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('User Package Comparison')));
		$this->load->view($this->startup->skin.'/user/compare', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function register()
	{
		if($this->db->get_where('groups', array('id !=' => 2, 'id !=' => 1, 'status' => '1'))->num_rows() == 0)
		{
			$data['error_message'] = '';
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Register')));
			$this->load->view($this->startup->skin.'/user/register/closed', $data);
			$this->load->view($this->startup->skin.'/footer');
			return;
		}

		// Blank Error Message
		$data['error_message'] = '';

		// If user failed CAPTCHA, delete it
		if($this->session->flashdata('captcha'))
		{
			unlink(ROOTPATH.'/temp/'.$this->session->flashdata('captcha'));
		}

		// Delete old captchas
		$expiration = time()-7200; // Two hour limit
		$this->db->delete('captcha', array("captcha_time <" => $expiration));

		$data['captcha'] = $this->_get_captcha();
		$data['groups'] = $this->db->get_where('groups', array('status' => '1'));
		$data['gates'] = $this->db->get_where('gateways', array('status' => '1'));

		$config = array(
			array(
				'field' => 'username',
				'label' => lang('Username'),
				'rules' => 'trim|required|strtolower|min_length[5]|max_length[12]|xss_clean|callback_username_check'
			),
			array(
				'field' => 'password',
				'label' => lang('Password'),
				'rules' => 'trim|required|min_length[4]|matches[passconf]'
			),
			array(
				'field' => 'passconf',
				'label' => lang('Password Confirmation'),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'email',
				'label' => lang('Email Address'),
				'rules' => 'trim|required|valid_email|max_length[255]|matches[emailConf]|callback_email_check'
			),
			array(
				'field' => 'emailConf',
				'label' => lang('Email Address Confirmation'),
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
		if($this->input->post('posted'))
		{
			$run = $this->form_validation->run();
			$query = $this->db->get_where('captcha', array('word' => $this->input->post('captcha'), 'ip_address' => $this->input->ip_address(),'captcha_time >' => $expiration,));
			$rows = $query->num_rows();

			if(!$rows)
			{
				$run = FALSE;
			}
			if (!$run)
			{
				$this->form_validation->set_error_delimiters('<li>', '</li>');
				$error = validation_errors();
				if(!$rows)
				{
					$error .= '<li>'.lang('The Captcha you submited was incorrect').'</li>';
				}
				$data['error_message'] = '<p><span class="alert"><strong>'.lang('Error:').'</strong><br><ul>'.$error.'</ul></span></p>'."\n";

				$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Register')));
				$this->load->view($this->startup->skin.'/user/register/begin', $data);
				$this->load->view($this->startup->skin.'/footer');
			}
			else
			{
				$this->_register_submit();
			}
		}
		else
		{
			$data['error_message'] = '';
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Register')));
			$this->load->view($this->startup->skin.'/user/register/begin', $data);
			$this->load->view($this->startup->skin.'/footer');
		}
	}

	public function login()
	{
		//$this->output->cache(60);
		$config = array(
			array(
				'field' => 'username',
				'label' => lang('Username'),
				'rules' => 'trim|required|min_length[4]|max_length[32]|xss_clean|strtolower'
			),
			array(
				'field' => 'password',
				'label' => lang('Password'),
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();
			if($this->input->post('submit'))
			{
				$this->load->vars(array('error_message' => '<span class="alert"><strong>'.lang('Error:').'</strong><ul>'.$error.'</ul></span>'));
			}
			else
			{
				$this->load->vars(array('error_message' => ''));
			}
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Login')));
			$this->load->view($this->startup->skin.'/user/login');
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->_login_submit();
		}
	}

	public function pay_cancel()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Payment Canceled')));
		$this->load->view($this->startup->skin.'/user/register/pay_cancel');
		$this->load->view($this->startup->skin.'/footer');
	}

	public function pay_complete()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Payment Processing')));
		$this->load->view($this->startup->skin.'/user/register/pay_complete');
		$this->load->view($this->startup->skin.'/footer');
	}

	public function pay_new($id='', $gate_id='')
	{
		if(intval($id) == 0 or intval($gate_id) == 0)
		{
			show_404();
		}

		$user = $this->db->get_where('users', array('id' => $id))->row();
		if(!$user or $user->status != 0)
		{
			show_404();
		}

		$group = $this->db->get_where('groups', array('id' => $user->group))->row();
		if(!$group)
		{
			show_404();
		}

		$gate = $this->db->get_where('gateways', array('id' => $gate_id))->row();
		if(!$gate)
		{
			show_404();
		}

		// get payment gateway settings
		$gate_conf = unserialize($gate->settings);

		// load payment libs
		include_once (APPPATH.'libraries/payment/PaymentGateway.php');

		// which payment system to use?
		if($gate->name == 'paypal')
		{
			// Include the paypal library
			include_once (APPPATH.'libraries/payment/Paypal.php');

			// Create an instance of the paypal library
			$my_paypal = new Paypal();

			// Specify your paypal email
			$my_paypal->add_field('business', $gate_conf['email']);

			// Specify the currency
			$my_paypal->add_field('currency_code', $gate_conf['currency']);

			// Specify the url where paypal will send the user on success/failure
			$my_paypal->add_field('return', site_url('user/pay_complete'));
			$my_paypal->add_field('cancel_return', site_url('user/pay_cancel'));

			// Specify the url where paypal will send the IPN
			$my_paypal->add_field('notify_url', site_url('payment/ipn/paypal'));

			// Specify the product information
			$my_paypal->add_field('item_name', $this->startup->site_config->sitename.' '.lang('Account Registration'));
			$my_paypal->add_field('amount', $group->price);
			$my_paypal->add_field('item_number',  rand(1,1000).'-'.$user->id);

			// Specify any custom value
			$my_paypal->add_field('custom', base64_encode(json_encode(array('user_id'=>$user->id, 'type'=>'reg'))));

			// Enable test mode if needed
			if(defined('XUDEBUG') and XUDEBUG == true)
			{
				$my_paypal->enable_test_mode();
			}

			// Let's start the train!
			$data['form'] = $my_paypal->submit_payment(lang('If you are not automatically redirected to payment website within 5 seconds,<br> click \'Make Payment\' below to begin the payment procedure.'));
		}
		else if($gate->name == 'authorize')
		{
			// Include the paypal library
			include_once (APPPATH.'libraries/payment/Authorize.php');

			// Create an instance of the authorize.net library
			$my_authorize = new Authorize();

			// Specify your authorize.net login and secret
			$my_authorize->set_user_info($gate_conf['login'], $gate_conf['secret']);

			// Specify the url where authorize.net will send the user on success/failure
			$my_authorize->add_field('x_Receipt_Link_URL', site_url('user/pay_complete'));

			// Specify the url where authorize.net will send the IPN
			$my_authorize->add_field('x_Relay_URL', site_url('payment/ipn/authorize'));

			// Specify the product information
			$my_authorize->add_field('x_Description', $this->startup->site_config->sitename.' '.lang('Account Registration'));
			$my_authorize->add_field('x_Amount', $group->price);
			$my_authorize->add_field('x_Invoice_num',  rand(1,1000).'-'.$user->id);
			$my_authorize->add_field('x_Cust_ID', base64_encode(json_encode(array('user_id' => $user->id, 'type' => 'reg'))));

			// Enable test mode if needed
			if(defined('XUDEBUG') and XUDEBUG == true)
			{
				$my_authorize->enable_test_mode();
			}

			// Let's start the train!
			$data['form'] = $my_authorize->submit_payment(lang('If you are not automatically redirected to payment website within 5 seconds,<br> click \'Make Payment\' below to begin the payment procedure.'));
		}
		else if($gate->name = '2co')
		{
			// Include the paypal library
			include_once (APPPATH.'libraries/payment/TwoCo.php');

			// Create an instance of the authorize.net library
			$my2_co = new TwoCo();

			// Specify your 2CheckOut vendor id
			$my2_co->add_field('sid', $gate_conf['vendor_id']);

			// Specify the order information
			$my2_co->add_field('cart_order_id', rand(1,1000).'-'.$user->id);
			$my2_co->add_field('total', $group->price);

			// Specify the url where authorize.net will send the IPN
			$my2_co->add_field('x_Receipt_Link_URL', site_url('payment/ipn/two_checkout'));
			$my2_co->add_field('tco_currency', $gate_conf['currency']);
			$my2_co->add_field('custom', base64_encode(json_encode(array('user_id' => $user->id, 'type'=>'reg'))));

			// Enable test mode if needed
			if(defined('XUDEBUG') and XUDEBUG == true)
			{
				$my2_co->enable_test_mode();
			}

			// Let's start the train!
			$data['form'] = $my2_co->submit_payment(lang('If you are not automatically redirected to payment website within 5 seconds,<br> click \'Make Payment\' below to begin the payment procedure.'));
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Make Payment')));
		$this->load->view($this->startup->skin.'/user/register/pay_new', array('ammount' => $group, 'user' => $id, 'form' => $data['form'] ));
		$this->load->view($this->startup->skin.'/footer');
	}

	public function manage()
	{
		if(!$this->session->userdata('id'))
		{
			redirect('/user/login');
		}

		$data['error_message'] = '';
		if($this->session->flashdata('error_message'))
		{
			$data['error_message'] = $this->session->flashdata('error_message');
		}

		$run = false;

		if ($run == FALSE and $this->input->post('username'))
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();
			$data['error_message'] = '<p><span class="alert"><strong>'.lang('Error:').'</strong><br>'.$error.'</span></p>';

			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$data['user'] = $query->row();
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Account')));
			$this->load->view($this->startup->skin.'/user/manage', $data);
			$this->load->view($this->startup->skin.'/footer');
		}
		else if(!$this->input->post('username'))
		{
			$data['error_message'] = '';

			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$data['user'] = $query->row();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Account')));
			$this->load->view($this->startup->skin.'/user/manage', $data);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->_user_update();
		}
	}

	public function files($user)
	{
		$query = $this->db->get_where('users', array('username' => $user));
		$data['user'] = $query->row();

		if($data['user']->public)
		{
			// Load the pagination library
			$this->load->library('pagination');

			// Setup some vars
			$data['flash_message'] = '';
			$per_page = 25;

			// Pagination config values
			$config['base_url'] = site_url('user/files/'.$user);
			$config['total_rows'] = $this->files_db->get_num_userfiles($data['user']->id, true);
			$config['per_page'] = $per_page;

			// setup the pagination library
			$this->pagination->initialize($config);

			// Get the files object
			$data['files'] = $this->files_db->get_files_by_user($data['user']->id, true, $per_page, $this->uri->segment(3), '', true);

			// Create the pagination HTML
			$data['pagination'] = $this->pagination->create_links();
		}

		// If there was a message generated previously, load it
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		// Load the static files
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Your Files')));
		$this->load->view($this->startup->skin.'/user/files', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function change_password()
	{
		if(!$this->session->userdata('id'))
		{
			redirect('/user/login');
		}
		$config = array(
			array(
				'field' => 'oldpassword',
				'label' => lang('Old Password'),
				'rules' => 'trim|required|min_length[4]|callback_password_check'
			),
			array(
				'field' => 'newpassword',
				'label' => lang('New Password'),
				'rules' => 'trim|required|min_length[4]|matches[newpassconf]'
			),
			array(
				'field' => 'newpassconf',
				'label' => lang('New Password Confirmation'),
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);

		$run = $this->form_validation->run();
		if ($run == FALSE and $this->input->post('username'))
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();
			$this->load->vars(
				array('error_message' => '<p><span class="alert"><strong>'.lang('Error:').'</strong><br>'.$error.'</span></p>')
			);

			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$user = $query->row();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Change Password')));
			$this->load->view($this->startup->skin.'/user/password', $user);
			$this->load->view($this->startup->skin.'/footer');
		}
		else if(!$this->input->post('username'))
		{
			$this->load->vars(
				array('error_message' => '')
			);
			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$user = $query->row();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Change Password')));
			$this->load->view($this->startup->skin.'/user/password', $user);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->_password_update();
		}
	}

	public function forgot_password()
	{
		$config = array(
			array(
				'field' => 'username',
				'label' => lang('Username'),
				'rules' => 'trim|required|min_length[4]|callback_username_check_forgot'
			),
			array(
				'field' => 'email',
				'label' => lang('Email Address'),
				'rules' => 'trim|required|valid_email|callback_email_check_forgot'
			)
		);
		$this->form_validation->set_rules($config);

		$run = $this->form_validation->run();
		$res = $this->_check_user();

		if(!$res)
		{
			$run = false;
		}

		if ($run == FALSE and $this->input->post('posted'))
		{
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$error = validation_errors();

			if(!$res)
			{
				$error .= lang('No User is registered with that username/email address combination.');
			}

			$this->load->vars(
				array('error_message' => '<p><span class="alert"><strong>'.lang('Error:').'</strong><br>'.$error.'</span></p>')
			);

			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$user = $query->row();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Forgot Password')));
			$this->load->view($this->startup->skin.'/user/forgot', $user);
			$this->load->view($this->startup->skin.'/footer');
		}
		else if(!$this->input->post('posted'))
		{
			$this->load->vars(
				array('error_message' => '')
			);

			$query = $this->db->get_where('users', array('id' => $this->session->userdata('id')));
			$user = $query->row();

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Forgot Password')));
			$this->load->view($this->startup->skin.'/user/forgot', $user);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->_password_forgot();
		}
	}

	public function logout()
	{
		$this->users->user_logout();
		redirect('home');
	}

	public function profile($uname)
	{
		$this->load->helper(array('string', 'text'));
		$data['user'] = $this->db->get_where('users', array('username' => $uname))->row();
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('User Profile:').' '.ucfirst($uname)));
		$this->load->view($this->startup->skin.'/user/profile/view', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	public function username_check($str)
	{
		$query = $this->db->get_where('users',array('username' => $str));
		$num = $query->num_rows();
		if($num == 1)
		{
			$this->form_validation->set_message('username_check', lang('The username you requested has already been taken'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function username_check_forgot($str)
	{
		$query = $this->db->get_where('users',array('username' => $str));
		$num = $query->num_rows();
		if($num == 1)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('username_check_forgot', lang('That username does not exist in our records'));
			return false;
		}
	}

	public function email_check($str)
	{
		$query = $this->db->get_where('users',array('email' => $str));
		$num = $query->num_rows();
		if($num == 1)
		{
			$this->form_validation->set_message('email_check', lang('The email address you supplied is already registered with us'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function email_check_forgot($str)
	{
		$query = $this->db->get_where('users',array('email' => $str));
		$num = $query->num_rows();
		if($num != 1)
		{
			$this->form_validation->set_message('email_check_forgot', lang('The email address you supplied is not registered with us.'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function password_check($str)
	{
		$data =  array(
			'username' => $this->session->userdata('username'),
			'password' => md5($this->config->config['encryption_key'].$str)
		);

		$query = $this->db->get_where('users', $data);
		$num = $query->num_rows();
		if($num != 1)
		{
			$this->form_validation->set_message('password_check', lang('The Old Password you supplied did not match our records'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function _login_submit()
	{
		if($this->users->process_login($this->input->post('username'), $this->input->post('password')))
		{
			if(stristr($_SERVER['HTTP_REFERER'], 'user/login'))
			{
				redirect('home');
			}
			else
			{
				redirect(substr($_SERVER['HTTP_REFERER'], strlen(site_url())));
			}
			//redirect(substr($_SERVER['HTTP_REFERER'],24));
		}
		else
		{
			$this->load->vars(array('error_message' => '<span class="alert">'.lang('Login Failed, Please Try Again').'</span>'));

			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Login')));
			$this->load->view($this->startup->skin.'/user/login');
			$this->load->view($this->startup->skin.'/footer');
			return false;
		}
	}

	private function _register_submit()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => md5($this->config->config['encryption_key'].$this->input->post('password')),
			'email' => $this->input->post('email'),
			'group' => $this->input->post('group'),
			'time' => time(),
			'last_login' => '',
			'ip' => $this->input->ip_address(),
		);

		$group = $this->db->select('price')->get_where('groups', array('id' => $this->input->post('group')))->row();

		$forward_pay = false;

		if($group->price > 0.00)
		{
			$data['status'] = 0;
			$data['gateway'] = $this->input->post('gate');
			$forward_pay = TRUE;
		}
		else
		{
			$data['status'] = 1;
			$forward_pay = FALSE;
		}

		$id = $this->users->new_user($data, $forward_pay);

		if($forward_pay == FALSE)
		{
			$this->users->process_login($this->input->post('username'), $this->input->post('password'));
			$this->load->view($this->startup->skin.'/header', array('header_title' =>lang('Register')));
			$this->load->view($this->startup->skin.'/user/register/complete');
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			redirect('user/pay_new/'.$id.'/'.$this->input->post('gate'));
			return false;
		}
	}

	private function _user_update()
	{
		if(isset($_FILES['avitar']['name']) and $_FILES['avitar']['name'] != '')
		{
			$this->load->model('imgup');
			$this->imgup->process_user_avitar($this->session->userdata('id'));
		}

		$data = array(
			// Nothing here yet!
		);

		$result = $this->users->user_update($data);

		if($result)
		{
			$this->session->set_flashdata('error_message', '<span class="info">'.lang('User Modification Completed!').'</span>');
			redirect('user/manage');
		}
		else
		{
			$this->session->set_flashdata('error_message', '<span class="alert">'.lang('User Modification Failed:').' '.$result.'</span>');
			redirect('user/manage');
		}
	}

	private function _check_user()
	{
		$query = $this->db->get_where('users',array('username' => $this->input->post('username'), 'email' => $this->input->post('email')));
		$num = $query->num_rows();
		if($num != 1)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function _password_update()
	{
		$data = array(
			'password' => md5($this->config->config['encryption_key'].$this->input->post('newpassword'))
		);

		$result = $this->users->user_update($data);

		if($result)
		{
			$this->load->vars(array('error_message' => '<span class="info">'.lang('User Modification Completed!').'</span>'));
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Change Password')));
			$this->load->view($this->startup->skin.'/user/password');
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			$this->load->vars(array('error_message' => '<span class="alert">'.lang('User Modification Failed:').' '.$result.'</span>'));
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Change Password')));
			$this->load->view($this->startup->skin.'/user/password');
			$this->load->view($this->startup->skin.'/footer');
		}
	}

	private function _password_forgot()
	{
		$this->load->library('email');

		$new_pass = $this->functions->gen_pass(8);
		$new_passMD5 = md5($this->config->config['encryption_key'].$new_pass);

		$username = $this->input->post('username');

		$result = $this->users->user_update_forgot($new_passMD5, $username);

		$this->email->from($this->startup->site_config->site_email, $this->startup->site_config->sitename.lang('Support'));
		$this->email->to($this->input->post('email'));

		$this->email->subject(lang('Password Reset Request'));
		$body  = sprintf(lang('Hello %s,'), $username)."\n\n";
		$body .= sprintf(lang('You have requested that your password for %s be reset.'), $this->startup->site_config->sitename)."\n";
		$body .= lang('Here is your new password:')."\n\n";
		$body .= '--------------------------'."\n";
		$body .= sprintf(lang('Username: %s'), $username)."\n";
		$body .= sprintf(lang('Password: %s'), $new_pass)."\n";
		$body .= '--------------------------'."\n\n";
		$body .= lang('Thank You,')."\n";
		$body .= sprintf(lang('%s Administration'), $this->startup->site_config->sitename)."\n\n";

		$this->email->message($body);
		$this->email->send();

		if($result)
		{
			$this->load->vars(array('error_message' => '<span class="info">'.lang('New Password Sent!').'</span>'));
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Forgot Password')));
			$this->load->view($this->startup->skin.'/user/forgot');
			$this->load->view($this->startup->skin.'/footer');;
		}
		else
		{
			$this->load->vars(array('error_message' => '<span class="alert">'.lang('New Password Sent!').' '.$result.'</span>'));
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Forgot Password')));
			$this->load->view($this->startup->skin.'/user/forgot');
			$this->load->view($this->startup->skin.'/footer');
		}
	}

	private function _get_captcha()
	{
		$this->load->helper('captcha');

		$vals = array(
			'img_path'  => ROOTPATH.'/temp/',
			'word'	  => $this->users->gen_pass(5, false),
			'img_width' => 120,
			'img_height' => 30,
			'img_url'   => base_url().'temp/',
			'font_path' => BASEPATH.'fonts/MyriadWebPro-Bold.ttf'
		);

		$cap = create_captcha($vals);

		$data = array(
			'captcha_time'  => $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'		  => $cap['word']
		);

		$this->db->insert('captcha', $data);
		$this->session->set_flashdata('captcha', $cap['time'].'.jpg');

		return $cap['image'];
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

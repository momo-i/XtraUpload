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
 * XtraUpload Ipn Controller
 *
 * @package	 XtraUpload
 * @subpackage  Controllers
 * @category	Controllers
 * @author	  Matthew Glinski
 * @author	  momo-i
 * @link		http://xtrafile.com/docs
 */
class Ipn extends CI_Controller {

	private $gateway = '';

	/**
	 * Ipn()
	 *
	 * The home page controller constructor
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH.'libraries/payment/PaymentGateway.php');
	}

	public function index()
	{
		return;
	}

	// ------------------------------------------------------------------------

	/**
	 * IPN->paypal()
	 *
	 * Validate PayPal payments
	 *
	 * @access  public
	 * @return  none
	 */
	public function paypal()
	{
		// Include the paypal library
		include_once (APPPATH.'libraries/payment/Paypal.php');
		$this->gateway = '1';

		// Create an instance of the paypal library
		$my_paypal = new Paypal();

		// Log the IPN results
		// $my_paypal->ipn_log = TRUE;

		// Enable test mode if needed
		if(defined('XUDEBUG') and XUDEBUG == true)
		{
			$my_paypal->enable_test_mode();
		}

		// Check validity and write down it
		if ($my_paypal->validate_ipn())
		{
			if ($my_paypal->ipn_data['payment_status'] == 'Completed')
			{
				$settings = json_decode(base64_decode($my_paypal->ipn_data['custom']));
				if($settings['type'] == 'reg')
				{
					$this->_new_user_payment($settings['user_id'], $my_paypal->ipn_data['amount']);
					redirect('/user/pay_complete');
				}
				redirect('/user/pay_cancel');
			}
			else
			{
				 $this->_log_error($my_paypal->ipn_data);
				 redirect('/user/pay_cancel');
			}
		}
		redirect('/user/pay_cancel');
	}

	public function authorize()
	{
		// make sure there are no timeouts...
		echo lang('Processing...'); flush();

		$gate = $this->db->get_where('gateways', array('name' => 'authorize'))->row();
		$gate_conf = json_decode($gate->settings);

		// Include the paypal library
		include_once (APPPATH.'libraries/payment/Authorize.php');
		$this->gateway = '2';

		// Create an instance of the authorize.net library
		$my_authorize = new Authorize();

		// Log the IPN results
		// $my_authorize->ipn_log = TRUE;

		// Specify your authorize login and secret
		$my_authorize->set_user_info($gate_conf['login'], $gate_conf['secret']);

		// Enable test mode if needed
		$my_authorize->enable_test_mode();

		// Check validity and write down it
		if ($my_authorize->validate_ipn())
		{
			$settings = json_decode(base64_decode($my_authorize->ipn_data['x_Cust_ID']));
			if($settings['type'] == 'reg')
			{
				$this->_new_user_payment($settings['user_id'], $my_authorize->ipn_data['x_Amount']);
				redirect('/user/pay_complete');
			}
			redirect('/user/pay_cancel');
		}
		else
		{
			$this->_log_error($my_authorize->ipn_data);
			redirect('/user/pay_cancel');
		}
	}

	public function two_checkout()
	{
		// Include the paypal library
		include_once (APPPATH.'libraries/payment/TwoCo.php');
		$this->gateway = '3';

		$gate = $this->db->get_where('gateways', array('name' => 'twoco'))->row();
		$gate_conf = json_decode($gate->settings);

		// Create an instance of the authorize.net library
		$my2_co = new TwoCo();

		// Log the IPN results
		// $my2_co->ipn_log = TRUE;

		// Specify your authorize login and secret
		$my2_co->set_secret($gate_conf['secret_id']);

		// Enable test mode if needed
		$my2_co->enable_test_mode();

		// Check validity and write down it
		if ($my2_co->validate_ipn())
		{
			$settings = json_decode(base64_decode($my2_co->ipn_data['custom']));
			if($settings['type'] == 'reg')
			{
				$this->_new_user_payment($settings['user_id'], $my2_co->ipn_data['total']);
				redirect('/user/pay_complete');
			}
			redirect('/user/pay_cancel');
		}
		else
		{
			$this->_log_error($my2_co->ipn_data);
			redirect('/user/pay_cancel');
		}
	}

	//--------------------------------------------------------------------

	private function _new_user_payment($id, $amount)
	{
		$this->db->where('id', $id)->update('users', array('status' => 1));

		$user = $this->db->get_where('users', array('id' => $id))->row();
		$group = $this->db->get_where('groups', array('id' => $user->group))->row();

		$this->users->send_new_user_email($user->email, $user, $group);

		$this->load->model('transactions/transactions_db');
		$data = array(
			'user' => $user->id,
			'gateway' => $this->gateway,
			'time' => time(),
			'status' => '1',
			'ammount' => $amount,
			'config' => json_encode(array('type' => 'text', 'activated' => 'text', 'duration' => 'text', 'group' => 'text', 'email' => 'text' )),
			'settings' => json_encode(array('type' => 'New Registration', 'activated' => 'yes', 'duration' => $group->repeat_billing, 'group' => $group->id, 'email' => $user->email ))
		);

		$this->transactions_db->insert($id);
	}

	private function _log_error($gate, $data)
	{
		if($this->gateway == 2)
		{
			$settings = @json_decode(@base64_decode(@$data['x_Cust_ID']));
		}
		else
		{
			$settings = @json_decode(@base64_decode(@$data['custom']));
		}
		if(!$settings)
		{
			return false;
		}

		$id = $settings['user_id'];
		if($this->gateway == 1)
		{
			$amount = $data['amount'];
		}
		elseif($this->gateway == 2)
		{
			$amount = $data['x_Amount'];
		}
		elseif($this->gateway == 3)
		{
			$amount = $data['total'];
		}

		$user = $this->db->get_where('users', array('id' => $id))->row();
		$group = $this->db->get_where('groups', array('id' => $user->group))->row();

		$this->load->model('transactions/transactions_db');
		$data = array(
			'user' => $user->id,
			'gateway' => $this->gateway,
			'time' => time(),
			'status' => '0',
			'ammount' => $amount,
			'config' => json_encode(array('type' => 'text', 'activated' => 'text', 'duration' => 'text', 'group' => 'text', 'email' => 'text' )),
			'settings' => json_encode(array('type' => 'New Registration', 'activated' => 'no', 'duration' => $group->repeat_billing, 'group' => $group->id, 'email' => $user->email ))
		);

		$this->transactions_db->insert($id);
	}
}

/* End of file payment/ipn.php */
/* Location: ./application/controllers/payment/ipn.php */

<?php

/**
 * Authorize.net Class
 *
 * Integrate the Authorize.net payment gateway in your site using this
 * easy to use library. Just see the example code to know how you should
 * proceed. Also, remember to read the readme file for this class.
 *
 * @package     Payment Gateway
 * @category	Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

class Authorize extends PaymentGateway
{
    /**
     * Login ID of authorize.net account
     *
     * @var string
     */
    public $login;

    /**
     * Secret key from authorize.net account
     *
     * @var string
     */
    public $secret;

    /**
	 * Initialize the Authorize.net gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();

        // Some default values of the class
		$this->gateway_url = 'https://secure.authorize.net/gateway/transact.dll';
		$this->ipn_log_file = 'authorize.ipn_results.log';

		// Populate $fields array with a few default
		$this->add_field('x_Version',        '3.0');
        $this->add_field('x_Show_Form',      'PAYMENT_FORM');
		$this->add_field('x_Relay_Response', 'TRUE');
	}

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $this->add_field('x_Test_Request', 'TRUE');
        $this->gateway_url = 'https://test.authorize.net/gateway/transact.dll';
    }
	
	/**
     * Load config vars
     *
     * @param array
     * @return none
     */
    public function load_config($config)
    {
        foreach($config as $name => $value)
		{
			$this->$name = $value;
		}
    }

    /**
     * Set login and secret key
     *
     * @param string user login
     * @param string secret key
     * @return void
     */
    public function set_user_info($login, $key)
    {
        $this->login  = $login;
        $this->secret = $key;
    }

    /**
     * Prepare a few payment information
     *
     * @param none
     * @return void
     */
    public function prepare_submit()
    {
        $this->add_field('x_Login', $this->login);
        $this->add_field('x_fp_sequence', $this->fields['x_Invoice_num']);
        $this->add_field('x_fp_timestamp', time());

        $data = $this->fields['x_Login'] . '^' .
                $this->fields['x_Invoice_num'] . '^' .
                $this->fields['x_fp_timestamp'] . '^' .
                $this->fields['x_Amount'] . '^';

        $this->add_field('x_fp_hash', $this->hmac($this->secret, $data));
    }

    /**
	 * Validate the IPN notification
	 *
	 * @param none
	 * @return boolean
	 */
	public function validate_ipn()
	{
	    foreach ($_POST as $field=>$value)
		{
			$this->ipn_data["$field"] = $value;
		}

        $invoice    = intval($this->ipn_data['x_invoice_num']);
        $pnref      = $this->ipn_data['x_trans_id'];
        $amount     = doubleval($this->ipn_data['x_amount']);
        $result     = intval($this->ipn_data['x_response_code']);
        $respmsg    = $this->ipn_data['x_response_reason_text'];

        $md5source  = $this->secret . $this->login . $this->ipn_data['x_trans_id'] . $this->ipn_data['x_amount'];
        $md5        = md5($md5source);

		if ($result == '1')
		{
		 	// Valid IPN transaction.
		 	$this->log_results(true);
		 	return true;
		}
		else if ($result != '1')
		{
		 	$this->last_error = $respmsg;
			$this->log_results(false);
			return false;
		}
        else if (strtoupper($md5) != $this->ipn_data['x_MD5_Hash'])
        {
            $this->last_error = 'MD5 mismatch';
            $this->log_results(false);
            return false;
        }
	}

    /**
     * RFC 2104 HMAC implementation for php.
     *
     * @author Lance Rushing
     * @param string key
     * @param string date
     * @return string encoded hash
     */
    private function hmac($key, $data)
    {
       $b = 64; // byte length for md5

       if (strlen($key) > $b) {
           $key = pack("H*",md5($key));
       }

       $key  = str_pad($key, $b, chr(0x00));
       $ipad = str_pad('', $b, chr(0x36));
       $opad = str_pad('', $b, chr(0x5c));
       $k_ipad = $key ^ $ipad ;
       $k_opad = $key ^ $opad;

       return md5($k_opad  . pack("H*", md5($k_ipad . $data)));
    }
}

/* End of file Authorize.php */
/* Location: ./application/libraries/payment/Authorize.php */

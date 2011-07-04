<?php

/**
 * 2CheckOut Class
 *
 * Integrate the 2CheckOut payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

class TwoCo extends PaymentGateway
{
    /**
     * Secret word to be used for IPN verification
     *
     * @var string
     */
    public $secret;

    /**
     * Initialize the 2CheckOut gateway
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Some default values of the class
        $this->gateway_url = 'https://www.2checkout.com/checkout/purchase';
        $this->ipn_log_file = '2co.ipn_results.log';
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
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $this->add_field('demo', 'Y');
    }

    /**
     * Set the secret word
     *
     * @param string the scret word
     * @return void
     */
    public function set_secret($word)
    {
        if (!empty($word))
        {
            $this->secret = $word;
        }
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
            $this->ipn_data[$field] = $value;
        }

        $vendor_number   = ($this->ipn_data["vendor_number"] != '') ? $this->ipn_data["vendor_number"] : $this->ipn_data["sid"];
        $order_number    = $this->ipn_data["order_number"];
        $order_total     = $this->ipn_data["total"];

        // If demo mode, the order number must be forced to 1
        if($this->demo == "Y" || $this->ipn_data['demo'] == 'Y')
        {
            $order_number = "1";
        }

        // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
        $key = strtoupper(md5($this->secret . $vendor_number . $order_number . $order_total));

        // verify if the key is accurate
        if($this->ipn_data["key"] == $key || $this->ipn_data["x_MD5_Hash"] == $key)
        {
            $this->log_results(true);
            return true;
        }
        else
        {
            $this->last_error = "Verification failed: MD5 does not match!";
            $this->log_results(false);
            return false;
        }
    }
}

/* End of file TwoCo.php */
/* Location: ./application/libraries/payment/TwoCo.php */

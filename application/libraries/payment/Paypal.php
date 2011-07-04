<?php

/**
 * Paypal Class
 *
 * Integrate the Paypal payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package		Payment Gateway
 * @category	Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */
class Paypal extends PaymentGateway
{

    /**
	 * Initialize the Paypal gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();

        // Some default values of the class
		$this->gateway_url = 'https://www.paypal.com/cgi-bin/webscr';
		$this->ipn_log_file = 'paypal.ipn_results.log';

		// Populate $fields array with a few default
		$this->add_field('rm', '2');           // Return method = POST
		$this->add_field('cmd', '_xclick');
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
        $this->gateway_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
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
	 * Validate the IPN notification
	 *
	 * @param none
	 * @return boolean
	 */
	public function validate_ipn()
	{
		// parse the paypal URL
		$url_parsed = parse_url($this->gateway_url);

		// generate the post string from the _POST vars
		$post_string = '';

		foreach ($_POST as $field=>$value)
		{
			$this->ipn_data[$field] = $value;
			$post_string .= $field .'=' . urlencode(stripslashes($value)) . '&';
		}

		$post_string .="cmd=_notify-validate"; // append ipn command

		// open the connection to paypal
		$fp = fsockopen($url_parsed['host'], "80", $err_num, $err_str, 30);

		if(!$fp)
		{
			// Could not open the connection, log error if enabled
			$this->last_error = "fsockopen error no. $err_num: $err_str";
			$this->log_results(false);

			return false;
		}
		else
		{
			// Post the data back to paypal

			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $url_parsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $post_string . "\r\n\r\n");

			// loop through the response from the server and append to variable
			while(!feof($fp))
			{
				$this->ipn_response .= fgets($fp, 1024);
			}

		 	fclose($fp); // close connection
		}

		if (eregi("VERIFIED", $this->ipn_response))
		{
		 	// Valid IPN transaction.
		 	$this->log_results(true);
		 	return true;
		}
		else
		{
		 	// Invalid IPN transaction.  Check the log for details.
			$this->last_error = "IPN Validation Failed . $url_parsed[path] : $url_parsed[host]";
			$this->log_results(false);
			return false;
		}
	}
}

/* End of file Paypal.php */
/* Location: ./application/libraries/payment/Paypal.php */

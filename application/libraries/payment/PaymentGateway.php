<?php

/**
 * Payment Gateway
 *
 * This library provides generic payment gateway handling functionlity
 * to the other payment gateway classes in an uniform way. Please have
 * a look on them for the implementation details.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */
abstract class PaymentGateway {

    /**
     * Holds the last error encountered
     *
     * @var string
     */
    public $last_error;

    /**
     * Do we need to log IPN results ?
     *
     * @var boolean
     */
    public $log_ipn;

    /**
     * File to log IPN results
     *
     * @var string
     */
    public $ipn_log_file;

    /**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipn_response;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    public $test_mode;

    /**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();

    /**
     * IPN post values as array
     *
     * @var array
     */
    public $ipn_data = array();

    /**
     * Payment gateway URL
     *
     * @var string
     */
    public $gateway_url;

    /**
     * Initialization constructor
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        // Some default values of the class
        $this->last_error = '';
        $this->log_ipn = TRUE;
        $this->ipn_response = '';
        $this->test_mode = FALSE;
    }

    /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function add_field($field, $value)
    {
        $this->fields[$field] = $value;
    }

    /**
     * Submit Payment Request
     *
     * Generates a form with hidden elements from the fields array
     * and submits it to the payment gateway URL. The user is presented
     * a redirecting message along with a button to click.
     *
     * @param none
     * @return void
     */
    public function submit_payment($text='')
    {
		$html = '';
        $this->prepare_submit();

        $html .= "<form method=\"post\" id=\"gateway_form_submit\" name=\"gateway_form\" ";
        $html .= "action=\"" . $this->gateway_url . "\">\n";

        foreach ($this->fields as $name => $value)
        {
             $html .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }


        $html .= "<p style=\"text-align:center;\"><br/><br/>";
		$html .= $text;
		$html .= "<br/><br/>\n";
        $html .= "<input type=\"submit\" value=\"".lang('Make Payment')."\"></p>\n";

        $html .= "</form>\n";
		return $html;
    }

    /**
     * Perform any pre-posting actions
     *
     * @param none
     * @return none
     */
    protected function prepare_submit()
    {
        // Fill if needed
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    abstract protected function enable_test_mode();

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    abstract protected function validate_ipn();

    /**
     * Logs the IPN results
     *
     * @param boolean IPN result
     * @return void
     */
    public function log_results($success)
    {

        if (!$this->log_ipn) return;

        // Timestamp
        $text = '[' . date('m/d/Y g:i A').'] - ';

        // Success or failure being logged?
        $text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->last_error . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from gateway:\n";
        foreach ($this->ipn_data as $key=>$value)
        {
            $text .= "$key=$value, ";
        }

        // Log the response from the paypal server
        $text .= "\nIPN Response from gateway Server:\n " . $this->ipn_response;

        // Write to log
        $fp = fopen($this->ipn_log_file,'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);
    }
}

/* End of file PaymentGateway.php */
/* Location: ./application/libraries/payment/PaymentGateway.php */

<?php
/* vim: set ts=4 sw=4 sts=0: */

/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package		XtraUpload
 * @author		Matthew Glinski
 * @copyright	Copyright (c) 2006, XtraFile.com
 * @license		http://xtrafile.com/docs/license
 * @link		http://xtrafile.com
 * @since		Version 2.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------
// payment_lib (Payment Class Config)
// ------------------------------------------------------------------------

// If (and where) to log ipn to file
$config['payment_lib_ipn_log_file'] = BASEPATH . 'logs/payment_ipn.log';
$config['payment_lib_ipn_log'] = TRUE;

// Where are the buttons located at
$config['payment_lib_button_path'] = 'buttons';

// What is the default currency?
$config['payment_lib_currency_code'] = 'USD';

/* End of file paymentlib_config.php */
/* Location: ./system/application/config/paymentlib_config.php */

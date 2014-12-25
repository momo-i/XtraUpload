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

/**
 * XtraUpload Pagination Class
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Pagination
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XU_Pagination extends CI_Pagination {

	/**
	 * Attributes
	 *
	 * @var	string
	 */
	protected $_attributes = 'prevnext';

}

/* End of file Pagination.php */
/* Location: ./application/libraries/Pagination.php */

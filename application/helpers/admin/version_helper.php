<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

// ------------------------------------------------------------------------

/**
 * XtraUpload Admin Version Check Helper
 *
 * @package		XtraUpload
 * @subpackage	Helper
 * @category	Helper
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

if ( ! function_exists('check_version'))
{
	function check_version($urls = "http://xtrafire.momo-i.org/xu_version.txt")
	{
		$version = @file_get_contents($urls);
		return $version;
	}
}

/* End of file version_helper.php */
/* Location: ./application/helpers/admin/version_helper.php */

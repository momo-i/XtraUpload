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
 * XtraUpload Admin Version Check Helper
 *
 * @package		XtraUpload
 * @subpackage	Helper
 * @category	Helper
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

if ( ! function_exists('check_version'))
{
	function check_version($urls = "https://raw.githubusercontent.com/momo-i/XtraUpload/master/application/config/xu_ver.php")
	{
		$version = "Unknown";
		$file = @file_get_contents($urls);
		$config = ROOTPATH.'/temp/xu_ver.php';
		@file_put_contents($config, $file);
		if(file_exists($config))
		{
			require_once $config;
			@unlink($config);
		}

		return $version;
	}
}

/* End of file version_helper.php */
/* Location: ./application/helpers/admin/version_helper.php */

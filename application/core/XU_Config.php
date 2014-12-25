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
 * XtraUpload Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		momo-i
 * @link		https://github.com/momo-i/xtraupload-v3
 */
class XU_Config extends CI_Config {

	/**
	 * Site URL
	 *
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$base_url
	 * @return	string
	 */
	public function site_url($uri = '', $base_url = FALSE)
	{
		$base_url = $base_url !== false && is_string($base_url) ? $base_url : $this->slash_item('base_url');

		if ($uri == '')
		{
			return $base_url.$this->item('index_page');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $base_url.$this->slash_item('index_page').$this->_uri_string($uri).$suffix;
		}
		else
		{
			return $base_url.$this->item('index_page').'?'.$this->_uri_string($uri);
		}
    }

}

/* End of file XU_Config.php */
/* Location: ./application/core/XU_Config.php */

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
 * XtraUpload Number Helpers
 *
 * @package		XtraUpload
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

if ( ! function_exists('byte_format'))
{
	/**
	 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
	 *
	 * @param	mixed	will be cast as int
	 * @param	int
	 * @return	string
	 */
	function byte_format($num, $precision = 1)
	{
		$CI =& get_instance();
		$CI->lang->load('number');

		if ($num >= 1000000000000)
		{
			$num = round($num / 1099511627776, $precision);
			$unit = lang('TB');
		}
		elseif ($num >= 1000000000)
		{
			$num = round($num / 1073741824, $precision);
			$unit = lang('GB');
		}
		elseif ($num >= 1000000)
		{
			$num = round($num / 1048576, $precision);
			$unit = lang('MB');
		}
		elseif ($num >= 1000)
		{
			$num = round($num / 1024, $precision);
			$unit = lang('KB');
		}
		else
		{
			$unit = lang('bytes');
			return number_format($num).' '.$unit;
		}

		return number_format($num, $precision).' '.$unit;
	}
}

/* End of file number_helper.php */
/* Location: ./application/helpers/number_helper.php */

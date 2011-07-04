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
 * XtraUpload Admin Sort Helper
 *
 * @package		XtraUpload
 * @subpackage	Helper
 * @category	Helper
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

// ------------------------------------------------------------------------

if( ! function_exists('get_sort_link'))
{
	function get_sort_link($item, $sort, $dir)
	{
		if($item == $sort)
		{
			if($dir == 'desc')
			{
				return "sort_form('".$item."', 'asc')";
			}
			else
			{
				return "sort_form('".$item."', 'desc')";
			}
		}
		else
		{
			return "sort_form('".$item."', 'desc')";
		}
	}
}

if( ! function_exists('get_sort_arrow'))
{
	function get_sort_arrow($item, $sort, $dir)
	{
		if($sort == $item )
		{
			if($dir == 'asc')
			{
				?><img src="<?php echo base_url(); ?>img/order/arrow_up.png" alt="" class="nb"><?
			}
			else
			{
				?><img src="<?php echo base_url(); ?>img/order/arrow_down.png" alt="" class="nb"><?
			}
		}
	}
}

/* End of file sort_helper.php */
/* Location: ./application/helpers/admin/sort_helper.php */

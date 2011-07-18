<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */
if ( ! function_exists('lang'))
{
	function lang($line, $lines=null, $int=0)
	{
		$CI =& get_instance();
		$lines = $CI->lang->line($line);
		return $lines;
	}
}

if ( ! function_exists('nlang'))
{
	function nlang($line, $lines, $int=0)
	{
		$CI =& get_instance();
		$lines = $CI->lang->line($line, $lines, $int);
		return $lines;
	}
}

if ( ! function_exists('get_language'))
{
	function get_language($lang = 'en_US')
	{
		$CI =& get_instance();
		$locale = $CI->lang->get_language($lang);
		return $locale;
	}
}

if ( ! function_exists('get_region'))
{
	function get_region($lang = 'en_US')
	{
		$CI =& get_instance();
		$region = $CI->lang->get_region($lang);
		return $region;
	}
}

if ( ! function_exists('available_lang'))
{
	function available_lang()
	{
		$CI =& get_instance();
		$CI->config->load('language');
		$available_lang = $CI->config->item('available_lang');
		$langs = array('en_US' => lang('English'));
		$paths = scandir(APPPATH.'/language');
		foreach ($paths as $lang)
		{
			if(isset($available_lang[$lang]))
			{
				$langs[$lang] = $available_lang[$lang];
			}
		}
		return $langs;
	}
}

if ( ! function_exists('is_rtl'))
{
	function is_rtl($lang = 'en_US')
	{
		$CI =& get_instance();
		$is_rtl = $CI->lang->is_rtl($lang);
		return $is_rtl;
	}
}

if ( ! function_exists('lang_timezone'))
{
	function lang_timezone($key)
	{
		$timezones = array(
			'UM12' => lang('(UTC -12:00) Baker/Howland Island'),
			'UM11' => lang('(UTC -11:00) Samoa Time Zone, Niue'),
			'UM10' => lang('(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti'),
			'UM95' => lang('(UTC -9:30) Marquesas Islands'),
			'UM9' => lang('(UTC -9:00) Alaska Standard Time, Gambier Islands'),
			'UM8' => lang('(UTC -8:00) Pacific Standard Time, Clipperton Island'),
			'UM7' => lang('(UTC -7:00) Mountain Standard Time'),
			'UM6' => lang('(UTC -6:00) Central Standard Time'),
			'UM5' => lang('(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time'),
			'UM45' => lang('(UTC -4:30) Venezuelan Standard Time'),
			'UM4' => lang('(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time'),
			'UM35' => lang('(UTC -3:30) Newfoundland Standard Time'),
			'UM3' => lang('(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay'),
			'UM2' => lang('(UTC -2:00) South Georgia/South Sandwich Islands'),
			'UM1' => lang('(UTC -1:00) Azores, Cape Verde Islands'),
			'UTC' => lang('(UTC) Greenwich Mean Time, Western European Time'),
			'UP1' => lang('(UTC +1:00) Central European Time, West Africa Time'),
			'UP2' => lang('(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time'),
			'UP3' => lang('(UTC +3:00) Moscow Time, East Africa Time'),
			'UP35' => lang('(UTC +3:30) Iran Standard Time'),
			'UP4' => lang('(UTC +4:00) Azerbaijan Standard Time, Samara Time'),
			'UP45' => lang('(UTC +4:30) Afghanistan'),
			'UP5' => lang('(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time'),
			'UP55' => lang('(UTC +5:30) Indian Standard Time, Sri Lanka Time'),
			'UP575' => lang('(UTC +5:45) Nepal Time'),
			'UP6' => lang('(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time'),
			'UP65' => lang('(UTC +6:30) Cocos Islands, Myanmar'),
			'UP7' => lang('(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam'),
			'UP8' => lang('(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time'),
			'UP875' => lang('(UTC +8:45) Australian Central Western Standard Time'),
			'UP9' => lang('(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time'),
			'UP95' => lang('(UTC +9:30) Australian Central Standard Time'),
			'UP10' => lang('(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time'),
			'UP105' => lang('(UTC +10:30) Lord Howe Island'),
			'UP11' => lang('(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu'),
			'UP115' => lang('(UTC +11:30) Norfolk Island'),
			'UP12' => lang('(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time'),
			'UP1275' => lang('(UTC +12:45) Chatham Islands Standard Time'),
			'UP13' => lang('(UTC +13:00) Phoenix Islands Time, Tonga'),
			'UP14' => lang('(UTC +14:00) Line Islands')
		);
		if (isset($timezones[$key]))
		{
			return $timezones[$key];
		}
		return $timezones['UTC'];
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */

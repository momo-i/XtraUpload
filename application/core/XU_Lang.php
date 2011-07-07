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
 * Language Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Language
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/language.html
 */
class XU_Lang extends CI_Lang {

	private $_default_locale;
	private $_translate;

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
		log_message('debug', "Zend Locale Class Initialized");
		$this->_default_locale = new Zend_Locale(Zend_Locale::BROWSER);
		if( ! Zend_Locale::isLocale($this->_default_locale, true, false))
		{
			if( ! Zend_Locale::isLocale($this->_default_locale, false, false))
			{
				throw new Zend_Locale_Exception("The locale '$locale' is no known locale");
			}
		}
		$locale = APPPATH."/language/{$this->_default_locale}/xtraupload.mo";
		if( ! is_file($locale))
		{
			$this->_default_locale->setLocale('en_US');
		}
		$this->_default_locale = new Zend_Locale($this->_default_locale);
		$this->_translate = new Zend_Translate('gettext', APPPATH."/language/{$this->_default_locale}/xtraupload.mo", $this->_default_locale->getLanguage());
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	$line	the language line
	 * @return	string
	 */
	public function line($line = 'nolang')
	{
		return $this->_translate->_($line);
	}

	public function get_language($lang = 'en_US')
	{
		$locale = new Zend_Locale($lang);
		return $locale->getLanguage();
	}

	public function get_region($lang = 'en_US')
	{
		$locale = new Zend_Locale($lang);
		return $locale->getRegion();
	}

}
// END Language Class

/* End of file Lang.php */
/* Location: ./system/core/Lang.php */

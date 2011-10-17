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

	private $_dbtypes = array();

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
		log_message('debug', "XtraUpload Zend Locale Class Initialized");
		try {
			$this->_default_locale = new Zend_Locale();
		} catch(Exception $e) {
			$this->_default_locale = new Zend_Locale('en_US');
		}
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

		$this->_dbtypes = array(
			'db_invalid_connection_str' => $this->lang('Unable to determine the database settings based on the connection string you submitted.'),
			'db_unable_to_connect' => $this->lang('Unable to connect to your database server using the provided settings.'),
			'db_invalid_query' => $this->lang('The query you submitted is not valid.'),
			'db_must_set_table' => $this->lang('You must set the database table to be used with your query.'),
			'db_must_use_set' => $this->lang('You must use the "set" method to update an entry.'),
			'db_must_use_index' => $this->lang('You must specify an index to match on for batch updates.'),
			'db_batch_missing_index' => $this->lang('One or more rows submitted for batch updating is missing the specified index.'),
			'db_must_use_where' => $this->lang('Updates are not allowed unless they contain a "where" clause.'),
			'db_del_must_use_where' => $this->lang('Deletes are not allowed unless they contain a "where" or "like" clause.'),
			'db_field_param_missing' => $this->lang('To fetch fields requires the name of the table as a parameter.'),
			'db_unsupported_function' => $this->lang('This feature is not available for the database you are using.'),
			'db_transaction_failure' => $this->lang('Transaction failure: Rollback performed.'),
			'db_unable_to_drop' => $this->lang('Unable to drop the specified database.'),
			'db_unsuported_feature' => $this->lang('Unsupported feature of the database platform you are using.'),
			'db_unsuported_compression' => $this->lang('The file compression format you chose is not supported by your server.'),
			'db_filepath_error' => $this->lang('Unable to write data to the file path you have submitted.'),
			'db_invalid_cache_path' => $this->lang('The cache path you submitted is not valid or writable.'),
			'db_table_name_required' => $this->lang('A table name is required for that operation.'),
			'db_column_name_required' => $this->lang('A column name is required for that operation.'),
			'db_column_definition_required' => $this->lang('A column definition is required for that operation.'),
			'db_error_heading' => $this->lang('A Database Error Occurred'),
		);

	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	$line	the language line
	 * @return	string
	 */
	public function line($line = 'nolang', $lines = NULL, $int = 0)
	{
		$this->_check_database($line);
		if($lines && $int)
		{
			return $this->_translate->plural($line, $lines, $int);
		}
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

	public function is_rtl($lang = 'en_US')
	{
		$is_rtl = Zend_Locale_Data::getList($lang, 'layout');
		return $is_rtl;
	}

	public function set_locale($lang = 'en_US')
	{
		$this->_default_locale = new Zend_Locale($lang);
		if ( ! file_exists(APPPATH."language/{$this->_default_locale}/xtraupload.mo"))
		{
			$mofile = APPPATH."language/en_US/xtraupload.mo";
		}
		else
		{
			$mofile = APPPATH."language/{$this->_default_locale}/xtraupload.mo";
		}
		$language = $this->_default_locale->getLanguage() ? $this->_default_locale->getLanguage() : 'en';
		try {
			$this->_translate = new Zend_Translate('gettext', $mofile, $language);
		} catch(Exception $e) {
			$this->_translate = new Zend_Translate('gettext', $mofile, 'en');
		}
	}

	private function _check_database(&$line)
	{
		if (array_key_exists($line, $this->_dbtypes))
		{
			$line = $this->_dbtypes[$line];
		}
	}

	public function lang($str)
	{
		return $this->_translate->translate($str);
	}

}
// END Language Class

/* End of file Lang.php */
/* Location: ./system/core/Lang.php */

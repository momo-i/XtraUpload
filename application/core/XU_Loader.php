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
 * XtraUpload Loader Class
 *
 * Loads framework components.
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Loader
 * @author		momo-i
 * @link		https://github.com/momo-i/xtraupload-v3
 */
class XU_Loader extends CI_Loader {

	/**
	 * Store loading extensions
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_ci_extensions = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Load Extension
	 *
	 * This function loads the specified extension.
	 *
	 * @access	public
	 * @param	array	$extensions	specified extension
	 * @return	void
	 */
	public function extension($extensions = array())
	{
		if(!is_array($extensions)) {
			$extensions = array($extensions);
		}

		foreach($extensions as $extension) {
			$plugin = strtolower(str_replace('.php', '', $extension));

			// If the extension is already loaded, continue on.
			if(isset($this->_ci_extensions[$extension])) {
				continue;
			}

			// Attempt to load the extension.
			if(file_exists($extension_path = sprintf(APPPATH.'extend/%s/main.php', $extension))) {
				include $extension_path;
			} else {
				if(file_exists($extension_path = sprintf(BASEPATH.'extend/%s/main.php', $extension))) {
					include $extension_path;
				} else {
					show_error(sprintf('Unable to load the requested file: extend/%s/main.php', $extension));
				}
			}

			// Initialize the plugin and log it.
			$this->_ci_extensions[$extension] = new $plugin();

			log_message('debug', sprintf('Extension loaded: %s', $plugin));
		}
	}

	/**
	 * Load View
	 *
	 * Extends on the default view loader.
	 *
	 * @access	public
	 * @param	string	$view	Skin name if empty, use default
	 * @param	array	$vars	Data to load
	 * @param	bool	$return	Data to load
	 * @retun	object
	 */
	public function view($view, $vars = array(), $return = FALSE)
	{
		if( (substr($view, 0, 7) !== 'default') && !file_exists($file = sprintf(APPPATH.'views/%s.php', $view)) ) {
			list($theme, $path) = explode('/', $view, 2);
			unset($theme);
			$view = sprintf('default/%s', $path);
		}

		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}


}

/* End of file XU_Loader.php */
/* Location: ./application/core/XU_Loader.php */

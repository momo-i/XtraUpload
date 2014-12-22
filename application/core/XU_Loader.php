<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Loader Class
 *
 * Loads framework components.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Loader
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
class XU_Loader extends CI_Loader {

	protected $_ci_extensions = array();

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Load Extension
	 *
	 * This function loads the specified extension.
	 *
	 * @access	 public
	 * @param	  array
	 * @return	 void
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
	 * @param $view
	 * @param array $vars
	 * @param bool $return
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

/* End of file Loader.php */
/* Location: ./system/core/Loader.php */

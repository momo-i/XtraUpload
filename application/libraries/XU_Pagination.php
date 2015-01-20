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
 * XtraUpload Pagination Class
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Pagination
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XU_Pagination extends CI_Pagination {

	/**
	 * Attributes
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $_attributes = ' class="prevnext"';

	/**
	 * Full tag open
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $full_tag_open = '<div class="pagination">';

	/**
	 * Full tag close
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $full_tag_close = '</div>';

	/**
	 * Current tag open
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $cur_tag_open = '<span class="current">';

	/**
	 * Current tag close
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $cur_tag_close = '</span>';

	/**
	 * Constructor
	 *
	 * @param	array	$params	Initialization parameters
	 * @return	void
	 */
	public function __construct($params = array())
	{
		parent::__construct();

		log_message('debug', 'XU Pagination Class Initialized');
		$this->first_link = lang('&lsaquo; First');
		$this->next_link = lang('&gt;');
		$this->prev_link = lang('&lt;');
		$this->last_link = lang('Last &rsaquo;');
	}

}

/* End of file Pagination.php */
/* Location: ./application/libraries/Pagination.php */

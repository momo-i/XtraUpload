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
 * XtraUpload XU_API Menu Library
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/api/menus
 */
class Xu_menus_api {

	/**
	 * Store
	 *
	 * @access	private
	 * @var		object
	 */
	private $_store;

	/**
	 * CodeIgniter singleton
	 *
	 * @access	private
	 * @var		object
	 */
	private $CI;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "XtraUpload Menu API Class Initialized");
		$this->_init();
	}

	/**
	 * Xu_menus_api::_init()
	 *
	 * Initialize store object
	 *
	 * @access	private
	 * @return	void
	 */
	private function _init()
	{
		$this->_store = new stdClass();
		$this->_store->main_menu = array();
		$this->_store->admin_menu = array();
		$this->_store->admin_menu_names = array();
		$this->_store->admin_menu_order = array();
		$this->_store->admin_menu_count = 0;
		$this->_store->plugin_menu = array();
		$this->_store->sub_menu = array();
	}

	/**
	 * Xu_menus_api::get_main_menu()
	 *
	 * Returns main menu HTML code
	 *
	 * @access	public
	 * @return	string	html tag
	 */
	public function get_main_menu()
	{
		$html = '';
		foreach ($this->_store->main_menu as $link => $arr)
		{
			if(((isset($item['login']) && $item['login'] == true) && $this->CI->session->userdata('id')) or !isset($item['login']))
			{
				$html .= '<li';
				if(stristr($this->CI->uri->uri_string(), $link) && !stristr($this->CI->uri->uri_string(),'admin'))
				{
					$html .= ' id="current"';
				}
				$html .= '><a href="'.site_url($link).'"><img src="'.base_url().$arr['icon'].'" class="nb" alt=""> '.$arr['text'].'</a></li>';
			}
		}
		return $html;
	}

	/**
	 * Xu_menus_api::add_main_menu_link()
	 *
	 * Add link for main menu
	 *
	 * @access	public
	 * @param	string	$link	Link
	 * @param	string	$text	Menu text
	 * @param	string	$icon	Menu icon
	 * @param	bool	$login	Login flag
	 * @return	void
	 */
	public function add_main_menu_link($link, $text, $icon, $login=false)
	{
		$menu = $this->_store->main_menu;
		$menu[$link] = array(
			'icon' => $icon, 
			'text' => $text, 
			'login' => $login
		);
		$this->_store->main_menu = $menu;
	}

	/**
	 * Xu_menus_api::remove_main_menu_link()
	 *
	 * Remove link for main menu
	 *
	 * @access	public
	 * @param	int		$id		Link ID(not in use)
	 * @param	strin	$link	Link name
	 * @return	void
	 */
	public function remove_main_menu_link($id, $link)
	{
		unset($this->_store->main_menu[$link]);
	}

	/**
	 * Xu_menus_api::add_admin_menu()
	 *
	 * Add link for admin menu
	 *
	 * @access	public
	 * @param	string	$name	Menu name
	 * @param	int		$id		Link ID
	 * @return	mixed	int|false
	 */
	public function add_admin_menu($name, $id='')
	{
		if($id == '')
		{
			$id = $this->_store->admin_menu_count;
		}
		
		if(isset($this->_store->admin_menu[$id]))
		{
			return false;
		}
		
		$this->_store->admin_menu_names[$id] = $name;
		
		$count = count($this->_store->admin_menu_order) - 1;
		$this->_store->admin_menu_order[$count] = $id;
		$this->_store->admin_menu[$id] = array();
		$this->_store->admin_menu_count++;
		
		return $id;
	}

	/**
	 * Xu_menus_api::get_admin_menu_order()
	 *
	 * Returns admin menu order
	 *
	 * @access	public
	 * @param	int		$id	Menu ID
	 * @return	int
	 */
	public function get_admin_menu_order($id='')
	{
		if($id != '')
		{
			return $this->_store->admin_menu_order[$id];
		}
		
		return $this->_store->admin_menu_order;
	}

	/**
	 * Xu_menus_api::put_admin_menu_order()
	 *
	 * Add admin menu order
	 *
	 * @access	public
	 * @param	string	$menu	Menu name
	 * @return	void
	 */
	public function put_admin_menu_order($menu)
	{
		$this->_store->admin_menu_order = $menu;
		ksort($this->_store->admin_menu_order);
	}

	/**
	 * Xu_menus_api::remove_admin_menu()
	 *
	 * Remove menu from admin page
	 *
	 * @access	public
	 * @param	int		$id	Menu ID
	 * @return	int
	 */
	public function remove_admin_menu($id='')
	{
		if($id == '')
		{
			$id = $this->_store->admin_menu_count;
		}
		
		unset($this->_store->admin_menu_names[$id], $this->_store->admin_menu[$id]);
		$this->_store->admin_menu_count--;
			
		return $id;
	}

	/**
	 * Xu_menus_api::get_admin_menu()
	 *
	 * Returns admin menu html tag
	 *
	 * @access	public
	 * @param	int		$id	Menu ID
	 * @return	string
	 */
	public function get_admin_menu($id='')
	{
		if($id != '')
		{
			$html = '<h3>'.$this->_store->admin_menu_names[$id].'</h3><ul class="sidemenu">'."\n";
			//sort($this->_store->admin_menu);
			foreach($this->_store->admin_menu[$id] as $link => $arr)
			{
				$html .= '<li><a href="'.site_url($link).'"><img src="'.base_url().$arr['icon'].'" class="nb" alt=""> '.$arr['text'].'</a></li>'."\n";
			}
			$html .= '</ul>'."\n";
			return $html;
		}
		else
		{
			$html = '';
			foreach($this->_store->admin_menu_order as $index => $id)
			{
				$menu = $this->_store->admin_menu[$id];
				$html .= '<h3>'.$this->_store->admin_menu_names[$id].'</h3><ul class="sidemenu">'."\n";
				//sort($this->_store->admin_menu);
				foreach($menu as $link => $arr)
				{
					$html .= '<li><a href="'.site_url($link).'"><img src="'.base_url().$arr['icon'].'" class="nb" alt=""> '.$arr['text'].'</a></li>'."\n";
				}
				$html .= '</ul>'."\n";
			}
			return $html;
		}
		
	}

	/**
	 * Xu_menus_api::add_admin_menu_link()
	 *
	 * Add menu link to admin menu
	 *
	 * @access	public
	 * @param	int		$id	Menu ID
	 * @param	string	$link	Link
	 * @param	string	$text	Menu text
	 * @param	string	$icon	Menu icon
	 * @return	void
	 */
	public function add_admin_menu_link($id, $link, $text, $icon)
	{
		$menu = $this->_store->admin_menu[$id];
		$menu[$link] = array(
			'icon' => $icon, 
			'text' => $text
		);
		$this->_store->admin_menu[$id] = $menu;
	}

	/**
	 * Xu_menus_api::remove_admin_menu_link()
	 *
	 * Remove menu link from admin page
	 *
	 * @access	public
	 * @param	int		$id	Menu ID
	 * @param	string	$link	Link
	 * @return	void
	 */
	public function remove_admin_menu_link($id, $link)
	{
		unset($this->_store->admin_menu[$id][$link]);
	}

	/**
	 * Xu_menus_api::get_plugin_menu()
	 *
	 * Returns plugin menu html tag
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_plugin_menu()
	{
		$html = '';
		//sort($this->_store->admin_menu);
		foreach($this->_store->plugin_menu as $link => $arr)
		{
			$html .= '<li><a href="'.site_url($link).'"><img src="'.base_url().$arr['icon'].'" class="nb" alt="" /> '.$arr['text'].'</a></li>'."\n";
		}
		return $html;
	}

	/**
	 * Xu_menus_api::add_plugin_menu_link()
	 *
	 * Add menu link to plugin page
	 *
	 * @access	public
	 * @param	string	$link	Link
	 * @param	string	$text	Menu text
	 * @param	string	$icon	Menu icon
	 * @return	void
	 */
	public function add_plugin_menu_link($link, $text, $icon)
	{
		$menu = $this->_store->plugin_menu;
		$menu[$link] = array(
			'icon' => $icon, 
			'text' => $text
		);
		$this->_store->plugin_menu = $menu;
	}

	/**
	 * Xu_menus_api::remove_plugin_menu_link()
	 *
	 * Remove menu link from plugin page
	 *
	 * @access	public
	 * @param	string	$link Link
	 * @return	void
	 */
	public function remove_plugin_menu_link($link)
	{
		unset($this->_store->plugin_menu[$link]);
	}

	/**
	 * Xu_menus_api::get_sub_menu()
	 *
	 * Returns sub menu
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_sub_menu()
	{
		$html = '';
		foreach($this->_store->sub_menu as $name => $menu)
		{
			if(stristr($name, '-login'))
			{
				if(!$this->CI->session->userdata('id'))
					continue;
			}
			$name = str_replace('-login', '', $name);
			
			$html .= '<h3>'.$name.'</h3><ul class="sidemenu">'."\n";
			
			foreach($menu as $link => $item)
			{
				if(((isset($item['login']) && $item['login'] == true) && $this->CI->session->userdata('id')) or !$item['login'])
				{
					$html .= '<li><a href="'.site_url($item['link']).'"><img src="'.base_url().$item['icon'].'" class="nb" alt="" /> '.$item['text'].'</a></li>'."\n";
				}
			}
			$html .= '</ul>'."\n";
		}
		
		return $html;
	}

	/**
	 * Xu_menus_api::add_sub_menu_link()
	 *
	 * Add link to sub menu
	 *
	 * @access	public
	 * @param	string	$cat	Category
	 * @param	string	$link	Link
	 * @param	string	$text	Menu text
	 * @param	string	$icon	Menu icon
	 * @param	bool	$login	Login flag
	 * @return	void
	 */
	public function add_sub_menu_link($cat, $link, $text, $icon, $login=false)
	{
		$menu = $this->_store->sub_menu;
		if(!isset($menu[$cat]))
		{
			$menu[$cat] = array();
		}
		$menu[$cat][$link] = array(
			'link' => $link, 
			'icon' => $icon, 
			'text' => $text, 
			'login' => $login
		);
		$this->_store->sub_menu = $menu;
	}

	/**
	 * Xu_menus_api::remove_sub_menu_link()
	 *
	 * Remove link from sub menu
	 *
	 * @access	public
	 * @param	string	$cat	Category
	 * @param	string	$link	Link
	 * @return	void
	 */
	public function remove_sub_menu_link($cat, $link)
	{
		unset($this->_store->sub_menu[$cat][$link]);
	}

	/**
	 * Xu_menus_api::_get_store()
	 *
	 * Get store
	 *
	 * @access	private
	 * @param	string	$item	Item name
	 * @return	void
	 */
	private function _get_store($item)
	{
		return $this->_store->$item;
	}
}

/* End of file Xu_menus_api.php */
/* Location: ./application/libraries/api/Xu_menus_api.php */

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
 * XtraUpload Files Controller
 *
 * @package		XtraUpload
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Matthew Glinski
 * @author		momo-i
 * @link		http://xtrafile.com/docs
 */
class Files extends CI_Controller {

	/**
	 * Lets the Files::download_fail() function know if the download completed
	 *
	 * @access	private
	 * @var		bool
	 */
	private $download_complete = false;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Files::index()
	 *
	 * Redirecting Home::index()
	 *
	 * @return	void
	 */
	public function index()
	{
		redirect('home');
	}

	/**
	 * Files::get()
	 *
	 * The file download geteway page, file info, wait time,
	 * and captcha test are served here
	 *
	 * @access	public
	 * @param	string	$id		File ID
	 * @param	string	$name	File name
	 * @param	string	$error	Error message
	 * @return	void
	 */
	public function get($id='', $name='', $error='')
	{
		// check for auth string in URL
		$this->check_for_httpauth();

		// Get the file object for the requested file
		$file = $this->files_db->get_file_object($id);

		// If there is no such file found, redirect to 404 error
		if( ! isset($file->server) OR ! $file)
		{
			$this->_send404();
			return;
		}

		// Is file Password Protected?
		if( ! empty($file->pass) && ($this->input->post('pass') != $file->pass))
		{
			$this->_pass_page($file);
			return;
		}

		// Get the captcha image if required
		$data['captcha_bool'] = $this->startup->group_config->download_captcha;
		$data['sec'] = intval($this->startup->group_config->wait_time);
		$data['auto_download'] = intval($this->startup->group_config->auto_download);

		// Is captcha required?
		if($data['captcha_bool'] == 2)
		{
			// yes, generate a captcha
			$data['captcha'] = $this->_get_captcha();
		}
		else if($data['captcha_bool'] == 1)
		{
			if($this->session->userdata('captcha_served') == 'true')
			{
				// no, already served a captcha
				$data['captcha'] = '';
				$data['captcha_bool'] = 0;
			}
			else
			{
				// yes, generate a captcha
				$data['captcha'] = $this->_get_captcha();
			}
		}
		else
		{
			// no captchas are turned off
			$data['captcha'] = '';
		}

		// If conditions are right, just download the file :P
		if(( ! $data['captcha_bool'] && $data['sec'] <= 1 && $data['auto_download']) OR ($file->direct && $file->direct_bw > $file->size))
		{
			$link = $this->_gen_dlink($id, $name, 120);
			$this->_goto_download_url($file, $link);
		}

		// Detect if the requested file is an image
		$data['image'] = false;
		if($file->is_image)
		{
			$data['image'] = $this->files_db->get_image_links($id, $name);
		}

		// Setup some variables
		$data['error'] = $error;
		$data['file'] = $file;

		// if the user has already waited and just failed
		// the captcha test dont make them wait again
		if($this->input->post('waited'))
		{
			$data['sec'] = 1;
		}

		// video code
		$src = site_url('files/stream/'.$file->file_id.'/'.md5($this->config->config['encryption_key'].$file->file_id.$this->input->ip_address()).'/'.$file->link_name);
		$swf = base_url('assets/players/flashmediaelement.swf');
		// player size
		$player_width = $this->startup->site_config->player_width;
		$player_height = $this->startup->site_config->player_height;
		switch($file->type)
		{
			case 'flv':
				$flvplayer = base_url('assets/players/flvplayer.swf');
				$express = base_url('assets/players/expressInstall.swf');
				$data['icon'] = 'tv';
				$data['code'] = <<<EOF
          <script type="text/javascript">
            var flashvarsVideo = {
                source: "$src",
                type: "video",
                streamtype: "http",
                server: "",//Used for rtmp streams
                duration: "",
                poster: "",
                autostart: "false",
                hardwarescaling: "false",
                darkcolor: "000000",
                brightcolor: "4c4c4c",
                controlcolor: "FFFFFF",
                hovercolor: "67A8C1"
            };
            var params = {
                menu: "false",
                scale: "noScale",
                allowFullscreen: "true",
                allowScriptAccess: "always",
                bgcolor: "#000000",
                quality: "high",
                wmode: "opaque"
            };
            var attributes = {
                id:"JarisFLVPlayer"
            };
            swfobject.embedSWF("$flvplayer", "flvplayer", "$player_width", "$player_height", "10.0.0", "$express", flashvarsVideo, params, attributes);
          </script>
          <div id="flvplayer"></div>
          <br>

EOF;
			break;
			case 'mp4':
			case 'm4v':
			case 'mov':
			case 'wmv':
			case 'avi':
			case 'mpg':
				$type = $file->type;
				$data['icon'] = 'tv';
				$data['code'] = <<<EOF
          <video id="player2" controls="controls" width="$player_width" height="$player_height" preload="none">
            <source type="video/$type" src="$src">
            <object width="$player_width" height="$player_height" type="application/x-shockwave-flash" data="$swf">
              <param name="movie" value="$swf" />
              <param name="flashvars" value="controls=true&amp;file=$src" />
            </object>
          </video>
          <span id="player2-mode"></span>
          <script type="text/javascript">
            $('audio,video').mediaelementplayer({
              success: function(player, node) {
                $('#' + node.id + '-mode').html('mode: ' + player.pluginType);
              }
            });
          </script>
          <br>

EOF;
			break;
			case 'mp3':
				$data['icon'] = 'music';
				$data['code'] = <<<EOF
          <audio id="player2" src="$src" controls="controls" type="audio/mp3"></audio>
          <script type="text/javascript">
            $('audio').mediaelementplayer();
          </script>
          <br>

EOF;
			break;
			default:
				$data['icon'] = 'help';
				$data['code'] = '';
			break;
		}

		// Send the information to the user
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Download File').' '.$this->startup->site_config->title_separator.' '.$name));
		$this->load->view($this->startup->skin.'/files/get', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Files::gen()
	 *
	 * The file download validation page, if everything
	 * checks out the file is downloaded
	 *
	 * @access	public
	 * @param	string	$id		File ID
	 * @param	string	$name	File name
	 * @return	void
	 */
	public function gen($id='', $name='')
	{
		// check if the user submitted a captcha
		if(!$this->input->post('captcha') and ($this->startup->group_config->download_captcha == 2 or ($this->startup->group_config->download_captcha == 1 and !$this->session->userdata('captcha_served'))))
		{
			$error = '<span class="alert">'.lang('The Captcha you submited was incorrect.').'</span>';
			$this->get($id, $name, $error);
			return false;
		}

		$file = $this->files_db->get_file_object($id);

		// Is file Password Protected?
		if(!empty($file->pass) and ($this->input->post('pass') != $file->pass))
		{
			$this->_pass_page($file);
			return;
		}

		// Captcha validation check
		if($this->startup->group_config->download_captcha == 2 or ($this->startup->group_config->download_captcha == 1 and !$this->session->userdata('captcha_served')))
		{
			// If user submitted CAPTCHA, delete it.
			if($this->session->flashdata('captcha'))
			{
				if(file_exists(ROOTPATH.'/temp/'.$this->session->flashdata('captcha')))
				{
					unlink(ROOTPATH.'/temp/'.$this->session->flashdata('captcha'));
				}
			}

			// Delete old captchas
			$expiration = time()-7200; // Two hour limit
			$this->db->delete('captcha', array('captcha_time <' => $expiration));

			// get captcha information from DB
			$query = $this->db->get_where('captcha', array('word' => $this->input->post('captcha'),'ip_address' => $this->input->ip_address(),'captcha_time >' => $expiration));
			$rows = $query->num_rows();

			// check if the captcha exists
			if (!$rows)
			{
				$error = '<span class="alert">'.lang('The Captcha you submited was incorrect.').'</span><br>';

				$this->get($id, $name, $error);
				return false;
			}
			else
			{
				if($this->startup->group_config->download_captcha == 1)
				{
					if($this->session->userdata('captcha_served') != 'true')
					{
						$this->session->set_userdata('captcha_served', 'true');
					}
				}

				$data = array(
					'word' => $this->input->post('captcha'),
					'ip_address' => $this->input->ip_address(),
					'captcha_time >' => $expiration
				);

				$this->db->delete('captcha', $data);
			}
		}
		$file = $this->files_db->get_file_object($id);

		$link = $this->_gen_dlink($id, $file->link_name, 60);

		// construct final download link
		$this->_goto_download_url($file, $link);
	}

	/**
	 * Files::download()
	 *
	 * Download file if a download link was generated
	 *
	 * @access	public
	 * @param	string	$dlink	Database ID
	 * @param	string	$name	File name
	 * @return	void
	 */
	public function download($dlink, $name='')
	{
		// did user submit WWW_basic-auth params?
		$this->check_for_httpauth();

		$down_link = $this->db->select('time, ip, fid')->get_where('dlinks', array('id' => $dlink));

		// Download link does not exists
		if($down_link->num_rows() != 1)
		{
			$this->_send404();
			exit();
		}

		// get dlink object
		$dl = $down_link->row();

		// File link expired
		if($dl->time < time())
		{
			$this->db->delete('dlinks', array('id' => $dlink));
			$this->_send404();
			exit();
		}

		// Not the same user
		if($dl->ip != $this->input->ip_address())
		{
			$this->db->delete('dlinks', array('id' => $dlink));
			$this->_send404();
			exit();
		}

		// Send file data and headers to the browser
		$this->_download($dl->fid);
	}

	/**
	 * Files::stream()
	 *
	 * Stream a file for playback, mp3's are currently only supported
	 *
	 * @access	public
	 * @param	int		$fid	File ID
	 * @param	string	$enc	Encrypted string with file ID and IP address
	 * @return	void
	 */
	public function stream($fid='', $enc='')
	{
		$file = $this->files_db->get_file_object($fid);
		if(!$file)
		{
			show_404();
		}

		if($enc != md5($this->config->config['encryption_key'].$fid.$this->input->ip_address()))
		{
			show_404();
		}

		// get server manager
		$this->load->model('server/server_db');

		// let the embed definition see if we can stream, and describe the file transfer speed limit
		$code = $this->xu_api->embed->get_embed_code($file->type);
		if(is_array($code))
		{
			$serv = $this->server_db->get_server_for_download($file);

			if($serv != base_url())
			{
				header("Location: ".$serv.implode('/', $this->uri->segment_array()));
				exit;
			}

			$this->_download($file->file_id, $code['speed']);
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Files::embed()
	 *
	 * Embed File HTML
	 *
	 * @access	public
	 * @param	string	$type	Embed type
	 * @param	int		$fid	File ID
	 * @return	void
	 */
	public function embed($type='mp3', $fid='')
	{
		$file = $this->files_db->get_file_object($fid);

		if(file_exists(APPPATH.'views/_protected/files/embed/'.$type.'.php'))
		{
			$this->load->view('_protected/files/embed/'.$type, array('file' => $file));
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Files::manage()
	 *
	 * File management page, logged in users only
	 *
	 * @access	public
	 * @return	void
	 */
	public function manage()
	{
		// Check for logged in user
		$this->functions->check_login();

		// Load the pagination library
		$this->load->library('pagination');

		// Setup some vars
		$data['flash_message'] = '';
		$per_page = 100;

		// Pagination config values
		$config['base_url'] = site_url('files/manage');
		$config['total_rows'] = $this->files_db->get_num_files();
		$config['per_page'] = $per_page;

		// setup the pagination library
		$this->pagination->initialize($config);

		// Get the files object
		$data['files'] = $this->files_db->get_files($per_page, $this->uri->segment(3), '', true);

		// If there was a message generated previously, load it
		if($this->session->flashdata('msg'))
		{
			$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
		}

		// Create the pagination HTML
		$data['pagination'] = $this->pagination->create_links();

		// Load the static files
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Your Files')));
		$this->load->view($this->startup->skin.'/files/manage', $data);
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Files::manage()
	 *
	 * File management page, logged in users only
	 *
	 * @access	public
	 * @return	void
	 */
	public function search()
	{
		if($this->startup->group_config->can_search)
		{
			$this->load->helper('string');

			$data['flash_message'] = '';
			// If there was a message generated previously, load it
			if($this->session->flashdata('msg'))
			{
				$data['flash_message'] = '<span class="info"><strong>'.$this->session->flashdata('msg').'</strong></span>';
			}

			if(!$this->uri->segment(3))
			{
				// Load the static files
				$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Your Files')));
				$this->load->view($this->startup->skin.'/files/search/new', $data);
				$this->load->view($this->startup->skin.'/footer');
				return;
			}

			// Load the pagination library
			$this->load->library('pagination');
			$this->load->helper('date');

			// Setup some vars
			$per_page = 100;

			// Pagination config values
			$config['base_url'] = site_url('files/search/'.$this->uri->segment(3));
			$config['total_rows'] = $this->files_db->search_num_files($this->uri->segment(3));
			$config['per_page'] = $per_page;

			$data['num_results'] = $config['total_rows'];
			$data['query'] = $this->uri->segment(3);

			if($data['num_results'] == 0)
			{
				$this->session->set_flashdata('msg', lang('Your query returned 0 results, please try again.'));
				redirect('files/search');
			}

			// setup the pagination library
			$this->pagination->initialize($config);

			// Get the files object
			$data['files'] = $this->files_db->search_files($this->uri->segment(3), $per_page, $this->uri->segment(3), '', true);

			// Create the pagination HTML
			$data['pagination'] = $this->pagination->create_links();

			// Load the static files
			$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Manage Your Files')));
			$this->load->view($this->startup->skin.'/files/search/query', $data);
			$this->load->view($this->startup->skin.'/footer');
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Files::delete()
	 *
	 * File delete page, logged in users only
	 *
	 * @access	public
	 * @param	int		$id		Database ID
	 * @param	string	$secid	MD5 hashed ID
	 * @param	string	$name	File name
	 * @return	void
	 */
	public function delete($id, $secid, $name)
	{
		if($this->files_db->file_exists($id, $secid))
		{
			$this->files_db->delete_file($id, $secid, $name);
			$this->session->set_flashdata('msg', lang('File Deleted'));
			if($this->session->userdata('id'))
			{
				redirect('files/manage');
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	/**
	 * Files::old_redirect()
	 *
	 * Old XtraUpload download url redirect
	 *
	 * @deprecated	3.1.0
	 * @access	public
	 * @return	void
	 */
	public function old_redirect()
	{
		$id = $this->uri->segment(2);
		$name = $this->uri->segment(3);

		if($name)
		{
			redirect('files/get/'.$id.'/'.url_title($name));
		}
		else
		{
			redirect('files/get/'.$id);
		}
	}

	/**
	 * Files::_download()
	 *
	 * File Download private function
	 *
	 * @access	private
	 * @param	int		$id		File ID
	 * @param	int		$speed	Download speed
	 * @return	void
	 */
	private function _download($id, $speed=0)
	{
		// Get the file refrence
		$file = $this->files_db->get_file_object($id, 'file_id, filename, o_filename, size, direct');

		// Increment the file downloa count
		$this->files_db->add_to_downloads($file->file_id);

		// If file exists, send download
		if($file)
		{
			// Load the custom file download library
			$this->load->library('filedownload');

			// Function to call if user aborts connection during download
			register_shutdown_function(array('Files', '_download_fail'), $file);

			// Setup config for file download
			$config = array();
			$config['file'] = $file->filename;
			$config['resume'] = true;
			$config['filename'] = $file->o_filename;
			$config['speed'] = intval($this->startup->group_config->speed_limit);

			if($speed)
			{
				$config['speed'] = $speed;
			}

			// Send the actual file
			$bandwidth = $this->filedownload->send_download($config);
		}
	}

	/**
	 * Files::_download_fail()
	 *
	 * Function called if the user aborts the connection prematurely
	 *
	 * @access	public
	 * @param	object	$file	Files_db::get_file_object()
	 * @return	void
	 */
	static public function _download_fail($file)
	{
		$this1 =& get_instance();
		$user = $this1->session->userdata('id');
		if(empty($user))
		{
			$user = lang("Guest");
		}
		$data = array(
			'file_id'   => $file->file_id,
			'user'	  => $user,
			'ip'		=> $this1->input->ip_address(),
			'size'	  => $file->size,
			'sent'	  => $this1->filedownload->bandwidth,
			'time'	  => time()
		);

		if($file->direct && !$this1->startup->group_config->auto_download)
		{
			$this1->files_db->edit_premium_bandwith($file->file_id, $dl_obj->bandwidth);
		}

		$this1->db->insert('downloads', $data);
	}

	/**
	 * Files::mass_delete()
	 *
	 * Mass delete files
	 *
	 * @access	public
	 * @return	void
	 */
	public function mass_delete()
	{
		if($this->input->post('files') and is_array($this->input->post('files')))
		{
			foreach($this->input->post('files') as $id)
			{
				$this->files_db->delete_file_user($id, $this->session->userdata('id'));
			}
			$count = count($this->input->post('files'));
			$this->session->set_flashdata('msg', nlang('%d file has been deleted', '%d files have been deleted', $count));
		}

		redirect('files/manage');
	}

	/**
	 * Files::_gen_dlink()
	 *
	 * Generate timed download link
	 *
	 * @access	public
	 * @param	string	$fid	File ID
	 * @param	string	$name	File name
	 * @param	int		$time	Download limit time?
	 * @param	bool	$stream	Stream or not
	 * @return	int		The insert ID number when performing database inserts.
	 */
	private function _gen_dlink($id, $name, $time=60, $stream=false)
	{
		$this->db->insert('dlinks', array('fid' => $id, 'name' => $name, 'time' => time()+($time*60), 'ip' => $this->input->ip_address(), 'stream' => $stream));
		return $this->db->insert_id();
	}

	/**
	 * Files::_pass_page()
	 *
	 * Function called file is password protected
	 *
	 * @access	private
	 * @param	object	$file	Object returns Files_db::get_file_object()
	 * @param	bool	$error	If password was submitted and is incorrect: true else false
	 * @return	void
	 */
	private function _pass_page($file, $error=false)
	{
		// if password was submitted and is incorrect
		if($this->input->post('pass'))
		{
			$error = true;
		}

		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('Password Protected File')));
		$this->load->view($this->startup->skin.'/files/pass_protected', array('error' => $error, 'file' => $file));
		$this->load->view($this->startup->skin.'/footer');
	}

	/**
	 * Files::_get_captcha()
	 *
	 * Function called to generate a captcha image
	 *
	 * @access	private
	 * @return	mixed	if create successful, returns html code, if failure, return false.
	 */
	private function _get_captcha()
	{
		$this->load->helper('captcha');
		$img_width = $this->startup->site_config->captcha_width;
		$img_height = $this->startup->site_config->captcha_height;
		$vals = array(
			'img_path'  => ROOTPATH.'/temp/',
			'word'	  => $this->users->gen_pass(3, false),
			'img_width' => $img_width,
			'img_height' => $img_height,
			'img_url'   => base_url().'temp/',
			'fonts' => BASEPATH.'assets/fonts/texb.ttf'
		);

		$cap = create_captcha($vals);

		$data = array(
			'captcha_time'  => $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'		  => $cap['word']
		);

		$this->db->insert('captcha', $data);
		$this->session->set_flashdata('captcha', $cap['time'].'.jpg');

		return $cap['image'];
	}

	/**
	 * Files::_send404()
	 *
	 * Function called to send a 404 error on invalid file link
	 *
	 * @access	private
	 * @return	void
	 */
	private function _send404()
	{
		$this->load->view($this->startup->skin.'/header', array('header_title' => lang('File Not Found')));
		$this->load->view($this->startup->skin.'/files/404');
		$this->load->view($this->startup->skin.'/footer');
		return;
	}

	/**
	 * Files::check_for_httpauth()
	 *
	 * Login user if they send login info using basic-auth, mostly for download accelerators
	 *
	 * @access	public
	 * @return	void
	 */
	public function check_for_httpauth()
	{
		if (!isset($_SERVER['PHP_AUTH_USER']))
		{
			return;
		}
		else
		{
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];
			if($this->users->process_login($user, $pass))
			{
				$this->startup->get_group();
				return;
			}
		}
	}

	/**
	 * Files::_goto_download_url()
	 *
	 * build the correct url for downloading a file and send the visitor to it
	 *
	 * @access	public
	 * @param	object	$file	Object returns Files_db::get_file_object()
	 * @param	string	$link	Returns id generated by Files::_gen_dlink()
	 * @return	void
	 */

	private function _goto_download_url($file, $link)
	{
		$dlink = $file->server;
		if($this->config->config['index_page'] != '')
		{
			$dlink .= $this->config->config['index_page'].'/';
		}

		$dlink .= 'files/download/'.$link.'/'.$file->link_name;
		header("Location: ".$dlink);
		exit;
	}
}

/* End of file Files.php */
/* Location: ./application/controllers/Files.php */

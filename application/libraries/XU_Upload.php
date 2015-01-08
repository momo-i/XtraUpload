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
 * File Uploading Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Uploads
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/file_uploading.html
 */
class XU_Upload extends CI_Upload {

	/**
	 * Maximum file size
	 *
	 * @access	public
	 * @var		int
	 */
	public $max_size = 0;

	/**
	 * Maximum image width
	 *
	 * @access	public
	 * @var		int
	 */
	public $max_width = 0;

	/**
	 * Maximum image height
	 *
	 * @access	public
	 * @var		int
	 */
	public $max_height = 0;

	/**
	 * Minimum image width
	 *
	 * @access	public
	 * @var		int
	 */
	public $min_width = 0;

	/**
	 * Minimum image height
	 *
	 * @access	public
	 * @var		int
	 */
	public $min_height = 0;

	/**
	 * Maximum filename length
	 *
	 * @access	public
	 * @var		int
	 */
	public $max_filename = 0;

	/**
	 * Maximum duplicate filename increment ID
	 *
	 * @access	public
	 * @var		int
	 */
	public $max_filename_increment = 100;

	/**
	 * Allowed file types
	 *
	 * @access	public
	 * @var		string
	 */
	public $allowed_types = '';

	/**
	 * Allowed or not
	 *
	 * @access	public
	 * @var		bool
	 */
	public $allowed_or_not = TRUE;

	/**
	 * Temporary filename
	 *
	 * @access	public
	 * @var		string
	 */
	public $file_temp = '';

	/**
	 * Filename
	 *
	 * @access	public
	 * @var		string
	 */
	public $file_name = '';

	/**
	 * Original filename
	 *
	 * @access	public
	 * @var		string
	 */
	public $orig_name = '';

	/**
	 * File type
	 *
	 * @access	public
	 * @var		string
	 */
	public $file_type = '';

	/**
	 * File size
	 *
	 * @access	public
	 * @var		int
	 */
	public $file_size = NULL;

	/**
	 * Filename extension
	 *
	 * @access	public
	 * @var		string
	 */
	public $file_ext = '';

	/**
	 * Force filename extension to lowercase
	 *
	 * @access	public
	 * @var		string
	 */
	public $file_ext_tolower = FALSE;

	/**
	 * Upload path
	 *
	 * @access	public
	 * @var		string
	 */
	public $upload_path = '';

	/**
	 * Overwrite flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $overwrite = FALSE;

	/**
	 * Obfuscate filename flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $encrypt_name = FALSE;

	/**
	 * Is image flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $is_image = FALSE;

	/**
	 * Image width
	 *
	 * @access	public
	 * @var		int
	 */
	public $image_width = NULL;

	/**
	 * Image height
	 *
	 * @access	public
	 * @var		int
	 */
	public $image_height = NULL;

	/**
	 * Image type
	 *
	 * @access	public
	 * @var		string
	 */
	public $image_type = '';

	/**
	 * Image size string
	 *
	 * @access	public
	 * @var		string
	 */
	public $image_size_str = '';

	/**
	 * Error messages list
	 *
	 * @access	public
	 * @var		array
	 */
	public $error_msg = array();

	/**
	 * Remove spaces flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $remove_spaces = TRUE;

	/**
	 * MIME detection flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $detect_mime = TRUE;

	/**
	 * XSS filter flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $xss_clean = FALSE;

	/**
	 * Apache mod_mime fix flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $mod_mime_fix = TRUE;

	/**
	 * Temporary filename prefix
	 *
	 * @access	public
	 * @var		string
	 */
	public $temp_prefix = 'temp_file_';

	/**
	 * Filename sent by the client
	 *
	 * @access	public
	 * @var		bool
	 */
	public $client_name = '';

	/**
	 * Filename override
	 *
	 * @accses	protected
	 * @var		string
	 */
	protected $_file_name_override = '';

	/**
	 * MIME types list
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_mimes = array();

	/**
	 * CI Singleton
	 *
	 * @access	protected
	 * @var		object
	 */
	protected $_CI;

	/**
	 * Translateion type
	 *
	 * @accss	private
	 * @var		array
	 */
	private $_types = array();

	/**
	 * Constructor
	 *
	 * @param	array	$props
	 * @return	void
	 */
	public function __construct($config = array())
	{
		empty($config) OR $this->initialize($config, FALSE);

		$this->_mimes =& get_mimes();
		$this->_CI =& get_instance();

		log_message('debug', 'Upload Class Initialized');

		$this->_types = array(
			'upload_userfile_not_set' => lang('Unable to find a post variable called userfile.'),
			'upload_file_exceeds_limit' => lang('The uploaded file exceeds the maximum allowed size in your PHP configuration file.'),
			'upload_file_exceeds_form_limit' => lang('The uploaded file exceeds the maximum size allowed by the submission form.'),
			'upload_file_partial' => lang('The file was only partially uploaded.'),
			'upload_no_temp_directory' => lang('The temporary folder is missing.'),
			'upload_unable_to_write_file' => lang('The file could not be written to disk.'),
			'upload_stopped_by_extension' => lang('The file upload was stopped by extension.'),
			'upload_no_file_selected' => lang('You did not select a file to upload.'),
			'upload_invalid_filetype' => lang('The filetype you are attempting to upload is not allowed.'),
			'upload_invalid_filesize' => lang('The file you are attempting to upload is larger than the permitted size.'),
			'upload_invalid_dimensions' => lang('The image you are attempting to upload doesn\'t fit into the allowed dimensions.'),
			'upload_destination_error' => lang('A problem was encountered while attempting to move the uploaded file to the final destination.'),
			'upload_no_filepath' => lang('The upload path does not appear to be valid.'),
			'upload_no_file_types' => lang('You have not specified any allowed file types.'),
			'upload_bad_filename' => lang('The file name you submitted already exists on the server.'),
			'upload_not_writable' => lang('The upload destination folder does not appear to be writable.'),
		);
	}

	/**
	 * Perform the file upload
	 *
	 * @param	string	$field
	 * @return	bool
	 */
	public function do_upload($field = 'userfile')
	{
		// Is $_FILES[$field] set? If not, no reason to continue.
		if (isset($_FILES[$field]))
		{
			$_file = $_FILES[$field];
		}
		// Does the field name contain array notation?
		elseif (($c = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $field, $matches)) > 1)
		{
			$_file = $_FILES;
			for ($i = 0; $i < $c; $i++)
			{
				// We can't track numeric iterations, only full field names are accepted
				if (($field = trim($matches[0][$i], '[]')) === '' OR ! isset($_file[$field]))
				{
					$_file = NULL;
					break;
				}

				$_file = $_file[$field];
			}
		}

		if ( ! isset($_file))
		{
			$this->set_error($this->_types['upload_no_file_selected']);
			return FALSE;
		}

		// Is the upload path valid?
		if ( ! $this->validate_upload_path())
		{
			// errors will already be set by validate_upload_path() so just return FALSE
			return FALSE;
		}

		// Was the file able to be uploaded? If not, determine the reason why.
		if ( ! is_uploaded_file($_file['tmp_name']))
		{
			$error = isset($_file['error']) ? $_file['error'] : 4;

			switch ($error)
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->set_error($this->_types['upload_file_exceeds_limit']);
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->set_error($this->_types['upload_file_exceeds_form_limit']);
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->set_error($this->_types['upload_file_partial']);
					break;
				case UPLOAD_ERR_NO_FILE:
					$this->set_error($this->_types['upload_no_file_selected']);
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$this->set_error($this->_types['upload_no_temp_directory']);
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->set_error($this->_types['upload_unable_to_write_file']);
					break;
				case UPLOAD_ERR_EXTENSION:
					$this->set_error($this->_types['upload_stopped_by_extension']);
					break;
				default:
					$this->set_error($this->_types['upload_no_file_selected']);
					break;
			}

			return FALSE;
		}

		// Set the uploaded data as class variables
		$this->file_temp = $_file['tmp_name'];
		$this->file_size = $_file['size'];

		// Skip MIME type detection?
		if ($this->detect_mime !== FALSE)
		{
			$this->_file_mime_type($_file);
		}

		$this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
		$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
		$this->file_name = $this->_prep_filename($_file['name']);
		$this->file_ext	 = $this->get_extension($this->file_name);
		$this->client_name = $this->file_name;

		// Is the file type allowed to be uploaded?
		if ( ! $this->is_allowed_filetype())
		{
			log_message('debug', "Extension: {$this->file_ext}");
			$this->set_error($this->_types['upload_invalid_filetype']);
			return FALSE;
		}

		// if we're overriding, let's now make sure the new name and type is allowed
		if ($this->_file_name_override !== '')
		{
			$this->file_name = $this->_prep_filename($this->_file_name_override);

			// If no extension was provided in the file_name config item, use the uploaded one
			if (strpos($this->_file_name_override, '.') === FALSE)
			{
				$this->file_name .= $this->file_ext;
			}
			else
			{
				// An extension was provided, let's have it!
				$this->file_ext	= $this->get_extension($this->_file_name_override);
			}

			if ( ! $this->is_allowed_filetype(TRUE))
			{
				$this->set_error($this->_types['upload_invalid_filetype']);
				return FALSE;
			}
		}

		// Convert the file size to kilobytes
		if ($this->file_size > 0)
		{
			$this->file_size = round($this->file_size/1024, 2);
		}

		// Is the file size within the allowed maximum?
		if ( ! $this->is_allowed_filesize())
		{
			$this->set_error($this->_types['upload_invalid_filesize']);
			return FALSE;
		}

		// Are the image dimensions within the allowed size?
		// Note: This can fail if the server has an open_basedir restriction.
		if ( ! $this->is_allowed_dimensions())
		{
			$this->set_error($this->_types['upload_invalid_dimensions']);
			return FALSE;
		}

		// Sanitize the file name for security
		$this->file_name = $this->_CI->security->sanitize_filename($this->file_name);

		// Truncate the file name if it's too long
		if ($this->max_filename > 0)
		{
			$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
		}

		// Remove white spaces in the name
		if ($this->remove_spaces === TRUE)
		{
			$this->file_name = preg_replace('/\s+/', '_', $this->file_name);
		}

		/*
		 * Validate the file name
		 * This function appends an number onto the end of
		 * the file if one with the same name already exists.
		 * If it returns false there was a problem.
		 */
		$this->orig_name = $this->file_name;

		if ($this->overwrite === FALSE)
		{
			$this->file_name = $this->set_filename($this->upload_path, $this->file_name);

			if ($this->file_name === FALSE)
			{
				return FALSE;
			}
		}

		/*
		 * Run the file through the XSS hacking filter
		 * This helps prevent malicious code from being
		 * embedded within a file. Scripts can easily
		 * be disguised as images or other file types.
		 */
		if ($this->xss_clean && $this->do_xss_clean() === FALSE)
		{
			$this->set_error($this->_types['upload_unable_to_write_file']);
			return FALSE;
		}

		/*
		 * Move the file to the final destination
		 * To deal with different server configurations
		 * we'll attempt to use copy() first. If that fails
		 * we'll use move_uploaded_file(). One of the two should
		 * reliably work in most environments
		 */
		// Plupload, file already moved, then ignore.
		/*if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
		{
			if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
			{
				$this->set_error($this->_types['upload_destination_error']);
				return FALSE;
			}
		}*/

		/*
		 * Set the finalized image dimensions
		 * This sets the image width/height (assuming the
		 * file was an image). We use this information
		 * in the "data" function.
		 */
		$this->set_image_properties($this->upload_path.$this->file_name);

		return TRUE;
	}

	/**
	 * Finalized Data Array
	 *
	 * Returns an associative array containing all of the information
	 * related to the upload, allowing the developer easy access in one array.
	 *
	 * @param	string	$index
	 * @return	mixed
	 */
	public function data($index = NULL)
	{
		$data = array(
				'file_name'		=> $this->file_name,
				'file_type'		=> $this->file_type,
				'file_path'		=> $this->upload_path,
				'full_path'		=> $this->upload_path.$this->file_name,
				'raw_name'		=> str_replace($this->file_ext, '', $this->file_name),
				'orig_name'		=> $this->orig_name,
				'client_name'		=> $this->client_name,
				'file_ext'		=> $this->file_ext,
				'file_size'		=> $this->file_size,
				'is_image'		=> $this->is_image(),
				'image_width'		=> $this->image_width,
				'image_height'		=> $this->image_height,
				'image_type'		=> $this->image_type,
				'image_size_str'	=> $this->image_size_str,
			);

		if ( ! empty($index))
		{
			return isset($data[$index]) ? $data[$index] : NULL;
		}

		return $data;
	}

	/**
	 * Set the file name
	 *
	 * This function takes a filename/path as input and looks for the
	 * existence of a file with the same name. If found, it will append a
	 * number to the end of the filename to avoid overwriting a pre-existing file.
	 *
	 * @param	string	$path
	 * @param	string	$filename
	 * @return	string
	 */
	public function set_filename($path, $filename)
	{
		if ($this->encrypt_name === TRUE)
		{
			$filename = md5(uniqid(mt_rand())).$this->file_ext;
		}

		if ( ! file_exists($path.$filename))
		{
			return $filename;
		}

		$filename = str_replace($this->file_ext, '', $filename);

		$new_filename = '';
		for ($i = 1; $i < $this->max_filename_increment; $i++)
		{
			if ( ! file_exists($path.$filename.$i.$this->file_ext))
			{
				$new_filename = $filename.$i.$this->file_ext;
				break;
			}
		}

		if ($new_filename === '')
		{
			$this->set_error($this->_types['upload_bad_filename']);
			return FALSE;
		}
		else
		{
			return $new_filename;
		}
	}

	/**
	 * Verify that the filetype is allowed
	 *
	 * @param	bool	$ignore_mime
	 * @return	bool
	 */
	public function is_allowed_filetype($ignore_mime = FALSE)
	{
		if ($this->allowed_types === '*')
		{
			log_message('debug', 'Allowed types are *');
			if($this->allowed_or_not)
				return TRUE;
			else
				return FALSE;
		}

		if (empty($this->allowed_types) OR ! is_array($this->allowed_types))
		{
			log_message('debug', "Allowed types: ".print_r($this->allowed_types, true));
			$this->set_error($this->types['upload_no_file_types']);
				return FALSE;
		}

		$ext = strtolower(ltrim($this->file_ext, '.'));

		if (in_array($ext, $this->allowed_types, TRUE))
		{
			log_message('debug', "No allowed types in array: $ext");
			if($this->allowed_or_not)
				return FALSE;
			else
				return TRUE;
		}

		// Images get some additional checks
		if (in_array($ext, array('gif', 'jpg', 'jpeg', 'jpe', 'png'), TRUE) && @getimagesize($this->file_temp) === FALSE)
		{
			log_message('debug', "No allowed image types. ext: {$ext}");
			if($this->allowed_or_not)
				return FALSE;
			else
				return TRUE;
		}

		if ($ignore_mime === TRUE)
		{
			log_message('debug', "Ignore mime...");
			return TRUE;
		}

		if (isset($this->_mimes[$ext]))
		{
			log_message('debug', "File types: {$this->_mimes[$ext]}");
			$return = false;
			if($this->allowed_or_not)
			{
				log_message('debug', "allowed_or_not: true ");
				$return = is_array($this->_mimes[$ext])
					? in_array($this->file_type, $this->_mimes[$ext], TRUE)
					: ($this->_mimes[$ext] === $this->file_type);
			}
			else
			{
				log_message('debug', "allowed_or_not: false");
				$return = is_array($this->_mimes[$ext])
					? ($this->_mimes[$ext] === $this->file_type)
					: in_array($this->file_type, $this->_mimes[$ext], TRUE);
			}
			return $return;
		}

		log_message('debug', __FUNCTION__.": last...");
		if($this->allowed_or_not)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Validate Upload Path
	 *
	 * Verifies that it is a valid upload path with proper permissions.
	 *
	 * @return	bool
	 */
	public function validate_upload_path()
	{
		if ($this->upload_path === '')
		{
			$this->set_error($this->_types['upload_no_filepath']);
			return FALSE;
		}

		if (realpath($this->upload_path) !== FALSE)
		{
			$this->upload_path = str_replace('\\', '/', realpath($this->upload_path));
		}

		if ( ! is_dir($this->upload_path))
		{
			$this->set_error($this->_types['upload_no_filepath']);
			return FALSE;
		}

		if ( ! is_really_writable($this->upload_path))
		{
			$this->set_error($this->_types['upload_not_writable']);
			return FALSE;
		}

		$this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
		return TRUE;
	}

	/**
	 * Set an error message
	 *
	 * @param	string	$msg
	 * @return	CI_Upload
	 */
	public function set_error($msg)
	{
		is_array($msg) OR $msg = array($msg);

		foreach ($msg as $val)
		{
			$this->error_msg[] = $val;
			log_message('error', $val);
		}

		return $this;
	}

	/**
	 * Display the error message
	 *
	 * @param	string	$open
	 * @param	string	$close
	 * @return	string
	 */
	public function display_errors($open = '<p>', $close = '</p>')
	{
		return (count($this->error_msg) > 0) ? $open.implode($close.$open, $this->error_msg).$close : '';
	}

	/**
	 * Get file information using finfo
	 *
	 * @access	private
	 * @param	string	$tmpfile	filename
	 * @return	array
	 */
	private function _get_fileinfo($tmpfile)
	{
		if(function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME);
			$info = finfo_file($finfo, $tmpfile);
			$info = preg_replace("/^(.+?);.*$/", "\\1", $info);
			$info = strtolower(trim(stripslashes($info), '"'));
		}
		else
		{
			$info = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type']);
			$info = strtolower(trim(stripslashes($info), '"'));
		}

		return $info;
	}

	/**
	 * Upload for plupload
	 *
	 * @access	public
	 * @param	array	$data
	 * @param	array	$files
	 * @param	string	$secid
	 * @return	void
	 */
	public function process_upload($data, $files, $secid)
	{
		if ( ! $this->validate_upload_path())
		{
			$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'.lang('Invalid upload path.').'"}, "id" : "id"}';
			log_message('debug', $errmsg);
			return $errmsg;
		}
		// Get parameters
		$chunk = isset($data["chunk"]) ? $data["chunk"] : 0;
		$chunks = isset($data["chunks"]) ? $data["chunks"] : 0;
		$this->file_name = isset($data["name"]) ? $data["name"] : '';
		$_FILES['file']['name'] = $data['name'];
		//log_message('debug', print_r($data, true));
		//log_message('debug', print_r($_FILES, true));
		// Clean the fileName for security reasons
		$this->file_name = preg_replace('/[^\w\._]+/', '', $this->file_name);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($this->upload_path . $this->file_name))
		{
			if(preg_match('#\.#', $this->file_name))
			{
				$ext = strrpos($this->file_name, '.');
				$file_name_a = substr($this->file_name, 0, $ext);
				$file_name_b = substr($this->file_name, $ext);
			}
			else
			{
				$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'.lang('Failed to find extension.').'"}, "id" : "id"}';
				log_message('debug', $errmsg. " ". $this->file_name);
				return $errmsg;
			}
			$count = 1;
			while (file_exists($this->upload_path . $file_name_a . '_' . $count . $file_name_b))
			{
				$count++;
			}
			$this->file_name = $file_name_a . '_' . $count . $file_name_b;
		}

		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		{
			$content_type = $_SERVER["HTTP_CONTENT_TYPE"];
		}
		if (isset($_SERVER["CONTENT_TYPE"]))
		{
			$content_type = $_SERVER["CONTENT_TYPE"];
		}

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($content_type, "multipart") !== false)
		{
			if (isset($files['file']['tmp_name']) && is_uploaded_file($files['file']['tmp_name']))
			{
				// Open temp file
				$out = fopen($this->upload_path . $this->file_name, $chunk == 0 ? "wb" : "ab");
				if ($out)
				{
					// Read binary input stream and append it to temp file
					$in = fopen($files['file']['tmp_name'], "rb");

					if ($in)
					{
						while ($buff = fread($in, 4096))
						{
							fwrite($out, $buff);
						}
					}
					else
					{
						$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'.lang('Failed to open input stream.').'"}, "id" : "id"}';
						log_message('debug', $errmsg);
						return $errmsg;
					}
					fclose($in);
					fclose($out);
					@unlink($files['file']['tmp_name']);
				}
				else
				{
					$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "'.lang('Failed to open output stream.').'"}, "id" : "id"}';
					log_message('debug', $errmsg);
					return $errmsg;
				}
			}
			else
			{
				$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "'.lang('Failed to move uploaded file.').'"}, "id" : "id"}';
				log_message('debug', $errmsg);
				return $errmsg;
			}
		}
		else
		{
			// Open temp file
			$out = fopen($this->upload_path . $this->file_name, $chunk == 0 ? "wb" : "ab");
			if ($out)
			{
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in)
				{
					while ($buff = fread($in, 4096))
					{
						fwrite($out, $buff);
					}
				}
				else
				{
					$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "'.lang('Failed to open input stream.').'"}, "id" : "id"}';
					log_message('debug', $errmsg);
					return $errmsg;
				}
				fclose($in);
				fclose($out);
			}
			else
			{
				$errmsg = '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "'.lang('Failed to open output stream.').'"}, "id" : "id"}';
				log_message('debug', $errmsg);
				return $errmsg;
			}
		}

		// Return JSON-RPC response
		$errmsg = '{"jsonrpc" : "2.0", "result" : "'.$this->upload_path . $this->file_name.'", "id" : "id"}';
		log_message('debug', $errmsg);
		log_message('debug', "Chunk: $chunk, Chunks: $chunks");
		if(($chunk == 0 && $chunks == 0) OR ($chunk == ($chunks - 1)))
		{
			$this->do_upload('file');
			$this->updata = $this->data();
			$errmsg = json_encode($this->updata);
			$temp = $this->updata['file_path'].$secid;
			log_message('debug', print_r($temp, true));
			file_put_contents($temp, $errmsg);
		}
		return $errmsg;
	}

}

/* End of file XU_Upload.php */
/* Location: ./application/libraries/XU_Upload.php */

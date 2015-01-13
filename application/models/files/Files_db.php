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
 * XtraUpload Files DB Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class Files_db extends CI_Model {

    public function __construct($select='')
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//------------------------
	// File Viewing Functions
	//------------------------
	public function get_files($limit=100, $offset=0, $select='')
	{
		$posts = array();
		$this->db->order_by("refrence.id", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('user' => $this->session->userdata('id')), $limit, $offset);
		return $query;	
	}
		
	public function get_files_by_user($user, $public=false, $limit=100, $offset=0, $select='')
	{
		$posts = array();
		$this->db->order_by("refrence.id", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}
		
		if($public)
		{
			$this->db->where('feature', 1);	
		}

		$query = $this->db->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('user' => $user), $limit, $offset);
		return $query;	
	}
	
	public function search_files($query, $limit=100, $offset=0, $select='')
	{
		$posts = array();
		$this->db->order_by("refrence.id", "desc"); 
		if($select != '')
		{
			$this->db->select($select);
		}

		$this->db->like('o_filename', $query);
		$this->db->or_like('descr', $query);
		$this->db->or_like('file_id', $query);

		$query = $this->db->join('files', 'refrence.link_id = files.id')->get('refrence', $limit, $offset);
		return $query;	
	}
	
	public function get_admin_files($sort, $direction, $limit=100, $offset=0, $select='')
	{
		$this->db->order_by($sort, $direction); 
		if($select != '')
		{
			$this->db->select($select);
		}

		return $this->db->join('files', 'refrence.link_id = files.id')->get('refrence', $limit, $offset);
	}
	
	public function get_admin_files_search($query, $sort, $direction, $limit=100, $offset=0, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$this->db->like('o_filename', $query);
		$this->db->or_like('descr', $query);
		$this->db->or_like('file_id', $query);
		$this->db->order_by($sort, $direction); 
		return $this->db->join('files', 'refrence.link_id = files.id')->get('refrence', $limit, $offset);
	}
	
	public function get_images($limit=100, $offset=0, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$this->db->order_by("refrence.id", "desc"); 		
		return $this->db->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('user' => $this->session->userdata('id'), 'refrence.is_image' => 1), $limit, $offset);
	}
	
	public function get_num_files()
	{
		$query = $this->db->select('id')->where('user', $this->session->userdata('id'));
		return $query->count_all_results('refrence');
	}
	
	public function get_num_user_files($user, $public=false)
	{
		if($public)
		{
			$this->db->where('feature', 1);	
		}
		
		$query = $this->db->select('id')->where('user', $user);
		return $query->count_all_results('refrence');
	}
	
	public function search_num_files($query)
	{
		$this->db->like('o_filename', $query);
		$this->db->or_like('descr', $query);
		$this->db->or_like('file_id', $query);
		
		return $this->db->select('id')->count_all_results('refrence');
	}
	
	public function get_admin_num_files()
	{
		return $this->db->select('id')->count_all_results('refrence');
	}

	public function get_admin_num_files_in_folder($folder_id)
	{
		return $this->db->select('f_id')->count_all_results('folder');
	}
	
	public function get_admin_num_files_search($query)
	{
		$this->db->like('o_filename', $query);
		$this->db->or_like('descr', $query);
		$this->db->or_like('file_id', $query);
		
		return $this->db->select('id')->count_all_results('refrence');
	}
	
	public function get_files_usage_space($user = '')
	{
		if(empty($user))
		{
			$user = $this->session->userdata('id');
		}
		
		$query = $this->db->select_sum('size')->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('user' => $user));
		return $query->row()->size;
	}
	
	public function get_file_by_id($id, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get_where('refrence', array('id' => $id));
		return $query->row();
	}
	
	public function get_file_for_download($id, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$query = $this->db->get_where('files', array('id' => $id));
		if($query->num_rows() != 1)
		{
			return false;
		}
		return $query->row();
	}
	
	public function get_recent_files($limit=5, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}

		$this->db->order_by("id", "desc"); 
		$query = $this->db->get_where('refrence', array('feature' => 1), $limit, 0);
		
		return $query;
	}
	
	public function get_links($secid, $file_object=false)
	{
		$links = array();

		// Use provided file object
		if($file_object)
		{
			$links['down'] = site_url('/files/get/'.$file_object->file_id.'/'.$file_object->link_name);
			$links['del'] = site_url('/files/delete/'.$file_object->file_id.'/'.$file_object->secid.'/'.$file_object->link_name);
			
			if($file_object->is_image)
			{
				$links['img'] = site_url('/image/links/'.$file_object->file_id.'/'.$file_object->link_name);
			}
			$links = $this->xu_api->hooks->run_hooks('files_db::get_links', $links);
			return $links;
		}
		
		// No provided file object, make one
		$query = $this->db->select('file_id, link_name, is_image')->get_where('refrence', array('secid' => $secid), 1, 0);
		log_message('debug', "Files_db: Check $secid");
		if($query->num_rows() == 1)
		{
			log_message('debug', 'Files_db: Found links.');
			$file = $query->row();
			log_message('debug', print_r($file, true));
			$links['down'] = site_url('/files/get/'.$file->file_id.'/'.$file->link_name);
			$links['del'] = site_url('/files/delete/'.$file->file_id.'/'.$secid.'/'.$file->link_name);
			
			if($file->is_image)
			{
				$links['img'] = site_url('/image/links/'.$file->file_id.'/'.$file->link_name);
			}
			$links = $this->xu_api->hooks->run_hooks('files_db::get_links', $links);
			return $links;
		}
		else
		{
			log_message('debug', 'Files_db: Failed.');
		    $reason = $this->get_reason_upload_failed($secid);
		    if(!$reason)
		    {
		        return array('reason' => 'unknown', 'failed' => true);
		    }
		    else
		    {
		        return array('reason' => $reason, 'failed' => true);
		    }
		}
	}
	
	public function get_reason_upload_failed($secid)
	{
	    $d = $this->db->get_where('upload_failures', array('secid' => $secid));
	    if($d->num_rows() > 0)
	    {
	        return $d->row()->reason;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	public function set_upload_failed($secid, $reason=1)
	{
	    $this->db->insert('upload_failures', array('secid' => $secid, 'reason' => $reason, 'date' => time()));
	}
	
	public function file_exists($id, $secid='')
	{
		$sql_where = array('file_id' => $id);
		if($secid != '')
		{
			$sql_where['secid'] = $secid;
		}
		
		$query = $this->db->select('id')->get_where('refrence', $sql_where, 1, 0);
		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get_download_link($id)
	{
		$query = $this->db->select('file_id, link_name')->get_where('refrence', array('file_id' => $id), 1, 0);
		if($query->num_rows() == 1)
		{
			$file = $query->row();
			return site_url('/files/get/'.$file->file_id.'/'.$file->link_name);
			
		}
		else
		{
			return false;
		}
	}
	
	public function get_file_object($id, $select='')
	{
		return $this->_get_file_object($id, $select);
	}
	
	public function get_file_refrence($id, $name, $select='')
	{
		if($select != '')
		{
			$this->db->select($select);
		}
		$query = $this->db->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('file_id' => $id, 'link_name' => $name), 1, 0);
		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	public function get_image_links($fid, $query=NULL)
	{
		$query = $this->db->select('file_id, link_name, server, filename, thumb')->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('file_id' => $fid, 'files.is_image' => 1), 1, 0);
		if($query->num_rows() != 1)
		{
			return false;
		}
		
		$file = $query->row();

		$links = array(
			'code_url' => site_url('/image/links/'.$file->file_id.'/'.$file->link_name),
			'img_url' => site_url('/image/show/'.$file->file_id.'/'.$file->link_name),
			'img_path' => $file->filename,
			'thumb_url' => site_url('/image/thumb/'.$file->file_id.'/'.$file->link_name, $file->server),
			'thumb_path' => $file->thumb,
			'direct_url' => site_url('/image/direct/'.$file->file_id.'/'.$file->link_name, $file->server)
		);
		return $links;
	}
	
	public function process_image($file, $type, $new, $prefix)
	{
		if(!is_dir(ROOTPATH.'/thumbstore/'.$prefix))
		{
			mkdir(ROOTPATH.'/thumbstore/'.$prefix);
		}
		
		$dimensions = getimagesize($file);
		if($dimensions[0] < 200 and $dimensions[1] < 200)
		{
			copy($file, $new);
			return;
		}
		$config = array(
			'image_library' => 'gd2',
			'create_thumb' => TRUE,
			'thumb_marker' => '',
			'source_image' => $file,
			'new_image' => $new,
			'maintain_ratio' => TRUE,
			'quality' => 90,
			'width' => 200,
			'height' => 200
		);
		$this->load->library('image_lib', $config);
		
		$this->image_lib->resize();
	}
	
	public function update_file_info($fid, $data)
	{
		$this->db->where('secid', $fid)->update('refrence', $data);
	}
	
	public function new_file($file, $uid, $user, $is_image, $server, $remote_upload, $select='', $duration = 0)
	{
		// Get MD5 Hash of uploaded file
		$md5 = md5_file($file);
		
		// Filesize of uploaded file
		$size = filesize($file);
		
		// Has file been baned?
		$banFile = $this->db->select('id')->get_where('bans', array('md5' => $md5, 'type' => 'file'));
		if($banFile->num_rows() != 0)
		{
			// YES!!!  KILL IT WITH FIRE!!!
			@unlink($file);
			log_message('debug', 'File Uploaded is banned: '.basename($file));
			$this->set_upload_failed($uid, 'banned');
			return false;
		}
		
		//is storage limit set?
		if($this->startup->group_config->storage_limit > 0)
		{
			// Does user have sufficent space to upload this file?
			if(($this->get_files_usage_space($user) + $size) > ($this->startup->group_config->storage_limit * 1024 * 1024))
			{
				// Nope!  KILL IT WITH FIRE!!!
				@unlink($file);
				
				log_message('debug', 'File Uploaded excedes allowed storage space for user');
				$this->set_upload_failed($uid, 'storage');
				return false;
			}
		}
		
		log_message('debug', "File uploaded to User Group: ".$this->startup->group_config->name);

		// Generate an awesome file_id for our new friend
		$file_id = $this->functions->get_rand_id();
		
		if($user == false or intval($user) == 0)
		{
			$user = '';
		}

		// Has the file been uploaded before?
		$real_file = $this->db->select('id, type, is_image')->get_where('files', array('md5' => $md5));
		log_message('debug', "Files_db: Has the file been uploaded before?");
		if($real_file->num_rows() == 0)
		{
			// Nope, original content!
			// Get some file information
			$type =  str_replace('.','',strtolower(strrchr(basename($file), '.')));
			$rType =  str_replace('.','',strrchr(basename($file), '.'));
			$new_image = '';
			
			// Create file storage folder if it dosent exist
			$prefix = substr($md5, 0, 2);
			$new_path = ROOTPATH.'/filestore/'.$prefix.'/'.$file_id.'.'.basename($file).'._';
			if(!is_dir(ROOTPATH.'/filestore/'.$prefix))
			{
				mkdir(ROOTPATH.'/filestore/'.$prefix);
			}
			
			// Was an image uploaded? If so, Process It! 
			if($is_image and $size <= (15 * 1024 * 1024))
			{
				$new_image = ROOTPATH.'/thumbstore/'.$prefix.'/'.$file_id.'.'.basename($file);
				$base = basename($file);
				$base = substr($base,0,(strlen($base) - (1+strlen($type))));
				$base = $base.'_thumb.'.$rType;
				$new_image = ROOTPATH.'/thumbstore/'.$prefix.'/'.$file_id.'.'.$base;
				$this->process_image($file, $type, $new_image, $prefix);
			}
			
			// Move the file into its new home
			if(is_uploaded_file($file))
			{
				log_message('debug', "Files_db: move_uploaded_file $file to $new_path");
				move_uploaded_file($file, $new_path);
			}
			else
			{
				log_message('debug', "Files_db: rename $file to $new_path");
				rename($file, $new_path);
			}
			
			// Create the `files` entry to store our file
			$data = array(
				'filename' => $new_path,
				'size' => $size,
				'md5' => $md5,
				'status' => 1,
				'is_image' => $is_image,
				'thumb' => $new_image,
				'type' => $type,
				'server' => $server,
				'prefix' => $prefix
			);
			$this->db->insert('files', $data); unset($data);
			$file_link_id = $this->db->insert_id();
			log_message('debug', "Files_db: insert files: $file_link_id");
		}
		else
		{
			// Oops, we have a dupe. Lets save the user some trouble and not tell them, mmmk?
			log_message('debug', "Files_db: Oops, we have a dupe. Lets save the user some trouble and not tell them, mmmk?");
			$file_obj = $real_file->row();
			$type = $file_obj->type;
			$file_link_id = $file_obj->id;
			$is_image = $file_obj->is_image;
			@unlink($file);
		}
		
		$link_name = basename($file);
		if(substr($link_name, -2) == '._')
		{
		    $link_name = substr($link_name, 0, (strlen($link_name)-2));
		}
		$link_name = url_title($link_name);
		
		// Create an entry in the refrence table to this new upload
		$data = array(
			'o_filename' => basename($file), 
			'file_id' => $file_id,
			'link_id' => $file_link_id,
			'status' => 1,
			'type' => $type,
			'is_image' => $is_image,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'secid' => $uid,
			'user' => $user,
			'link_name' => $link_name,
			'downloads' => 0,
			'last_download' => time(),
			'direct_bw' => 0,
			'remote' => $remote_upload,
			'time' => time()
		);
		log_message('debug', "Files_db: Insert refrence");
		$this->db->insert('refrence', $data);
		return $file_id;
	}
	
	//------------------------
	// File Delete 
	//------------------------
	
	public function delete_file($id, $secid)
	{
		$fid = $this->db->select('link_id')->get_where('refrence', array('file_id' => $id, 'secid' => $secid));
		$file = $fid->row();
		
		$files = $this->db->get_where('refrence', array('link_id' => $file->link_id))->num_rows();
		if($files == 1)
		{
			$realfile = $this->db->get_where('files', array('id' => $file->link_id))->row();
			$this->db->delete('files', array('id' => $realfile->id));
		}
		$this->db->delete('refrence', array('file_id' => $id));
	}
	
	public function delete_file_user($id, $user)
	{
		if($this->db->select('id')->get_where('refrence', array('file_id' => $id, 'user' => $user))->num_rows() == 1)
		{
			$fid = $this->db->select('link_id')->get_where('refrence', array('file_id' => $id, 'user' => $user));
			$file = $fid->row();
			
			$files = $this->db->get_where('refrence', array('link_id' => $file->link_id))->num_rows();
			if($files == 1)
			{
				$realfile = $this->db->get_where('files', array('id' => $file->link_id))->row();
				$this->db->delete('files', array('id' => $realfile->id));
			}
			$this->db->delete('refrence', array('file_id' => $id));
		}
	}
	
	public function delete_file_admin($id)
	{
		$fid = $this->db->select('link_id')->join('files', 'refrence.link_id = files.id')->get_where('refrence', array('file_id' => $id));
		if($fid->num_rows() >= 1)
		{
			$file = $fid->row();
			
			$files = $this->db->get_where('refrence', array('link_id' => $file->link_id))->num_rows();
			if($files == 1)
			{
				$realfile = $this->db->get_where('files', array('id' => $file->link_id))->row();
				$this->db->delete('files', array('id' => $realfile->id));
			}
			$this->db->delete('refrence', array('file_id' => $id));
		}
	}
	
	public function ban_file_admin($id)
	{
		$file = $this->_get_file_object($id);
		if(!$file)
		{
			echo $file;
			return false;	
		}
		$this->db->delete('refrence', array('link_id' => $file->link_id));	
		$this->db->delete('files', array('md5' => $file->md5));
		$this->db->insert('bans', array('md5' => $file->md5, 'name' => $file->o_filename, 'time' => time(), 'type' => 'file'));
	}
	
	public function add_to_downloads($id)
	{
		$fid = $this->db->select('downloads')->get_where('refrence', array('file_id' => $id));
		$file = $fid->row();
		
		$data = array(
		   'downloads' => $file->downloads + 1,
		   'last_download' => time()
		);

		$this->db->where('file_id', $id);
		$this->db->update('refrence', $data); 
	}
	
	public function edit_premium_bandwith($id, $ammount, $previous, $plus=false)
	{
		if($plus)
		{
			$data['direct_bw'] = ($previous + $ammount);
		}
		else
		{
			$data['direct_bw'] = ($previous - $ammount);
		}
		
		if($data['direct_bw'] < 0)
		{
			$data['direct_bw'] = 0;
			$data['direct'] = 0;
		}

		$this->db->where('file_id', $id);
		$this->db->update('refrence', $data); 
	}
	
	private function _get_file_object($id, $select='', $where=array())
	{
		if($select != '')
		{
			$this->db->select($select);
		}
		
		$sql_where = array_merge($where,  array('file_id' => $id));
		
		$query = $this->db->join('files', 'refrence.link_id = files.id')->get_where('refrence', $sql_where, 1, 0);
		if($query->num_rows() >= 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
}

/* End of file Files_db.php */
/* Location: ./application/models/files/Files_db.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @license     http://xtrafile.com/docs/license
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

/**
 * XtraUpload Image Controller
 *
 * @package     XtraUpload
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Matthew Glinski
 * @author      momo-i
 * @link        http://xtrafile.com/docs
 */
class Image extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('files/files_db');
        $this->load->library('functions');
    }

    public function index()
    {
        redirect('home');
    }

    public function show($id, $name)
    {
        $links = $this->files_db->get_image_links($id, $name);
        if(!$links)
        {
            redirect('home');
        }
        $links['down'] = $this->files_db->get_download_link($id);
        $links['file'] = $this->files_db->get_file_object($id);
        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('View Image').' '.$this->startup->site_config->title_separator.' '.$name));
        $this->load->view($this->startup->skin.'/image/home', $links);
        $this->load->view($this->startup->skin.'/footer');
    }

    public function links($id, $name)
    {
        $links = $this->files_db->get_image_links($id, $name);
        if(!$links)
        {
            redirect('home');
        }

        if($this->startup->site_config->no_php_images)
        {
            $links['direct_url'] = base_url().$links['img_url'];
            $links['thumb_url'] = base_url().$links['thumb_url'];
        }

        $links['down'] = $this->files_db->get_download_link($id);

        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('Forum/BBCode Image Links')));
        $this->load->view($this->startup->skin.'/image/links', $links);
        $this->load->view($this->startup->skin.'/footer');
    }

    public function thumb($id, $name)
    {
        $file = $this->files_db->get_file_object($id);
        $type = strtolower($file->type);

        if(! (bool)$file->is_image)
        {
            show_404();
        }

        if($type == "jpg")
        {
            $type == 'jpeg';
        }

        header("Content-type: image/".$type);
        echo file_get_contents($file->thumb);
    }

    public function direct($id, $name)
    {
        $file = $this->files_db->get_file_object($id);
        $this->files_db->add_to_downloads($id);
        $type = strtolower($file->type);

        if(! (bool)$file->is_image)
        {
            show_404();
        }

        if($type == "jpg")
        {
            $type == 'jpeg';
        }

        header("Content-type: image/".$type);
        echo file_get_contents($file->filename);
    }

    public function gallery($id)
    {
        $data['gall'] = $this->db->get_where('gallery', array('g_id' => $id))->row();
        $data['gall_imgs'] = $this->db->get_where('g_items', array('gid' => $id));
        $data['id'] = $id;
        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('View Image Gallery').' '.$this->startup->site_config->title_separator.' '.$data['gall']->name));
        $this->load->view($this->startup->skin.'/image/gallery/view', $data);
        $this->load->view($this->startup->skin.'/footer');
    }

    public function create_gallery()
    {
        $this->load->model('user_access');
        $data['files'] = $this->files_db->get_images();

        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('Create Image Gallery')));
        $this->load->view($this->startup->skin.'/image/gallery/create', $data);
        $this->load->view($this->startup->skin.'/footer');
    }

    public function process_new_gallery()
    {
        $this->load->model('user_access');
        if(!is_array($this->input->post('files')) OR !$this->input->post('name'))
        {
            redirect('image/create_gallery');
        }

        $name = $this->input->post('name');
        $desc = $this->input->post('desc');
        $gid = $this->functions->get_rand_id(8);
        $data['gid'] = $gid;
        $this->db->insert('gallery', array('name' => $name, 'descr' => $desc, 'g_id' => $gid));

        $files = $this->input->post('files');
        foreach($files as $file)
        {
            $file_obj = $this->files_db->get_file_object($file);
            $image = $this->files_db->get_image_links($file_obj->file_id);

            $this->db->insert('g_items', array('gid' => $gid, 'thumb' => $image['thumb_url'], 'direct' => $image['direct_url'], 'view' => $image['img_url'], 'fid' => $file_obj->file_id));
        }

        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('Create Image Gallery').' '.$this->startup->site_config->title_separator.' '.lang('Done')));
        $this->load->view($this->startup->skin.'/image/gallery/done', $data);
        $this->load->view($this->startup->skin.'/footer');
    }

    public function edit_gallery($id)
    {
        $this->load->model('user_access');
		$data['gall'] = $this->db->get_where('gallery', array('g_id' => $id))->row();
		$data['gall_imgs'] = $this->db->get_where('g_items', array('gid' => $id));
		$data['id'] = $id;

        $this->load->view($this->startup->skin.'/header', array('header_title' => lang('Edit Image Gallery')));
        $this->load->view($this->startup->skin.'/image/gallery/edit', $data);
        $this->load->view($this->startup->skin.'/footer');
    }

	public function process_edit_gallery()
	{
        $this->load->model('user_access');
        if(!is_array($this->input->post('files')) OR !$this->input->post('name'))
        {
            redirect('image/edit_gallery');
        }
	}

}

/* End of file image.php */
/* Location: ./application/controllers/image.php */

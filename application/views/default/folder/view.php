        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/folder_32.png" class="nb" alt="">
          <?php echo lang('File Folder'); ?> 
        </h2>
        <h3><?php echo lang('Name:'); ?> <?php echo $folder->name; ?></h3>
        <pre><code><?php echo $folder->descr; ?></code></pre>
        <div id="folder">
          <table border="0" style="width:100%" id="file_list_table">
            <tr>
              <th class="align-left"><?php echo lang('File Name'); ?></th>
              <th><?php echo lang('Size'); ?></th>
            </tr>
<?php
foreach ($folder_files->result() as $fileRef):
	$file = $this->files_db->_get_file_object($fileRef->file_id);
	$links = $this->files_db->get_links('', $file);
?>
            <tr>
              <td>
                <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
                <?php echo anchor('files/get/'.$file->file_id.'/'.$file->link_name, $file->o_filename, array('target'=>'_blank')); ?> 
              </td>
              <td><?php echo $this->functions->get_filesize_prefix($file->size); ?></td>
            </tr>
<? endforeach; ?>
          </table>
        </div>

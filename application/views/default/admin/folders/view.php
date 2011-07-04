        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/folder_32.png" class="nb" alt="">
          <?php echo lang('Folder Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-right">
            <?php echo generate_link_button(lang('Search'), site_url('admin/files/search'), base_url().'img/icons/search_16.png', NULL); ?> 
          </div>
        </div>
        <p style=" clear:both;"></p>
        <table class="special" border="0" id="file_list_table"cellspacing="0" style="width:98%">
          <tr>
            <th class="align-left"><?php echo lang('Folder name'); ?></th>
            <th><?php echo lang('# Files'); ?></th>
            <th><?php echo lang('Created'); ?></th>
            <th><?php echo lang('Actions'); ?></th>
          </tr>
<?php
foreach ($files->result() as $file)
{
	$links = $this->files_db->get_links('', $file);
?>            
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <a href="<?php echo site_url('folders/get/'.$file->file_id.'/'.$file->link_name); ?>" target="_blank">
                <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
                <?php echo $this->functions->elipsis($file->o_filename, 10); ?>
              </a>
            </td>
            <td>
              <?php echo $this->functions->get_filesize_prefix($file->size); ?> 
            </td>
            <td>
              <?php echo unix_to_small($file->time); ?>
            </td>
            <td>
              <a title="<?php echo lang('Edit This File'); ?>" href="<?=site_url('admin/files/edit/'.$file->file_id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>">
              </a>
              <a title="<?php echo lang('Delete This File'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to delete this file?'); ?>')" href="<?php echo site_url('admin/files/delete/'.$file->file_id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
              </a>
              <a title="<?php echo lang('Ban This File'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to ban this file?'); ?>')" href="<?php echo site_url('admin/files/ban/'.$file->file_id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/lock_16.png" class="nb" alt="<?php echo lang('Ban'); ?>">
              </a>
            </td>
          </tr>
<?php
}
?>
        </table>

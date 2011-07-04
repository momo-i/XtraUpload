        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/documents_32.png" class="nb" alt="">
          <?php echo lang('File Manager - Search Result'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <form action="<?php echo site_url('admin/files/'); ?>" id="userAdmin" method="post" style="padding:0; margin:0; border:0;">
          <div id="massActions" style="clear:both; padding-top:4px;">
            <div class="float-left">
              <?php echo generate_link_button(lang('Delete'), 'javascript:;', base_url().'img/icons/close_16.png', 'red', array('onclick' => 'delete_submit()')); ?>
              <?php echo generate_link_button(lang('Ban'), 'javascript:;', base_url().'img/icons/lock_16.png', NULL, array('onclick' => 'ban_submit()')); ?>
            </div>
            <div class="float-right">
              <?=generate_link_button(lang('Search'), site_url('admin/files/search'), base_url().'img/icons/search_16.png', NULL); ?>
              <?=generate_link_button(lang('All'), site_url('admin/files/view'), base_url().'img/icons/documents_16.png', NULL); ?>
            </div>
          </div>
          <h3 style="clear:both">
            <?php echo lang('Search Query'); ?>: "<?php echo $query; ?>" <br>
            <?php echo lang('Number of results'); ?>: <?php echo $res_num; ?>
          </h3>
          <table class="special" border="0" id="file_list_table"cellspacing="0" style="width:98%">
            <tr>
              <th>
                <div align="center">
                  <input type="checkbox" onchange="switch_checkboxes(this.checked)">
                </div>
              </th>
              <th class="align-left">
                <a href="javascript:;" onclick="<?php echo get_sort_link('o_filename', $sort, $direction); ?>">
                  <?php echo lang('File name'); ?><?php echo get_sort_arrow('o_filename', $sort, $direction); ?>
                </a>
              </th>
              <th>
                <a href="javascript:;" onclick="<?php echo get_sort_link('size', $sort, $direction); ?>">
                  <?php echo lang('Size'); ?><?php echo get_sort_arrow('size', $sort, $direction); ?>
                </a>
              </th>
              <th>
                <a href="javascript:;" onclick="<?php echo get_sort_link('time', $sort, $direction): ?>">
                  <?php echo lang('Date'); ?><?php echo get_sort_arrow('time', $sort, $direction); ?>
                </a>
              </th>
              <th><?php echo lang('Actions'); ?></th>
            </tr>
<?php
foreach ($files->result() as $file)
{
	$links = $this->files_db->get_links('', $file);
?>            
            <tr <?php echo alternator('class="odd"', 'class="even"')?>>
              <td>
                <div align="center">
                  <input type="checkbox" id="check-<?php echo $file->id; ?>" name="files[]" value="<?php echo $file->file_id; ?>">
                </div>
              </td>
              <td>
                <a href="<?php echo site_url('files/get/'.$file->file_id.'/'.$file->link_name)?>" title="<?php echo $file->o_filename; ?>" target="_blank">
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
                <a title="<?php echo lang('Delete This File'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to delete this file?'); ?>')" href="<?php echo site_url('admin/files/delete/'.$file->file_id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
                </a>
                <a title="<?php echo lang('Ban This File'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to ban this file?'); ?>')" href="<?php echo site_url('admin/files/ban/'.$file->file_id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/clock_16.png" class="nb" alt="<?php echo lang('Ban'); ?>">
                </a>
              </td>
            </tr>
<?php 
}
?>
          </table>
        </form>
        <br>
        <div style="float:right">
          <form action="<?php echo site_url('admin/files/search_count/'.$query); ?>" method="post" style="padding:0; margin:0; border:0;">
            <?php echo lang('Results'); ?>: <input type="text" size="6" maxlength="6" name="file_count" value="<?php echo $per_page; ?>">
          </form>
        </div>
        <?php echo $pagination; ?> 
        <form style="display:none" method="post" id="sort_form" action="<?php echo site_url('admin/files/sort'); ?>">
          <input type="hidden" id="formS" name="sort">
          <input type="hidden"id="formD" name="direction">
        </form>
        <script type="text/javascript">
          //<![CDATA[
          function ban_submit()
          {
            if(confirm('<?php echo lang('Are you sure you want to ban these files?'); ?>'))
            {
              $('#userAdmin').attr('action', "<?php echo site_url('admin/files/massBan/'.$query); ?>");
              $('#userAdmin').submit();
            }
          }
          function delete_submit()
          {
            if(confirm('<?php echo lang('Are you sure you want to delete these files?'); ?>'))
            {
              $('#userAdmin').attr('action', "<?php echo site_url('admin/files/mass_delete/'.$query); ?>");
              $('#userAdmin').submit();
            }
          }
          function switch_checkboxes()
          {
            $('input[@type=checkbox]').each( function() 
            {
              this.checked = !this.checked;
            });
          }
          function switch_checkbox(id)
          {
            $('#'+id).each( function() 
            {
              this.checked = !this.checked;
            });
          }
          //--]]>
        </script>

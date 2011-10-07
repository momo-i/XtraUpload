        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/manage-files_32.png" class="nb" alt="">
          <?php echo lang('Manage Files'); ?> 
        </h2>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-left">
            <?php echo generate_link_button('Delete', 'javascript:;', base_url().'img/icons/close_16.png', 'red', array('onclick' => 'deleteSubmit()')); ?>
          </div>
        </div>
        <p style=" clear:both;"></p>
        <span style="display:none" class="info" id="edit_alert">
          <?php echo lang('Your changes have been saved.'); ?> 
        </span>
        <?php echo $flash_message; ?> 
<?php if($this->startup->group_config->storage_limit > '0') { ?>
        <span class="info">
          <strong><?php echo lang('Your account is limited by storage space:'); ?></strong><br>
          <?php printf(lang('You have %d of %d MB remaining.'), '<strong>'.$this->functions->get_filesize_prefix(($this->startup->group_config->storage_limit * 1024 * 1024) - $this->files_db->get_files_usage_space()).'</strong>', '<strong>'.$this->startup->group_config->storage_limit.'</strong>'); ?>
        </span>
<?php } //endif; ?><?php $this->load->helper('string'); ?>
        <form action="<?php echo site_url('files/manage'); ?>" id="userAdmin" method="post" style="padding:0; margin:0; border:0;">
          <table border="0" style="width:95%" id="file_list_table">
            <tr>
              <th style="width:20px">
                <div style="text-align:center"><input type="checkbox" id="switch_box" onchange="switchCheckboxes(this.checked)"></div>
              </th>
              <th><?php echo lang('File name'); ?></th>
              <th><?php echo lang('Size'); ?></th>
              <th><?php echo lang('Downloads'); ?></th>
              <th style="width:80px"><?php echo lang('Actions'); ?></th>
            </tr>
<?php
$i=0;
foreach ($files->result() as $file) {
	$id = $file->id;
	$link = $this->files_db->get_links($file->secid);
?>
            <tr id="<?php echo $file->file_id; ?>" <?php echo alternator('class="odd"', 'class="even"'); ?>>
              <td>
                <div align="center">
                  <input type="checkbox" id="check-<?php echo $file->id; ?>" onchange="manageCheckboxes()" name="files[]" value="<?php echo $file->file_id; ?>">
                </div>
              </td>
              <td>
                <a href='<?php echo $link['down']; ?>' rel="external">
                  <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
                  <?php echo $this->functions->elipsis($file->o_filename, 10); ?>
                </a>
              </td>
              <td>
                <?php echo $this->functions->get_filesize_prefix($file->size); ?> 
              </td>
              <td>
                <?php echo intval($file->downloads); ?> 
              </td>
              <td>
                <a href='javascript:;' onclick='$("#<?php echo $file->file_id; ?>-details").toggle()'><img src="<?php echo base_url(); ?>img/icons/link_16.png"  title="<?php echo lang('Show/Hide Links'); ?>" class="nb"></a>
                <a href='javascript:;' onclick='$("#<?php echo $file->id; ?>-edit").toggle()'><img src="<?php echo base_url(); ?>img/icons/edit_16.png"  title="<?php echo lang('Edit'); ?>" class="nb"></a>
                <a href="<?php echo $link['del']; ?>" onclick="return confirm('<?php echo lang('Are you SURE you want to do this?'); ?>');"><img src="<?php echo base_url(); ?>img/icons/close_16.png" title="<?php echo lang('Delete file'); ?>" class="nb"></a>
              </td>
            </tr>
            <tr class="details" style="display:none; border-top:none;" id="<?php echo $file->file_id; ?>-details">
              <td colspan="5" id="<?php echo $file->file_id; ?>-details-inner">
                <?php echo lang('Download Link'); ?>: <input class="down_link" readonly="readonly" type="text" size="65" value="<?php echo $link['down']; ?>" onfocus="this.select()" onclick="this.select()" ondblclick="this.select()"><br>
                <?php echo lang('Delete Link:'); ?> <a href="<?php echo $link['del']; ?>"><?php echo $link['del']; ?></a>
<?php if(isset($link['img'])) { ?>
                <br><?php echo lang('Image Links'); ?><a href="<?php echo $link['img']; ?>"><?php echo $link['img']; ?></a>
<?php } // endif; ?>
               </td>
            </tr>
            <tr class="details" style="display:none; border-top:none;" id="<?php echo $file->id; ?>-edit">
              <td colspan="5" id="<?php echo $file->file_id; ?>-edit-inner">
                <input name="<?php echo $file->id; ?>_fid" id="<?php echo $file->id; ?>_fid" value="<?php echo $file->secid; ?>" type="hidden">
                <span class="float-right">
                  <label for="<?php echo $file->id; ?>_desc"><?php echo lang('Description'); ?></label>
                  <textarea name="<?php echo $file->id; ?>_desc" id="<?php echo $file->id; ?>_desc" cols="30" style="height:180px" rows="2"><?php echo $file->descr; ?></textarea>
                </span>
                <label for="<?php echo $file->id; ?>_pass"><?php echo lang('Password'); ?></label>
                  <input name="<?php echo $file->id; ?>_pass" id="<?php echo $file->id; ?>_pass" value="<?php echo $file->password; ?>" size="35" maxlength="32" type="text"><br>
                <label for="<?php echo $file->id; ?>_tags"><?php echo lang('Tags (seperated by commas)'); ?></label>
                <input name="<?php echo $file->id; ?>_tags" id="<?php echo $file->id; ?>_tags" value="<?php echo $file->tags; ?>" size="35" maxlength="200" type="text"><br>
                <label for="<?php echo $file->id; ?>_feat"><?php echo lang('Feature This File?'); ?></label>
                <input name="<?php echo $file->id; ?>_feat" id="<?php echo $file->id; ?>_feat" <?php if($file->feature) { ?> checked="checked"<? } ?> type="checkbox" value="1"> <?php echo lang('Yes'); ?><br><br>
                <?php echo generate_link_button(lang('Save Changes'), 'javascript:;', base_url().'img/icons/ok_16.png', 'green', array('onclick' => 'editFileProps(\''.$file->id.'\');')); ?> 
                <?php echo generate_link_button(lang('Cancel Edit'), 'javascript:;', base_url().'img/icons/close_16.png', 'red', array('onclick' => '$(\'#'.$file->id.'-edit\').hide();')); ?><br><br>
              </td>
            </tr>
<?php
	$i++;
} //endforeach;
?>
          </table>
        </form>
        <?php echo $pagination; ?>
        <script type="text/javascript">
          //<![CDATA[
          function editFileProps(id)
          {
            if($('#'+id+'_feat').is(':checked'))
            {
              var fFeatured = 1;
            }
            else
            {
              var fFeatured = 0;
            }
            var fDesc = $('#'+id+'_desc').val();
            var fPass = $('#'+id+'_pass').val();
            var fTags = $('#'+id+'_tags').val();
            var curFileId = $('#'+id+'_fid').val();
            $.post(
              '<?php echo site_url('upload/file_upload_props'); ?>',
              {
                fid: curFileId,
                password: fPass,
                desc: fDesc,
                tags: fTags,
                featured: fFeatured
              }
            );
            $('#edit_alert').show();
            $("#"+id+"-edit").hide();
            setTimeout('$(\'#edit_alert\').slideUp("normal")', 1500)
          }
          function deleteSubmit()
          {
            if(confirm('<?php echo lang('Are you sure you want to delete these files?'); ?>'))
            {
              $('#userAdmin').attr('action', "<?php echo site_url('files/mass_delete'); ?>");
              $('#userAdmin').submit();
            }
          }
          function switchCheckboxes(checked)
          {
            var the_id = this.id;
            if(checked == false)
            {
              $("input:checkbox").each( function()
              {
                if(this.id != the_id)
                {
                  this.checked = false;
                }
              });
            }
            else
            {
              $("input:checkbox").each( function()
              {
                if(this.id != the_id)
                {
                  this.checked = true;
                }
              });
            }
          }
          function manageCheckboxes()
          {
            var boxes = [];
            var is_all_checked = true;
            var i = 0;
            // get all main checkboxes and manage them muwahhahh!!!!
            $("input[id^='check-']:checkbox").each( function()
            {
              if(this.id != 'switch_box' && is_all_checked == true)
              {
                if(this.checked === false)
                {
                  is_all_checked = false;
                }
              }
            });
            if(is_all_checked)
            {
              $('#switch_box').get(0).checked = true;
            }
            else
            {
              $('#switch_box').get(0).checked = false;
            }
          }
          function switchCheckbox(id)
          {
            $('#'+id).each( function()
            {
              $(this).get(0).checked = !$(this).get(0).checked;
            });
          }
          function sort_form(col, dir)
          {
            $('#formS').val(col);
            $('#formD').val(dir);
            $('#sort_form').get(0).submit();
          }
          //--]]>
        </script>

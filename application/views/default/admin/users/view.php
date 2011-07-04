        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('Users - Manage'); ?> 
        </h2>
        <div class="users">
          <?php echo $flash_message; ?>
          <form action="<?php echo site_url('admin/user/')?>" id="userAdmin" method="post" style="padding:0; margin:0; border:0;">
            <div id="massActions" style="clear:both; padding-top:4px;">
              <div class="float-left">
                <?php echo generate_link_button(lang('Delete'), 'javascript:;', base_url().'img/icons/close_16.png', 'red', array('onclick' => 'delete_submit()')); ?>
              </div>
              <div class="float-right">
                <?php echo generate_link_button(lang('Search'), site_url('admin/user/search'), base_url().'img/icons/search_16.png', NULL); ?>
                <?php echo generate_link_button(lang('Add'), site_url('admin/user/add'), base_url().'img/icons/add_16.png', 'green'); ?>
              </div>
            </div>
            <p style=" clear:both;"></p>
            <table class="special" border="0" cellpadding="4" cellspacing="0" style="width:98%;">
              <tr>
                <th><div align="center"><input type="checkbox" onchange="switch_checkboxes(this.checked)"></div></th>
                <th>
                  <div align="center">
                    <a href="javascript:;" onclick="<?php echo get_sort_link('id', $sort, $direction); ?>">
                      <?php echo lang('ID'); ?> #<?php echo get_sort_arrow('id', $sort, $direction); ?>
                    </a>
                  </div>
                </th>
                <th>
                  <div align="center">
                    <a href="javascript:;" onclick="<?php echo get_sort_link('username', $sort, $direction); ?>">
                      <?php echo lang('Name'); ?><?php echo get_sort_arrow('username', $sort, $direction); ?>
                    </a>
                  </div>
                </th>
                <th>
                  <div align="center">
                    <a href="javascript:;" onclick="<?php echo get_sort_link('email', $sort, $direction); ?>">
                      <?php echo lang('Email'); ?><?php echo get_sort_arrow('email', $sort, $direction); ?>
                    </a>
                  </div>
                </th>
                <th><div align="center"><?php echo lang('Space'); ?></div></th>
                <th><div align="center"><?php echo lang('Actions'); ?></div></th>
              </tr>
<?php
foreach ($users->result() as $user)
{
?>
              <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
                <td><div align="center"><input type="checkbox" id="check-<?php echo $user->id; ?>" name="users[]" value="<?php echo $user->id; ?>"></div></td>
                <td><div align="center"><?php echo $user->id; ?></div></td>
                <td><div align="center"><?php echo $user->username; ?></div></td>
                <td><div align="center"><?php echo $user->email; ?></div></td>
                <td><div align="center"><?php echo $this->functions->get_filesize_prefix($this->files_db->get_files_usage_space($user->id)); ?></div></td>
                <td>
                  <div align="center">
<?php
	if($user->status == 0 and $user->id != 1)
	{
?>
                    <a href="<?php echo site_url('admin/user/turn_on/'.$user->id); ?>">
                      <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="<?php echo lang('Activate User'); ?>" title="<?php echo lang('Activate User'); ?>">
                    </a>
<?php
	}
	elseif($user->id != 1)
	{
?>
                    <a href="<?php echo site_url('admin/user/turn_off/'.$user->id); ?>">
                      <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="<?php echo lang('Deactivate User'); ?>" title="<?php echo lang('Deactivate User'); ?>">
                    </a>
<?php
	}
?>
                    <a href="<?php echo site_url('admin/user/edit/'.$user->id); ?>">
                      <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>" title="<?php echo lang('Edit'); ?>">
                    </a>
<?php
	if($user->id != 1)
	{
?>
                    <a href="<?php echo site_url('admin/user/delete/'.$user->id); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to delete this user?'); ?>')">
                      <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>" title="<?php echo lang('Delete'); ?>">
                    </a>
<?php
	}
?>
                  </div>
                </td>
              </tr>
<?php
}
?>
            </table>
          </form>
          <div style="float:right">
            <form action="<?php echo site_url('admin/user/count'); ?>" method="post" style="padding:0; margin:0; border:0;">
              <?php echo lang('Results'); ?>: <input type="text" size="6" maxlength="6" name="user_count" value="<?php echo $per_page; ?>">
            </form>
          </div>
          <?php echo $pagination; ?> 
          <div class="clearer"></div>
          <form style="display:none" method="post" id="sort_form" action="<?php echo site_url('admin/user/sort'); ?>">
            <input type="hidden" id="formS" name="sort">
            <input type="hidden"id="formD" name="direction">
          </form>
          <script type="text/javascript">
            //<![CDATA[
            function delete_submit()
            {
              if(confirm('<?php echo lang('Are you sure you want to delete these users?'); ?>'))
              {
                $('#userAdmin').attr('action', "<?php echo site_url('admin/user/mass_delete'); ?>");
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
            function sort_form(col, dir)
            {
              $('#formS').val(col);
              $('#formD').val(dir);
              $('#sort_form').get(0).submit();
            }
            //--]]>
          </script>
        </div>

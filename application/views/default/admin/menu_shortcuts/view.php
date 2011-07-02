        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/sticky_32.png" class="nb" alt="">
          <?php echo lang('Admin Shortcut Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <form action="<?php echo site_url('admin/menu_shortcuts/view'); ?>" id="userAdmin" method="post" style="padding:0; margin:0; border:0;">
          <div id="massActions" style="clear:both; padding-top:4px;">
            <div class="float-right">
              <?php echo generate_link_button(lang('Add'), site_url('admin/menu_shortcuts/add'), base_url().'img/icons/add_16.png', 'green')?>
            </div>
          </div>
          <p style=" clear:both;"></p>
          <table class="special" border="0" id="shortcut_list_table"cellspacing="0" style="width:98%">
            <tr>
              <th class="align-left"><?php echo lang('Link Name'); ?></th>
              <th><?php echo lang('Link Locaton'); ?></th>
              <th><?php echo lang('Actions'); ?></th>
            </tr>
<?php
foreach ($shortcuts->result() as $shortcut)
{
?>            
            <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
              <td><?php echo $shortcut->title; ?></td>
              <td>
                <a href="<?php echo site_url($shortcut->link); ?>" rel="external">
                  <?php echo site_url($shortcut->link); ?> 
                </a>
              </td>
              <td>
<?php
	if($shortcut->status == 1)
	{
?>
                <a title="<?php echo lang('Turn Off Shortcut'); ?>" href="<?php echo site_url('admin/menu_shortcuts/turn_off/'.$shortcut->id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="<?php echo lang('Turn Off'); ?>">
                </a>
<?php
	}
	else
	{
?>
                <a title="<?php echo lang('Turn On Shortcut'); ?>" href="<?php echo site_url('admin/menu_shortcuts/turn_on/'.$shortcut->id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="<?php echo lang('Turn On'); ?>">
                </a>
<?php
	}
?>
                <a title="<?php echo lang('Edit This Shortcut'); ?>" href="<?php echo site_url('admin/menu_shortcuts/edit/'.$shortcut->id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>">
                </a>
                <a title="<?php echo lang('Delete This Shortcut'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to delete this shortcut?'); ?>')" href="<?php echo site_url('admin/menu_shortcuts/delete/'.$shortcut->id); ?>">
                  <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
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
          <form action="<?php echo site_url('admin/files/count'); ?>" method="post" style="padding:0; margin:0; border:0;">
            <?php echo lang('Results Per Page'); ?>: <input type="text" size="6" maxlength="6" name="file_count" value="<?php echo $per_page; ?>">
          </form>
        </div>
        <?php echo $pagination; ?>
        <script type="text/javascript" charset="utf-8">
          //<![CDATA[
          function submitOrderChange()
          {
            //TODO: add javascript actions to drag and drop sort these shortcuts
            //$()
          }
          //--]]>
        </script>

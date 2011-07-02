        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/server_32.png" class="nb" alt="">
          <?php echo lang('Edit Server'); ?> 
        </h2>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-left">
            <?php echo generate_link_button(lang('Manage Servers'), site_url('admin/server/view'), base_url().'img/icons/back_16.png')?>
          </div>
        </div>
        <div style="clear:both"></div>
        <form action="<?php echo site_url('/admin/server/edit/'.$id); ?>" method="post"> 
          <input type="hidden" name="valid" value="true" size="50">
          <h3><?php echo lang('Server Details'); ?></h3>
          <p>
            <label><?php echo lang('Server Name'); ?></label>
            <input type="text" name="name" value="<?php echo $server->name; ?>" size="50"><br>
            <label><?php echo lang('Server URL'); ?></label>
            <input type="text" name="url" value="<?php echo $server->url; ?>" size="50"><br>
            <label><?php echo lang('Is Active?'); ?></label>
<?php
$checked = ($server->status) ? ' checked="checked"' : '';
?>
            <input type="checkbox" name="status"<?php echo $checked ?> value="1"> <?php echo lang('Yes'); ?><br><br>
            <?php echo generate_submit_button(lang('Submit Changes'), base_url().'img/icons/ok_16.png'); ?><br>
          </p>
        </form>

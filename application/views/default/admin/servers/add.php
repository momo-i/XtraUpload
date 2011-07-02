        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/server_32.png" class="nb" alt="">
          <?php echo lang('Add New Server'); ?> 
        </h2>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-left">
            <?php echo generate_link_button(lang('Manage Servers'), site_url('admin/server/view'), base_url().'img/icons/back_16.png'); ?>
          </div>
        </div>
        <div style="clear:both"></div>
        <form action="<?php echo site_url('/admin/server/add'); ?>" method="post"> 
          <input type="hidden" name="valid" value="true" size="50">
          <h3><?php echo lang('Add New Server'); ?></h3>
          <p>
            <label><?php echo lang('Server Name'); ?></label>
            <input type="text" name="name" value="" size="50"><br>
            <label><?php echo lang('Server URL'); ?></label>
            <input type="text" name="url" value="" size="50"><br>
            <label><?php echo lang('Is Active?'); ?></label>
            <input type="checkbox" name="status" value="1"> <?php echo lang('Yes'); ?><br><br>
            <?php echo generate_submit_button(lang('Add Server'), base_url().'img/icons/add_16.png')?><br>
          </p>
        </form>

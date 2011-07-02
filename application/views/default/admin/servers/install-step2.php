        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/server_32.png" class="nb" alt="">
          <?php echo lang('Install Server Software'); ?> 
        </h2>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-left">
            <?php echo generate_link_button(lang('Manage Servers'), site_url('admin/server/view'), base_url().'img/icons/back_16.png'); ?>
          </div>
        </div>
        <div style="clear:both"></div>
        <h3><?php echo lang('Install Complete!'); ?></h3>
        <p><?php echo lang('The server file have been installed on the remote server. If you notice any problems with the install please double check the installer using the server install guid in the docs. Please use the above button to go back to the server manager.'); ?></p>

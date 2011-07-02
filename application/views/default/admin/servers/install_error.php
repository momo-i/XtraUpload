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
        <h3 style="padding-top:0">
          <img src="<?php echo base_url(); ?>img/icons/warning_24.png" class="nb" alt="">
          <span style="color:#FF0000; text-decoration:underline"><?php echo lang('Important Information'); ?></span>
          <img src="<?php echo base_url(); ?>img/icons/warning_24.png" class="nb" alt="">
        </h3>
        <p>
          <span style="font-weight:bold; text-decoration:underline; color:#FF0000">
            <?php echo lang('You do not have the server package downloaded!'); ?>
          </span><br>
          <?php printf(lang('Please download the latest server package from the %s.'), anchor('http://xtrafile.com/files/', lang('XtraFile.com Download Page'), array('rel' => 'external'))); ?><br>
          <?php echo lang('Once you have downloaded the package follow these steps:'); ?>
          <ol>
            <li><?php echo lang('Extract the package'); ?></li>
            <li><?php echo lang('Login to this server via FTP'); ?></li>
            <li><?php printf(lang('Create a new folder in the xu2 root folder, name it %sserver_package%s'), '<strong>', '</strong>'); ?></li>
            <li><?php echo lang('Upload the extracted files to that folder'); ?></li>
            <li><?php echo lang('Refresh this page'); ?></li>
          </ol>
          <br>
        </p>

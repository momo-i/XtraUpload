        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/tools_32.png" class="nb" alt="">
          <?php echo lang('Site Maintenance'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <h3><?php echo lang('View PHPINFO Debug/Settings Output'); ?></h3>
        <p class="action">
          <span class="desc"><?php echo lang('View your local PHPINFO, describing all your PHP settings(opens in a new window).'); ?></span>
          <?php echo generate_link_button(lang('View PHPINFO Page'), site_url('admin/actions/php_info'), base_url().'img/icons/about_16.png', NULL, array('rel' => 'external', 'target' => '_blank')); ?> 
        </p>
        <br><br>
        <h3><?php echo lang('Clear Site/Settings Cache'); ?></h3>
        <p class="action">
          <span class="desc"><?php echo lang('Clear your local cache files and generate new ones. This is useful if your getting random PHP errors.'); ?></span>
          <?php echo generate_link_button(lang('Clear Site/Settings Cache'), site_url('admin/actions/clear_cache'), base_url().'img/icons/trash_16.png', NULL); ?> 
        </p>
        <br><br>
        <h3><?php echo lang('Run Site Cron'); ?></h3>
        <p class="action">
          <span class="desc"><?php echo lang('Run the site cron now, removing all orphaned files and running any extention cron files.'); ?></span>
          <?php echo generate_link_button(lang('Run Site Cron'), site_url('admin/actions/run_cron'), base_url().'img/icons/history_16.png', NULL); ?> 
        </p>
        <br><br>
        <h3><?php echo lang('Sync Server Settings'); ?></h3>
        <p class="action">
          <span class="desc"><?php echo lang('Sync out the latest settings to each slave server, config, user groups and plugins.'); ?></span>
          <?php echo generate_link_button(lang('Sync Server Settings'), site_url('admin/actions/update_server_cache'), base_url().'img/icons/sync_16.png', NULL); ?> 
        </p>
        <br><br>

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
        <div style="border:1px #666666 solid">
          <h3 style="padding-top:0">
            <img src="<?php echo base_url(); ?>/img/icons/warning_24.png" class="nb" alt="">
            <span style="color:#FF0000; text-decoration:underline"><?php echo lang('Important Information'); ?></span>
            <img src="<?php echo base_url(); ?>/img/icons/warning_24.png" class="nb" alt="">
          </h3>
          <p style="font-weight:bold">
            <?php echo lang('This will FTP all php files required for a clean server install overwriting old files.'); ?><br>
            <span style="color:#FF0000; text-decoration:underline"><?php echo lang('THIS WILL ERASE ALL CHANGES YOU MIGHT HAVE MADE ON THIS SERVER!'); ?></span>
          </p>
        </div>
        <form action="<?php echo site_url('/admin/server/install/'.$sid); ?>" method="post"> 
          <input type="hidden" name="step-2" value="true" size="50">
          <input type="hidden" name="ftp_url" value="<?php echo $this->input->post('ftp_url'); ?>" size="50">
          <input type="hidden" name="ftp_user" value="<?php echo $this->input->post('ftp_user'); ?>" size="20">
          <input type="hidden" name="ftp_pass" value="<?php echo $this->input->post('ftp_pass'); ?>" size="20">
          <input type="hidden" name="ftp_port" value="<?php echo ($this->input->post('ftp_port') ? $this->input->post('ftp_port') : '21'); ?>" size="7">
          <input type="hidden" name="ftp_path" value="<?php echo $this->input->post('ftp_path'); ?>" size="50">
          <input type="hidden" name="random" value="<?php $rand = mt_rand(111,999); echo $rand; ?>" size="50">
          <h3><?php echo lang('Confirm Software Install'); ?></h3>
          <p><?php echo lang('We have confirmed a working FTP connection to the server you specified. Please click the button below to confirm that you want to install server software on this server.'); ?></p>
          <p>
            <?php echo generate_submit_button(lang('Install Server Software'), base_url().'img/icons/ok_16.png'); ?><br>
          </p>
        </form>

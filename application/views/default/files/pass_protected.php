        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/security_32.png" class="nb" alt="">
          <?php echo lang('Download File - Enter Password'); ?> 
        </h2>
<? if($error): ?>
        <span class="alert"><?php echo lang('The Password you submited was incorrect.'); ?></span>
<?php endif; ?>
        <form action="<?php echo site_url('files/get/'.$file->file_id.'/'.$file->link_name); ?>" method="post">
          <h3 id="dlhere"><?php echo lang('Enter File Password'); ?></h3>
          <p>
            <?php echo lang('This file has been password protected by the uploader. You must provide the correct password to download this file.'); ?> 
            <code>
              <label style="font-weight:bold" for="pass"><?php echo lang('File Password:'); ?></label>
              <input style="background: url('<?php echo base_url(); ?>img/icons/security_16.png') 2px center no-repeat; padding-left:20px;" type="text" size="40" maxlength="32" name="pass"><br><br>
              <?php echo generate_submit_button(lang('Unlock File'), base_url().'img/icons/security_16.png', 'green')?><br>
            </code>
          </p>
        </form>

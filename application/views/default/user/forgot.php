        <h2 style="vertical-align:middle">
          <img alt="" class="nb" src="<?php echo base_url(); ?>img/icons/help_32.png">
          <?php echo lang('Forgot Password'); ?> 
        </h2>
        <?php echo $error_message; ?> 
        <form action="<?php echo site_url('user/forgot_password'); ?>" method="post">
          <input type="hidden" name="posted" value="true">
          <h3><?php echo lang('Update Profile'); ?></h3>
          <p>
            <label style="font-weight:bold" for="username"><?php echo lang('Username'); ?></label>
            <input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50"><br><br>
            <label style="font-weight:bold" for="passconf"><?php echo lang('Email Address')?></label>
            <input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50"><br><br>
            <?php echo generate_submit_button(lang('Get New Password'), base_url().'img/icons/security_16.png'); ?><br>
          </p>
        </form>

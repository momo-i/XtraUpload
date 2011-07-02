        <h2 style="vertical-align:middle">
          <img alt="" class="nb" src="<?php echo base_url(); ?>img/icons/user_32.png">
          <?php echo lang('User Login'); ?> 
        </h2>
        <?php echo $error_message; ?> 
        <div id="login">
          <form action="<?php echo site_url('user/login'); ?>" method="post" class="loginform">
            <input type="hidden" name="submit" value="1">
            <p>
              <label for="username_1"><strong><?php echo lang('Username:')?></strong></label>
              <input style="background:2px center url(<?php echo base_url(); ?>img/icons/user_16.png) no-repeat transparent; padding-left:22px" type="text" id="username_1" name="username">
              <label for="password_1"><strong><?php echo lang('Password:')?></strong></label>
              <input style="background:2px center url(<?php echo base_url(); ?>img/other/key_16.png) no-repeat transparent; padding-left:22px" type="password" id="password_1" name="password"><br><br>
              <?php echo generate_submit_button(lang('Login'), base_url().'img/icons/log_in_16.png', 'green')?><br>
            </p>
          </form>
        </div>

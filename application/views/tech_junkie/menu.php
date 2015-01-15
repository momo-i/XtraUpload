<?php
if($this->session->userdata('id'))
{
?>
        <h3><?php printf(lang('Welcome %s!'), $this->session->userdata('username')); ?></h3>
        <ul class="sidemenu">
          <li>
            <a href="<?php echo site_url('user/manage')?>">
              <img src="<?php echo base_url(); ?>assets/images/icons/options_16.png" class="nb" alt=""> <?php echo lang('Manage Account')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/change_password'); ?>">
              <img src="<?php echo base_url(); ?>assets/images/icons/security_16.png" class="nb" alt=""> <?php echo lang('Change Password')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/logout'); ?>">
              <img src="<?php echo base_url(); ?>assets/images/icons/log_in_16.png" class="nb" alt=""> <?php echo lang('Logout')?>
            </a>
          </li>
        </ul>
<?php
}
else
{
?> 
        <h3><?php echo lang('Member Login'); ?></h3>
        <form action="<?php echo site_url('user/login'); ?>" method="post" class="loginform">
          <input type="hidden" name="submit" value="1">
          <p>
            <label for="username"><strong><?php echo lang('Username:')?></strong></label>
            <input style="background:2px center url(<?php echo base_url(); ?>assets/images/icons/user_16.png) no-repeat transparent; padding-left:22px" type="text" id="username" name="username">
            <label for="password"><strong><?php echo lang('Password:')?></strong></label>
            <input style="background:2px center url(<?php echo base_url(); ?>assets/images/other/key_16.png) no-repeat transparent; padding-left:22px" type="password" id="password" name="password"><br><br>
            <?php echo generate_submit_button(lang('Login'), base_url().'assets/images/icons/log_in_16.png', 'green'); ?><br>
          </p>
        </form>
        <ul class="sidemenu">
          <li>
            <a href="<?php echo site_url('user/forgot_password'); ?>">
              <img src="<?php echo base_url(); ?>assets/images/icons/help_16.png" class="nb" alt=""> <?php echo lang('Forgot your password?')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/register'); ?>">
                <img src="<?php echo base_url(); ?>assets/images/other/user-add_16.png" class="nb" alt=""> <?php echo lang('New? Register here!')?>
            </a>
          </li>
        </ul>
<?php
}	
echo $this->xu_api->menus->get_sub_menu();

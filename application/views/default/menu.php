<?php
if($this->session->userdata('id'))
{
?>
          <h3><?php printf(lang('Welcome %s!'), $this->session->userdata('username')); ?></h3>
          <ul class="sidemenu">
            <li>
              <?php echo anchor('user/manage', '<img src="'.base_url().'img/icons/options_16.png" class="nb" alt="">'.lang('Manage Account')); ?> 
            </li>
            <li>
              <?php echo anchor('user/change_password', '<img src="'.base_url().'img/icons/security_16.png" class="nb" alt="">'.lang('Change Password')); ?> 
            </li>
            <li>
              <?php echo anchor('user/logout', '<img src="'.base_url().'img/icons/log_out_16.png" class="nb" alt="">'.lang('Logout')); ?> 
            </li>
          </ul>
<?php
}
else
{
?>
          <h3><?php echo lang('Member Login'); ?></h3>
          <form action="<?php echo site_url('user/login');?>" method="post" class="loginform" id="loginform">
            <input type="hidden" name="submit" value="1">
            <p>
              <label for="username">
                <strong><?php echo lang('Username:'); ?></strong>
                <input style="background:2px center url(<?php echo base_url(); ?>img/icons/user_16.png) no-repeat transparent; padding-left:22px" type="text" id="username" name="username">
              </label>
              <label for="password">
                <strong><?php echo lang('Password:'); ?></strong>
                <input style="background:2px center url(<?php echo base_url(); ?>img/other/key_16.png) no-repeat transparent; padding-left:22px" type="password" id="password" name="password">
              </label>
              <br><br>
              <?php echo generate_submit_button(lang('Login'), base_url().'img/icons/log_in_16.png', 'green'); ?>
            </p>
          </form>
          <ul class="sidemenu">
            <li>
              <?php echo anchor('user/forgot_password', '<img src="'. base_url().'img/icons/help_16.png" class="nb" alt="">'.lang('Forgot Your Password?')); ?> 
            </li>
            <li>
              <?php echo anchor('user/register', '<img src="'. base_url().'img/other/user-add_16.png" class="nb" alt="">'.lang('New? Register Here!')); ?> 
            </li>
          </ul>
<?php
}
?>
          <?php echo $this->xu_api->menus->get_sub_menu(); //Get action submenus ?>

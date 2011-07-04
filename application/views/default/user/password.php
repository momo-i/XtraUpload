<?php
if( ! $this->session->userdata('id'))
{
	redirect('home');
}
?>
        <h2 style="vertical-align:middle">
          <img alt="" class="nb" src="<?php echo base_url(); ?>img/icons/user_32.png">
          <?php echo lang('Change Password'); ?> 
        </h2>
        <?php echo $error_message; ?> 
        <form action="<?php echo site_url('user/change_password'); ?>" method="post">
          <h3><?php echo lang('Change Password'); ?></h3>
          <p>
            <label style="font-weight:bold" for="username"><?php echo lang('Username; ')?></label>
            <input type="text" class="readonly" readonly="readonly" name="username" value="<?php echo $this->session->userdata('username'); ?>" size="50"><br><br>
            <label style="font-weight:bold" for="password"><?php echo lang('Old Password'); ?></label>
            <input type="password" name="oldpassword" size="50"><br>
            <label style="font-weight:bold" for="password"><?php echo lang('New Password'); ?></label>
            <input type="password" name="newpassword" size="50"><br>
            <label style="font-weight:bold" for="passconf"><?php echo lang('Confirm New Password'); ?></label>
            <input type="password" name="newpassconf" size="50"><br><br>
            <?php echo generate_submit_button(lang('Submit Changes'), base_url().'img/icons/ok_16.png', 'green')?><br>
          </p>
        </form>

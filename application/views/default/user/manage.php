<?php
if( ! $this->session->userdata('id'))
{
	redirect('home');
}
?>
        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>assets/images/icons/user_32.png" alt="" class="nb">
          <?php echo lang('Manage User'); ?> 
        </h2>
        <?php echo $error_message; ?> 
        <form action="<?php echo site_url('user/manage'); ?>" method="post">
          <h3><?php echo lang('Update Profile'); ?></h3>
          <p>
            <label style="font-weight:bold" for="username"><?php echo lang('Username:'); ?></label>
            <input type="text" class="readonly" readonly="readonly" name="username" value="<?php echo $this->session->userdata('username'); ?>" size="50"><br><br>
            <label style="font-weight:bold" for="realname"><?php echo lang('Email'); ?></label>
            <input type="text" name="email" value="<?php echo set_value('email', $user->email); ?>" size="50"><br>
            <label style="font-weight:bold" for="realname"><?php echo lang('New Password'); ?></label>
            <input type="text" name="password" value="<?php echo set_value('password'); ?>" size="50"><br>
            <label style="font-weight:bold" for="realname"><?php echo lang('User Locale'); ?></label>
            <select name="locale">
<?php
foreach($locales as $locale => $lname)
{
	$selected = ($user->locale == $locale) ? ' selected="selected"' : '';
?>
              <option<?php echo $selected; ?> value="<?php echo $locale; ?>"><?php echo $lname; ?></option>
<?php
} //endforeach
?>
            </select><br><br>
            <?php echo generate_submit_button(lang('Submit Changes'), base_url().'assets/images/icons/ok_16.png', 'green').'<br>'; ?>
          </p>
        </form>

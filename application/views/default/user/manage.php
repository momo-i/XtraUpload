<?php
if( ! $this->session->userdata('id'))
{
	redirect('home');
}
?>
        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" alt="" class="nb">
          <?php echo lang('Manage User'); ?> 
        </h2>
        <?php echo $error_message; ?> 
        <form action="<?php echo site_url('user/manage'); ?>" method="post">
          <h3><?php echo lang('Update Profile'); ?></h3>
          <p>
            <label style="font-weight:bold" for="username"><?php echo lang('Username:'); ?></label>
            <input type="text" class="readonly" readonly="readonly" name="username" value="<?php echo $this->session->userdata('username'); ?>" size="50"><br><br>
            <?php //generate_submit_button(lang('Submit Changes'), base_url().'img/icons/ok_16.png', 'green').'<br>'; ?>
          </p>
        </form>

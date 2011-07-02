        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" alt="" class="nb">
          <?php printf(lang('User Profile: %s'), '<span>'.$user->username.'</span>'); ?> 
        </h2>
        <div class="userProfile">
          <div>
<?php
if($this->session->userdata('username') == $user->username)
{
?>
            <span class="info">
              <strong><?php echo lang('Username'); ?>:</strong>
              <?php echo lang('This is your user profile!'); ?><br>
              <?php printf(lang('You can update these values %s!'), anchor('user/manage', lang('here'))); ?> 
            </span>
<?php
}
?>
            <h3><img src="<?php echo base_url(); ?>img/icons/public_16.png" alt="" class="nb"><?php echo lang('User Info'); ?></h3>
            <p>
              <?php printf(lang('Username: %s'), ucwords($user->username)); ?><br>
              <br>
            </p>
            <h3><img src="<?php echo base_url(); ?>img/icons/chart_16.png" alt="" class="nb"><?php echo lang('User Stats'); ?></h3>
<?php
$num = $this->db->get_where('refrence', array('user' => $this->session->userdata('id')))->num_rows();
?>
            <ul>
              <li style="list-style-image:none;">
                <strong><?php printf(lang('Uploaded Files: %s'), $num); ?></strong>
              </li>
            </ul>
          </div>
        </div>


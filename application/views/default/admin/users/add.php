        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('User - Add'); ?> 
        </h2>
        <br>
        <?php echo $error; ?>
        <?php echo generate_link_button(lang('Manage Users'), site_url('admin/user/home'), base_url().'img/icons/back_16.png'); ?><br>
        <form action="<?php echo site_url('admin/user/add')?>" method="post">
          <h3><?php echo lang('Add New User'); ?></h3>
          <p>
            <label style="font-weight:bold" for="username"><?php echo lang('Username'); ?></label>
            <input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50"><br>
            <label style="font-weight:bold" for="email"><?php echo lang('Email'); ?></label>
            <input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50"><br>
            <label style="font-weight:bold" for="group"><?php echo lang('User Group'); ?></label>
            <select name="group">
<?php
foreach ($groups->result() as $group)
{
?>
              <option value="<?php echo $group->id; ?>"<?php echo set_select('group'); ?>><?php echo ucwords($group->name); ?></option>
<?php
}
?>
            </select>
            <label style="font-weight:bold" for="password"><?php echo lang('Password'); ?></label>
            <input type="text" name="password" value="<?php echo set_value('password'); ?>" size="50"><br><br>
            <?php echo generate_submit_button(lang('Add User'), base_url().'img/icons/ok_16.png', 'green'); ?><br>
          </p>
        </form>

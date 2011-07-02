        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('Register New User')?> 
        </h2>
        <?php echo $error_message; ?> 
        <form action="<?php echo site_url('user/register'); ?>" method="post" id="user_reg">
          <input type="hidden" name="posted" value="1">
          <label style="font-weight:bold" for="reg_username"><?php echo lang('Username'); ?></label>
          <input type="text" class="required remove_title" id="reg_username" minlength="5" name="username" value="<?php echo set_value('username');?>" size="50">
          <br><br>
          <label style="font-weight:bold" for="group_sel">
            <?php echo lang('User Group'); ?>, <?php echo anchor('user/compare', lang('Click here for a comparision'), 'rel="external"'); ?> 
          </label>
          <select name="group" id="group_sel" onchange="isPaidGroup(this.value)">
<?php
$bs = array(
	'0' => lang('None'),
	'd' => lang('Day'),
	'w' => lang('Week'),
	'm' => lang('Month'),
	'y' => lang('Year'),
	'dy' => lang('2 Years'),
);
$script = array();
foreach ($groups->result() as $group)
{
	if($group->id == 2 or $group->id == 1)
	{
		continue;
	}
	$script[$group->id] = false;
	if($group->price > 0.00)
	{
		$script[$group->id] = true;
	}
	if($group->price > 0.00)
	{
		$strings = sprintf(lang('$%s per %s'), $group->price, $bs[$group->repeat_billing]);
	}
	else
	{
		$strings = lang('Free');
	}
?>
            <option value="<?php echo $group->id; ?>">
              <?php echo ucwords($group->name); ?>&nbsp;(<?php echo $strings; ?>)&nbsp;
            </option>
<?php
}
?>
          </select>
          <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
              isPaidGroup($('#group_sel').val());
            });
            function isPaidGroup(id)
            {
              var groups = new Array();
<?php
foreach ($script as $id => $paid)
{
?>
              groups[<?php echo $id; ?>] = <?php echo $paid; ?>;
<?php
}
?>
              if(groups[id])
              {
                $('#payment_gate').slideDown('normal');
              }
              else
              {
                $('#payment_gate').slideUp('normal');
              }
            }
            //--]]>
          </script>
          <div style="display:none" id="payment_gate">
            <label style="font-weight:bold" for="group2"><?php echo lang('Select Payment Method'); ?></label>
            <select name="gate" id="group2">
<?php
foreach ($gates->result() as $gate)
{
?>
              <option value="<?php echo $gate->id; ?>"<?php echo set_select('group2')?>><?php echo ucwords($gate->display_name); ?></option>
<?php
}
?>
            </select>
            <br><br>
          </div>
          <label style="font-weight:bold" for="email"><?php echo lang('Email Address'); ?></label> 
          <input type="text" id="email" class="required email remove_title" name="email" value="<?php echo set_value('email'); ?>" size="50"><br>
          <label style="font-weight:bold" for="emailConf"><?php echo lang('Email Address Confirmation'); ?> </label>
          <input type="text" id="emailConf" class="required email remove_title" name="emailConf" value="<?php echo set_value('emailConf'); ?>" size="50"><br><br>
          <label style="font-weight:bold" for="password"><?php echo lang('Password'); ?></label>
          <input type="password" class="required remove_title" name="password" id="password" value="<?php echo set_value('password'); ?>" size="50"><br>
          <label style="font-weight:bold" for="passconf"><?php echo lang('Password Confirmation'); ?></label>
          <input type="password" class="required remove_title" name="passconf" value="<?php echo set_value('passconf'); ?>" size="50"><br><br>
          <label style="font-weight:bold" for="captcha"><?php echo lang('Security Test - Type the letters you see below:'); ?></label>
          <?php echo $captcha; ?><br>
          <input type="text" class="required remove_title" name="captcha"><br><br>
          <?php echo generate_submit_button(lang('Submit Registration'), base_url().'img/other/user-add_16.png'); ?><br>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          $(document).ready(function(){
            $("#user_reg").validate();
          });
          //--]]>
        </script>

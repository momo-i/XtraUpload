        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/options_32.png" class="nb" alt="">
          <?php echo lang('Payment Gateway Settings'); ?> 
        </h2>
        <?php echo $flash_message; ?> 
        <form method="post" action="<?php echo site_url('admin/gateways/update/'.$gate->id); ?>">
          <h3><?php printf(lang('%s Config'), $gate->display_name); ?></h3>
<?php
$set = @unserialize($gate->settings);
$config = @unserialize($gate->config);
foreach ($config as $name => $type)
{
?>
          <label><?php echo ucwords(str_replace('_', ' ', $name)); ?></label>
<?php
	if($type == 'text')
	{
?>
          <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $set[$name]; ?>"><br><br>
<?php
	}
	elseif($type == 'box')
	{
?>
          <textarea rows="8" cols="40" name="<?php echo $name; ?>" id="<?php echo $name; ?>" ><?php echo $set[$name]; ?></textarea><br><br>
<?php
	}
}
?>
          <input type="hidden" name="valid" value="yes">
          <?php echo generate_submit_button(lang('Update'), base_url().'img/icons/ok_16.png', 'green'); ?><br>
        </form>

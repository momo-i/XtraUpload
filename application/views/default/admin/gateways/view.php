        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/credit_card_32.png" class="nb" alt="">
          <?php echo lang('Payment Gateways'); ?>
        </h2>
        <?php echo $flash_message; ?>
        <table class="special" border="0" id="gateway_list_table" cellspacing="0" style="width:98%">
          <tr>
            <th class="align-left"><?php echo lang('Name'); ?></th>
            <th><?php echo lang('Actions'); ?></th>
          </tr>
<?php
foreach ($gates->result() as $gate)
{
?>            
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <?php echo $gate->display_name; ?> 
            </td>
            <td>
<?php
	if($gate->status)
	{
?>
              <a title="<?php echo lang('Disable Gateway'); ?>" href="<?php echo site_url('admin/gateways/turn_off/'.$gate->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="<?php echo lang('Disable'); ?>">
              </a>
<?php
	}
	else
	{
?>
              <a title="<?php echo lang('Enable Gateway'); ?>" href="<?php echo site_url('admin/gateways/turn_on/'.$gate->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="<?php echo lang('Enable'); ?>">
              </a>
<?php
	}
	if($gate->default)
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/certificate_16.png" class="nb" alt="<?php echo lang('Current Default!'); ?>">
<?php
	}
	else
	{
?>
              <a title="<?php echo lang('Set To Default'); ?>" href="<?php echo site_url('admin/gateways/set_default/'.$gate->id); ?>">
                <img src="<?php echo base_url();?>img/icons/new_16.png" class="nb" alt="<?php echo lang('Set To Default'); ?>">
              </a>
<?php
	}
?>
              <a title="<?php echo lang('Edit This Gateway'); ?>" href="<?php echo site_url('admin/gateways/edit/'.$gate->id); ?>">
                    <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>">
              </a>
            </td>
          </tr>
<?php 
}
?>
        </table>

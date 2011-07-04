        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/transaction_32.png" class="nb" alt="">
          <?php echo lang('Transaction Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <div style="display:none">
          <div id="massActions" style="clear:both; padding-top:4px;">
            <div class="float-right">
              <?php echo generate_link_button(lang('Search'), site_url('admin/files/search'), base_url().'img/icons/search_16.png', NULL); ?>
            </div>
          </div>
          <div style="clear:both;"></div>
        </div>
        <table class="special" border="0" id="file_list_table"cellspacing="0" style="width:98%">
          <tr>
            <th><?php echo lang('User'); ?></th>
            <th><?php echo lang('Ammount'); ?></th>
            <th><?php echo lang('Gateway'); ?></th>
            <th><?php echo lang('Date'); ?></th>
            <th><?php echo lang('Status'); ?></th>
            <th><?php echo lang('Actions'); ?></th>
          </tr>
<?php
foreach ($transactions->result() as $transaction)
{
	$user = $this->db->select('username')->get_where('users', array('id' => intval($transaction->user)))->row();
	$gate = $this->db->select('display_name')->get_where('gateways', array('id' => intval($transaction->gateway)))->row();
?>
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <a href="<?php echo site_url('/admin/transactions/user/'.$user->id); ?>">
                <?php echo $user->username; ?>
              </a>
            </td>
            <td>
              $<?php echo $transaction->amount; ?>
            </td>
            <td>
              <?php echo $gate->display_name; ?>
            </td>
            <td>
              <?php echo unix_to_small($transaction->time)?>
            </td>
            <td>
<?php
	if($transaction->status)
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/ok_16.png" alt="" class="nb">
<?php
	}
	else
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/cancel_16.png" alt="" class="nb">
<?php
	}
?>
            </td>
            <td>
<?php
	if(!$transaction->status)
	{
?>
              <a title="<?php echo lang('Approve Transaction'); ?>" href="<?php echo site_url('admin/transactions/approve/'.$transaction->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/ok_16.png" class="nb" alt="<?php echo lang('Approve Transaction'); ?>">
              </a>
<?php
	}
?>
              <a title="<?php echo lang('Edit Transaction'); ?>" href="<?php echo site_url('admin/transactions/edit/'.$transaction->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>">
              </a>
              <a title="<?php echo lang('Delete Transaction'); ?>" onclick="return confirm('<?php echo lang('Are you sure you want to delete this transaction?'); ?>')" href="<?php echo site_url('admin/transactions/delete/'.$transaction->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
              </a>
            </td>
          </tr>
<?php
}
?>
        </table>
        <br>
        <?php echo $pagination; ?>


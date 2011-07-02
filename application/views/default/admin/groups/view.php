        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_group_32.png" class="nb" alt="">
          <?php echo lang('Groups - Manage'); ?> 
        </h2>
        <div class="users">
          <?php echo $flash_message; ?>
          <div id="massActions" style="clear:both; padding-top:4px;">
            <div class="float-right">
              <?php echo generate_link_button(lang('New Group'), site_url('admin/group/add'), base_url().'img/icons/add_16.png', NULL); ?>
            </div>
          </div>
          <table class="special" border="0" cellpadding="4" cellspacing="0" style="width:98%">
            <tr>
              <th>
                <div align="center"><?php echo lang('Name'); ?></div>
              </th>
              <th>
                <div align="center"><?php echo lang('Description'); ?></div>
              </th>
              <th>
                <div align="center"><?php echo lang('Actions'); ?></div>
              </th>
            </tr>
<?php
foreach ($groups->result() as $group)
{
?>
            <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
              <td><div align="center"><?php echo ucwords($group->name);?></div></td>
              <td><div align="center"><?php echo $group->descr; ?></div></td>
              <td>
                <div align="center"> 
<?php
	if($group->id > 2)
	{
		if($group->status)
		{
?>
                  <a href="<?php echo site_url('admin/group/turn_off/'.$group->id); ?>">
                    <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="public" title="<?php echo lang('Make Private'); ?>">
                  </a>
<?php
		}
		else
		{
?>
                  <a href="<?php echo site_url('admin/group/turn_on/'.$group->id); ?>">
                    <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="private" title="<?php echo lang('Make Public'); ?>">
                  </a>
<?php
		}
	}
?>
                  <a href="<?php echo site_url('admin/group/edit/'.$group->id); ?>">
                    <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>" title="<?php echo lang('Edit'); ?>">
                  </a>
<?php
	if($group->id > 2)
	{
?>
                  <a href="<?php echo site_url('admin/group/delete/'.$group->id); ?>"> 
                    <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>" title="<?php echo lang('Delete'); ?>">
                  </a>
<?php
	}
?>
                </div>
              </td>
            </tr>
<?php
}
?>
          </table>
        </div>

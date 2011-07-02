        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/server_32.png" class="nb" alt="">
          <?php echo lang('Server Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-right">
            <?php echo generate_link_button(lang('Add New Server'), site_url('admin/server/add'), base_url().'img/icons/new_16.png', NULL); ?>
          </div>
        </div>
        <table border="0" style="width:95%" id="file_list_table">
          <tr>
            <th class="align-left"><?php echo lang('Name'); ?></th>
            <th class="align-left"><?php echo lang('URL'); ?></th>
            <th class="align-left"><?php echo lang('# Files'); ?></th>
            <th class="align-left"><?php echo lang('Used Space'); ?></th>
            <th style="text-align:center"><?php echo lang('Actions'); ?></th>
          </tr>
<?php
foreach ($servers->result() as $server)
{
?>            
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td style="font-size:12px; font-weight:bold; color:#006600">
              <?php echo $server->name; ?>
            </td>
            <td style="font-size:12px; font-weight:bold; color:#006600">
              <?php echo $server->url; ?>
            </td>
            <td style="font-size:12px; font-weight:bold; color:#006600">
<?php
	$file = $this->db->get_where('files', array('server' => $server->url));
?>
              <?php echo $file->num_rows(); ?>
            </td>
            <td style="font-size:12px; font-weight:bold; color:#006600">
<?php
	$this->db->select_sum('size');
	$file = $this->db->get_where('files', array('server' => $server->url));
?>
              <?php echo $this->functions->get_filesize_prefix($file->row()->size); ?>
            </td>
            <td style="text-align:center">
<?php
	if(!$server->status)
	{
?>
              <a title="<?php echo lang('Turn On Server'); ?>" href="<?php echo site_url('admin/server/turn_on/'.$server->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="<?php echo lang('Turn On'); ?>">
              </a>
<?php
	}
	else
	{
?>
              <a title="<?php echo lang('Turn Off Server'); ?>" href="<?php echo site_url('admin/server/turn_off/'.$server->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="<?php echo lang('Turn Off'); ?>">
              </a>
<?php
	}
?>
              <a title="<?php echo lang('Edit Server'); ?>" href="<?php echo site_url('admin/server/edit/'.$server->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/edit_16.png" class="nb" alt="<?php echo lang('Edit'); ?>">
              </a>
              <a title="<?php echo lang('Install Server'); ?>" href="<?php echo site_url('admin/server/install/'.$server->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/wizard_16.png" class="nb" alt="<?php echo lang('Install'); ?>">
              </a>
              <a title="<?php echo lang('Delete Server'); ?>" href="<?php echo site_url('admin/server/delete/'.$server->id); ?>">
                <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
              </a>
            </td>
          </tr>
<?php
}
?>
        </table>

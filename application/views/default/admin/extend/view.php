        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/component_32.png" class="nb" alt="">
          <?php echo lang('Plugin Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?> 
        <h3>
          <img src="<?php echo base_url(); ?>img/icons/connect_16.png" class="nb" alt="">
          <?php echo lang('Installed Plugins'); ?> 
        </h3>
        <table border="0" style="width:99%" id="file_list_table">
          <tr>
            <th class="align-left"><?php echo lang('Name'); ?></th>
            <th><?php echo lang('Description'); ?></th>
            <th class="align-right" width="14%"><?php echo lang('Action'); ?></th>
          </tr>
<?php
foreach ($installed as $name => $plugin)
{
?>
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <?php echo anchor($plugin->link, $plugin->name, array('rel' => 'external')); ?> v<?php echo $this->functions->parse_version($plugin->version->local, false); ?><br>
              <?php printf(lang('By: %s'), anchor($plugin->author->link, $plugin->author->name, array('rel' => 'external'))); ?> 
            </td>
            <td><?php echo str_replace("\n", '<br>', word_wrap($plugin->description, 60)); ?></td>
            <td class="align-right">
<?php
	$active = $this->db->select('active')->get_where('extend', array('file_name' => $name), 1, 0)->row()->active;
	if($active == 1)
	{
?>
              <?php echo anchor('admin/extend/turn_off/'.$name, '<img src="'.base_url().'img/icons/on_16.png" alt="" class="">'); ?> 
<?php
	}
	else
	{
?>
              <?php echo anchor('admin/extend/turn_on/'.$name, '<img src="'.base_url().'img/icons/off_16.png" alt="" class="">'); ?> 
<?php
	}
	if($this->startup->site_config->allow_version_check && isset($plugin->version->latest_link))
	{
		$this->load->helper('admin/version');
		$latest_version = check_version($plugin->version->latest_link);
		if($plugin->version->local < $latest_version && $latest_version != false)
		{
			$imgsrc = '<img src="'.base_url().'img/icons/certificate_16.png" alt="" class="nb" title="'.sprintf(lang('New Version Available: v%s'), $this->functions->parse_version($latest_version, false)).'">';
?>
              <?php echo anchor($plugin->version->download_link, $imgsrc, array('rel' => 'external')); ?>
<?php
		}
	}
?>
              <?php echo anchor('admin/extend/remove/'.$name, '<img src="'.base_url().'img/icons/trash_16.png" alt="" class="nb">'); ?>
            </td>
          </tr>
<?php
}
?>
        </table>
        <h3>
          <img src="<?php echo base_url(); ?>img/icons/disconnect_16.png" class="nb" alt="">
          <?php echo lang('Not Installed Plugins'); ?> 
        </h3>
        <table border="0" style="width:99%" id="file_list_table">
          <tr>
            <th class="align-left"><?php echo lang('Name'); ?></th>
            <th><?php echo lang('Description'); ?></th>
            <th class="align-right" width="11%"><?php echo lang('Action'); ?></th>
          </tr>
<?php
foreach ($not_installed as $name => $plugin)
{
?>
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <?php echo anchor($plugin->link, $plugin->name, array('rel' => 'external')); ?> v<?php echo $this->functions->parse_version($plugin->version->local, false); ?><br>
              <?php printf(lang('By: %s'), anchor($plugin->author->link, $plugin->author->name, array('rel' => 'external'))); ?> 
            </td>
            <td><?php echo str_replace("\n", '<br>', word_wrap($plugin->description, 60)); ?></td>
            <td class="align-right">
                <?php echo anchor($plugin->link, '<img src="'.base_url().'img/icons/link_16.png" alt="" class="nb">', array('rel' => 'external', 'title' => lang('Visit Plugin Home Page'))); ?> 
                <?php echo anchor('admin/extend/install/'.$name, '<img src="'.base_url().'img/icons/wizard_16.png" alt="" class="nb">', array('title' => lang('Install Plugin'))); ?> 
            </td>
          </tr>
<?php
}
?>
        </table>

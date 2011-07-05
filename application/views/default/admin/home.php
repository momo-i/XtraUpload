        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/admin_32.png" class="nb" alt="">
          <?php echo lang('Admin Home'); ?> 
        </h2>
        <h3 id="php_ini_header" ><?php echo lang('Important Server Settings'); ?></h3>
<?php
$ini_list = array(
	'upload_max_filesize' => lang('The largest uploaded file size this server is configured to process is <em>{$}B</em>. <br>This overrides your group settings.'),
	'post_max_size'  => lang('The maximum size of all allowed POST data is <em>{$}B</em>'),
	'max_execution_time'  => lang('The longest amount of time your server can execute PHP files is <em>{$} second(s)</em>'),
	'max_input_time'  => lang('The longest request your server will process is <em>{$} second(s</em>)'),
	'memory_limit' => lang('The maximum amount of memory a PHP script can use is <em>{$}B</em>'),
	'short_open_tag' => lang('Enable the use of PHP short opening tags "&lt;?": {$}')
);
$ini_name = array(
	'upload_max_filesize' => lang('Max File Size'),
	'post_max_size'  => lang('Max POST-Request Size'),
	'max_execution_time'  => lang('Execution Timeout'),
	'max_input_time'  => lang('Request Input Timeout'),
	'memory_limit' => lang('Memory Limit'),
	'short_open_tag' => lang('Allow PHP Short Tags')
);
$ini_rec = array(
	'upload_max_filesize' => '250M',
	'post_max_size'  => '1000M',
	'max_execution_time'  => '600',
	'max_input_time'  => '600',
	'memory_limit' => '320M',
	'short_open_tag' => '1'
);

function rename_ini_result($r, $n)
{
	if($n == 'short_open_tag')
	{
		if($r == 1)
		{
			return 'On';
		}
		else
		{
			return 'Off';
		}
	}
	return $r;
}
?>
        <ul id="php_ini_list" style="font-size:1.2em;">
<?php
$is_not_good = false;
foreach ($ini_list as $k => $v)
{
?>
          <li>
            <a href="javascript:;" onclick="$('#php_<?php echo $k; ?>').slideToggle('normal')">
              <strong><?php echo $ini_name[$k]; ?></strong>
<?php
	if($k == 'upload_max_filesize' OR $k == 'post_max_size' OR $k == 'memory_limit')
	{
		echo ini_get($k)."B";
	}
	elseif($k == 'short_open_tag')
	{
		if(ini_get($k) == 1)
		{
			echo lang('On');
		}
		else
		{
			echo lang('Off');
		}
	}
	else
	{
		printf(lang('%s second(s)'), ini_get($k));
	}
?>
            </a>
<?php
	if(intval(ini_get($k)) < intval($ini_rec[$k]))
	{
		$is_not_good = true;
		echo ' - <img src="'.base_url().'img/icons/cancel_16.png" alt="'.lang('Error!').'" title="'.lang('Error!').'" class="nb"><span style="color:#F00">'.lang('Recommended').': ';
		if($k == 'upload_max_filesize' or $k == 'post_max_size' or $k == 'memory_limit')
		{
			echo $ini_rec[$k].'B';
		}
		elseif($k == 'short_open_tag')
		{
			echo lang('On');
		}
		else
		{
			printf(lang('%s second(s)'), $ini_rec[$k]);
		}
		echo '</span>';
	}
	else
	{
		echo '<img src="'.base_url().'img/icons/ok_16.png" alt="'.lang('Ok!').'" title="'.lang('Ok!').'" class="nb" />';
	}
?>
            <span id="php_<?php echo $k; ?>" style="display:none">
              <strong style="padding-left:12px; text-decoration:underline"><?php echo str_replace('{$}', rename_ini_result(ini_get($k), $k),$v ); ?></strong>
            </span>
          </li>
<?php
}
?>
        </ul>
<?php
if($is_not_good)
{
?>
        <span class="alert"><?php echo lang('Some of the above Settings are not ideal, please inspect these to ensure minimal problems.'); ?></span>
<?php
}
else
{
?>
        <script type="text/javascript">
          //<![CDATA[
          $(document).ready(function()
          {
            $('#php_ini_list, #php_ini_header').hide();
          });
          //--]]>
        </script>
<?php
}
if($this->startup->site_config->allow_version_check)
{
	$this->load->helper('admin/version');
	$latest_version = check_version(); //@file_get_contents('http://xtrafile.com/xu_version.txt');
	if(XU_VERSION < $latest_version)
	{
?>
        <h3><?php echo lang('Upgrade Available'); ?></h3>
        <span class="alert">
          <?php echo lang('Important Upgrade Available'); ?>:
          <?php echo anchor('http://xtrafile.com/files/', sprintf(lang('Important Upgrade Available %s'), '<strong>'.$this->functions->parse_version($latest_version).'</strong>')); ?> 
        </span>
<?php
	}
}
?>
        <h3><?php echo lang('XtraUpload v3 Stats'); ?></h3>
        <table border="0" style="width:98%">
          <tr>
            <td>
              <strong><?php echo lang('Number of Uploads'); ?>:</strong>
              <em><?php echo $this->db->count_all('refrence'); ?></em>
            </td>
            <td>
              <strong><?php echo lang('Total Disk Space Used'); ?>:</strong>
              <em><?php echo $this->functions->get_filesize_prefix($this->db->select_sum('size')->get('files')->row()->size); ?></em>
            </td>
          </tr>
          <tr>
            <td>
              <strong><?php echo lang('Number of Registered Users'); ?>:</strong>
              <em><?php echo $this->db->count_all('users'); ?></em>
            </td>
            <td>
              <strong><?php echo lang('Total Bandwidth Used'); ?>:</strong>
              <em><?php echo $this->functions->get_filesize_prefix($this->db->select_sum('sent')->get('downloads')->row()->sent); ?></em>
            </td>
          </tr>
          <tr>
            <td>
              <strong><?php echo lang('Number of Admins'); ?>:</strong>
              <em><?php echo $this->db->select_sum('id', 'count')->get_where('users', array('group' => '2'))->row()->count; ?></em>
            </td>
            <td>
              <strong><?php echo lang('Number of Active Servers'); ?>:</strong>
              <em><?php echo $this->db->select_sum('id', 'count')->get_where('servers', array('status' => '1'))->row()->count; ?></em>
            </td>
          </tr>
        </table>
        <h3><?php echo lang('Server Stats'); ?></h3>
        <table border="0" style="width:98%">
<?php
$servers = $this->db->get('servers');
foreach ($servers->result() as $serv)
{
	if($serv->total_space == 0)
	{
		continue;
	}
	$used_space_percent = (($serv->used_space / $serv->total_space) * 100);
	$free_space_percent = ($used_space_percent - 100) * (-1);
	$free_space = $this->functions->get_filesize_prefix($serv->free_space);
	$total_space = $this->functions->get_filesize_prefix($serv->total_space);
	$used_space = $this->functions->get_filesize_prefix($serv->used_space);
?>
          <tr>
            <td>
              <h3 style="font-size:16px; padding:2px; margin:0">
                <img class="nb" src="<?php echo base_url(); ?>img/other/server_16.png" alt="">
                <a href="<?php echo site_url('admin/server/edit/'.$serv->id); ?>"><?php echo ucfirst($serv->name); ?></a>
                (<?php echo $serv->url; ?>)
              </h3>
              <div class="progress_border" style="margin-left:2px; width:99%;">
                <div class="progress_img_sliver" style="width:<?php echo round($used_space_percent); ?>%;"><?php echo $used_space; ?> <?php echo lang('Used'); ?></div>
                <div class="progress_img_blank" style="width:<?php echo round($free_space_percent); ?>%;"><?php echo $free_space; ?> <?php echo lang('Free'); ?></div>
              </div>
              <h4 style="padding:4px;margin-top:4px;">
                <?php echo lang('Total Disk Space'); ?>: <?php echo $total_space; ?><br>
                <?php echo lang('Files on Server'); ?>: <?php echo $serv->num_files; ?><br>
              </h4>
            </td>
          </tr>
<?php
}
?>
        </table>
        <h3><?php echo lang('Useful Information'); ?></h3>
        <p>
          <?php printf(lang('You are using the %s with %s.'), anchor('admin/skin/view', sprintf(lang('%s skin'), '<strong>'.ucwords(str_replace('_', ' ', $this->startup->skin)).'</strong>')), anchor('admin/extend/view', sprintf(lang('%s plugins'), $this->db->get_where('extend', array('active' => 1))->num_rows()))); ?><br>
          <?php echo lang('This is XtraUpload version'); ?> <strong><?php echo XU_VERSION_READ?></strong>.
        </p>
        <h3><?php echo lang('Last 5 Admin Logins'); ?></h3>
        <table border="0" style="width:98%">
          <tr>
            <th><?php echo lang('Username'); ?></th>
            <th><?php echo lang('IP Address'); ?></th>
            <th><?php echo lang('Date'); ?></th>
            <th><?php echo lang('Valid'); ?></th>
          </tr>
<?php
$logins = $this->admin_logger->get_logs(5);
foreach ($logins->result() as $log)
{
?>
          <tr>
            <td>
              <?php echo anchor('admin/user/edit/'.$log->user, ucfirst($log->user_name)); ?> 
            </td>
            <td>
              <?php echo $log->ip; ?>
            </td>
            <td>
              <?php echo unix_to_human($log->date); ?>
            </td>
            <td>
<?php
	if ($log->valid == 1)
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/ok_16.png" class="nb" alt="">
<?
	}
	else
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/cancel_16.png" class="nb" alt="">
<?php
	}
?>
            </td>
          </tr>
<?php
}
?>
        </table>

        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('User Package Comparison')?> 
        </h2>
        <p>
          <?php echo lang('Listed below are all of our available signup packages. You can pick which one you want on the user registration page.'); ?> 
          <table border="0" style="width:98%">
<?php
$i=0;
$names = array(
	'speed_limit' => lang('Download Speed Limit'),
	'upload_size_limit' => lang('Upload File Size'),
	'wait_time' => lang('Download Wait Time'),
	'download_captcha' => lang('Captcha on Download?'),
	'auto_download' => lang('Direct Links?'),
	'upload_num_limit' => lang('Mass File Upload Limit'),
	'storage_limit' => lang('Account Storage Limits'),
	'repeat_billing' => lang('Billing Schedule'),
	'price' => lang('Price'),
	'file_expire' => lang('Active File Storage Time'),
	'can_search' => lang('Can Search Files'),
	'can_flash_upload' => lang('Can Upload Local Files'),
	'can_url_upload' => lang('Can URL Upload'),
);
$skip = array('id', 'status', 'descr', 'files_types', 'file_types_allow_deny', 'admin');
foreach ($group1 as $name => $value)
{
	if(in_array($name, $skip))
	{
		continue;
	}
?>
            <tr>
<?php
	if($i==0)
	{
?>
              <th><?php echo lang('Feature'); ?></th>
<?php ++$i; ?>
<?php
	}
	else
	{
?>
              <td><?php echo $names[$name]; ?></td>
<?php
	}
	$groups = $this->db->select($name)->get_where('groups', array('id !=' => 2, 'id !=' => 1, 'status' => '1'));
	foreach ($groups->result() as $group)
	{
		if($name == 'name')
		{
?>
              <th><?php echo $group->$name; ?></th>
<?php
		}
		elseif($name == 'wait_time')
		{
?>
              <td><?php printf(lang('%s Second(s)'), $group->$name); ?></td>
<?php
		}
		elseif($name == 'file_expire')
		{ // SHOULD BE CLEANED BETTER AND CHANGE FOR LANGUAGE.
			if($group->$name > 0)
			{
				$strings = sprintf(lang('%s Day(s)'), $group->$name);
			}
			else
			{
				$strings = lang('Forever!');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'price')
		{
			if($group->$name <= 0.00)
			{
				$strings = lang('Free!');
			}
			else
			{
				$strings = sprintf(lang('$%s'), $group->$name);
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'can_url_upload')
		{
			if($group->$name)
			{
				$strings = lang('Yes');
			}
			else
			{
				$strings = lang('No');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'can_flash_upload')
		{
			if($group->$name)
			{
				$string = lang('Yes');
			}
			else
			{
				$strings = lang('No');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'download_captcha')
		{
			if($group->$name)
			{
				$string = lang('Yes');
			}
			else
			{
				$strings = lang('No');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'auto_download')
		{
			if($group->$name)
			{
				$string = lang('Yes');
			}
			else
			{
				$strings = lang('No');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'can_search')
		{
			if($group->$name)
			{
				$string = lang('Yes');
			}
			else
			{
				$strings = lang('No');
			}
?>
              <td><?php echo $strings; ?></td>
<?php
		}
		elseif($name == 'speed_limit')
		{
?>
              <td><?php printf(lang('%s KBps'), $group->$name); ?></td>
<?php
		}
		elseif($name == 'repeat_billing')
		{
			$bs = array(
				'' => lang('None'),
				'0' => lang('None'),
				'd' => lang('Daily'),
				'w' => lang('Weekly'),
				'm' => lang('Monthly'),
				'y' => lang('Yearly'),
				'dy' => lang('Every 2 Years')
			);
?>
              <td><?php echo $bs[$group->repeat_billing]; ?></td>
<?php
		}
		elseif($name == 'upload_size_limit')
		{
?>
              <td><?php printf(lang('%s MB'), $group->$name); ?></td>
<?php
		}
		elseif($name == 'storage_limit')
		{
			if(intval($group->$name == 0))
			{
				$group->$name = lang('Unlimited');
			}
?>
              <td><?php printf(lang('%s MB'), $group->$name); ?></td>
<?php
		}
		elseif($name == 'upload_num_limit')
		{
?>
              <td><?php printf(lang('%s Files'), $group->$name); ?></td>
<?php
		}
	}
?>
            </tr>
<?php
}
?>
          </table>
        </p>

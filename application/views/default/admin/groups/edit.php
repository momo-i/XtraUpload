        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_group_32.png" class="nb" alt="">
          <?php printf(lang('"%s" Group - Edit'), $group->name); ?> 
        </h2>
        <br>
        <?php echo generate_link_button(lang('Manage Groups'), site_url('admin/group/view'), base_url().'img/icons/back_16.png'); ?><br>
        <form action="<?php echo site_url('admin/group/edit/'.$group->id); ?>" method="post">
          <h3><?php echo lang('Edit Group'); ?>: <?=ucwords($group->name)?></h3>
          <p>
<?php 
foreach ($group as $name => $value)
{
	if($name == 'id' or $name == 'status')
	{
		continue;
	}
	// ------------------------------
?>
            <label style="font-weight:bold" for="<?php echo $name; ?>">
              <?php echo $real_name[$name]; ?>
              <img src="<?php echo base_url(); ?>img/icons/about_16.png" style="cursor:pointer" onclick="$('#d_<?php echo $name; ?>').slideToggle()" class="nb">
            </label>
<?php
	if($real_type[$name] == 'yesno')
	{
		$checked1 = ($value) ? ' checked="checked"' : "";
		$checked2 = (!$value) ? ' checked="checked"' : "";
?>
            <input type="radio" name="<?php echo $name; ?>"<?php echo $checked1; ?> value="1" size="50"> <?php echo lang('Yes'); ?><br>
            <input type="radio" name="<?php echo $name; ?>" <?php echo $checked2; ?> value="0" size="50"> <?php echo lang('No'); ?><br>
<?php
	}
	elseif($real_type[$name] == 'allowdeny')
	{
		$checked1 = ($value) ? ' checked="checked"' : "";
		$checked2 = (!$value) ? ' checked="checked"' : "";
?>
            <input type="radio" name="<?php echo $name; ?>" <?php echo $checked1; ?> value="1" size="50"> <?php echo lang('Allow'); ?><br>
            <input type="radio" name="<?php echo $name; ?>" <?php echo $checked2; ?> value="0" size="50"> <?php echo lang('Deny'); ?><br>
<?php
	}
	elseif(is_array($real_type[$name]))
	{
?>
            <select name="<?php echo $name; ?>">
<?php
		foreach ($real_type[$name] as $a_key => $a_val)
		{
			$selected = ($value == $a_key) ? ' selected="selected"' : '';
?>
              <option<?php echo $selected; ?> value="<?php echo $a_key; ?>"><?php echo $a_val; ?></option>
<?php
		}
?>
            </select><br>
<?php
	}
	elseif($real_type[$name] == 'area')
	{
?>
            <textarea name="<?php echo $name; ?>" rows="4" cols="40"><?php echo $value; ?></textarea>
<?php
	}
	else
	{
?>
            <input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" size="50"><br>
<?php
	}
?>
            <span style="display:none; text-decoration:underline; font-weight:bold" id="d_<?php echo $name; ?>"><?php echo $real_descr[$name]; ?></span><br>
<?php
}
?>
            <br>
            <?php echo generate_submit_button(lang('Save Changes'), base_url().'img/icons/ok_16.png', 'green'); ?>
            <br>
          </p>
        </form>

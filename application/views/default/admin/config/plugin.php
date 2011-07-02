        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/options_32.png" class="nb" alt="">
          <?php echo lang('Plugin Config Settings'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <form method="post" action="<?php echo site_url('admin/config/update'); ?>">
          <h3><?php printf(lang('%s Config'), ucwords(str_replace('_',' ', $name))); ?></h3>
          <table width="500" border="0">
<?php
if($num_rows <= 0)
{
?>
            <span class="alert"><?php echo lang('No Config Settings for this plugin.'); ?></span>
<?php
}
else
{
	foreach ($configs->result() as $config)
	{
?>
            <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
              <td width="150"><?php echo $config->description1; ?></td>
              <td width="350">
<?php
		if($config->type == 'text')
		{
?>
                <input type="text" name="<?php echo $config->name; ?>" id="<?php echo $config->name; ?>" value="<?php echo $config->value; ?>">
                <img src="<?php echo base_url(); ?>img/icons/about_16.png" style="cursor:pointer" onclick="$('#d_<?php echo $config->name; ?>').slideToggle()" class="nb">
                <span style="display:none" id="d_<?php echo $config->name; ?>"><?php echo $config->description2; ?></span>
<?php
		}
		elseif($config->type == 'box')
		{
?>
                <textarea rows="8" cols="40" name="<?php echo $config->name; ?>" id="<?php echo $config->name; ?>" ><?php echo $config->value; ?></textarea>
                <img src="<?php echo base_url(); ?>img/icons/about_16.png" style="cursor:pointer" onclick="$('#d_<?php echo $config->name; ?>').slideToggle()" class="nb">
                &nbsp;<span style="display:none" id="d_<?php echo $config->name; ?>"><?php echo $config->description2; ?></span>
<?php
		}
		elseif($config->type == 'color')
		{
?>
                <div id="color_<?php echo $config->id; ?>"></div>
                <input type="text" name="<?php echo $config->name; ?>" value="<?php echo $config->value; ?>" id="<?php echo $config->name; ?>">
                <?php echo $config->description2; ?>
                <script type="text/javascript">
                  //<![CDATA[
                  $("#color_<?php echo $config->id; ?>").farbtastic('<?php echo $config->name; ?>','<?php echo $config->value; ?>');
                  $("#color_<?php echo $config->id; ?>").css('background-color','<?php echo $config->value; ?>');
                  //--]]>
                </script>
                <br>
<?php
		}
		else
		{
			$description = $config->description2;
			$description = explode('|-|',$description);
			$checked1 = ($config->value == '1') ? ' checked="checked"' : "";
			$checked2 = ($config->value == '0') ? ' checked="checked"' : "";
?>
                <input type="radio" name="<?php echo $config->name; ?>" id="<?php echo $config->name; ?>" value="1"<?php echo $checked1; ?>>
                <?php echo $description[0]; ?><br>
                <input type="radio" name="<?php echo $config->name; ?>" id="<?php echo $config->name; ?>" value="0"<?php echo $checked2; ?>>
                <?php echo $description[1]; ?><br>
<?php
		}
?>
              </td>
            </tr>
<?php
	}
}
?>
          </table>
          <input type="hidden" name="valid" value="yes">
<?php
if($num_rows > 0)
{
	echo generate_submit_button(lang('Update'), base_url().'img/icons/ok_16.png', 'green');
}
else
{
	echo generate_link_button(lang('Go Back'), 'javascript:history.go(-1)', base_url().'img/icons/back_16.png');	}
?>
          <br>
        </form>

<?php

/* Forms */

echo form_open('admin/translator', '', $hidden );
echo '<p>';
echo form_label(lang('Select Your Language Segement'), 'lang_module');
foreach ( $master_modules as $master_module ) 
{	
	echo form_radio('lang_module', $master_module).' '.ucwords($master_module).'<br />';
}
echo '<br />';
echo generate_submit_button(lang('Next Step'), $base_url.'img/icons/forward_16.png', 'green').'<br /></p>';
echo form_close();
?>

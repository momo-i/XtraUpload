<?php

/* Forms */
echo form_open('admin/translator', '', $hidden );
echo '<p>';
echo form_label(lang('Select Your Language'), 'slave_lang');
foreach ( $languages as $language ) 
{
	//echo form_hidden('slave_lang', $language);
	echo form_radio('slave_lang', $language).' '.ucwords($language).'<br />';
}
echo '<br />';
echo generate_submit_button(lang('Next Step'), $base_url.'img/icons/forward_16.png', 'green').'<br /></p>';
echo form_close();
?>

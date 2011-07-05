<?php

echo form_open('admin/translator', '', $hidden );

?>

<table> 

<?php

echo '<tr>';
echo '<td class="translator_table_header">' . lang('Key') . '</td>';
echo '<td class="translator_table_header">' . ucwords( $master_lang ) . '</td>';
echo '<td class="translator_table_header">' . ucwords( $slave_lang ) . '</td>';
echo '</tr>';

foreach ( $module_data as $key => $line ) {
	echo '<tr valign="top">';
	if(mb_strlen($key) > 20)
	{
		$key = mb_substr($key, 0, 20)."...";
		$masterline = mb_substr($line['master'], 0, 20)."...";
	}
	else
	{
		$masterline = $line['master'];
	}
	
	echo '<tr valign="top">';
	echo '<td>' . htmlspecialchars($key) . '</td>';
	echo '<td>' . htmlspecialchars($masterline) . '</td>';

	$data = array(
	  'name'        => $post_uniquifier . $key,
	  'value'       => $line[ 'slave' ],
	  'size'        => '40',
	  'style'		=> 'margin-top:12px;'
	);
	if(mb_strlen($line['slave']) < 40)
	{
		echo '<td>' . form_input( $data );
	}
	else
	{
		$data['cols'] = 40;
		$data['rows'] = 12;
		echo '<td>' . form_textarea($data);
	}

	if ( strlen( $line[ 'error' ] ) > 0 ) {
		echo '<br /><span class="translator_error">' . $line[ 'error' ] . '</span>';
	}

	if ( strlen( $line[ 'note' ] ) > 0 ) {
		echo '<br /><span class="translator_note">' . $line[ 'note' ] . '</span>';
	}

	echo '</td>';
	echo '</tr>';
}

?>

</table>

<?php

echo form_hidden('save_lang', 'Save');

echo generate_submit_button(lang('Save Changes'), base_url().'img/icons/save_16.png').'<br /></p>';

echo form_close();
	
?>

<?php

echo form_open('admin/translator', '', $hidden );

?>

<table>

<?php

echo '<tr>';
echo '<td class="translator_table_header">' . lang('Key') . '</td>';
echo '<td class="translator_table_header"><strong>' . ucwords( $master_lang ) . '</strong></td>';
echo '<td class="translator_table_header">' . ucwords( $slave_lang ) . '</td>';
echo '</tr>';

foreach ( $module_data as $key => $line ) {
	echo '<tr>';
	echo '<td>' . $key . '</td>';
	echo '<td>' . htmlspecialchars( $line['master'] ) . '</td>';
	echo '<td>' . htmlspecialchars( $line['slave'] ) . '</td>';
	echo '</tr>';
}

?>

</table>

<?php

echo form_hidden('confirm_save_lang', 'Confirm' );

echo generate_submit_button(lang('Confirm Changes'), base_url().'img/icons/ok_16.png', 'green').'<br /></p>';

echo form_close();

?>

<table>
<?php

echo '<tr>';
echo '<td class="translator_table_header">' . ucwords( $slave_lang ) . '</td>';
echo '<td class="translator_table_header">' . ucwords( $lang_module ) . '</td>';
echo '</tr>';

?>
</table>
<p>
<?php echo generate_link_button(lang('Select Language'), site_url('admin/translator'), base_url().'img/icons/back_16.png').'<br />'?>
</p>

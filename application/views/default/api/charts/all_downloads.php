<?php
$rand = mt_rand();
?>
var chart<?php echo $rand; ?> = new FusionCharts("<?php echo base_url(); ?>flash/charts/Pie3D.swf", "ChartId", "<?php echo $height; ?>", "<?php echo $width; ?>", "0", "0");
chart<?php echo $rand; ?>.setDataXML("<chart caption='<?php lang('All Downloads'); ?>' showPercentageValues='0'><set label='<?php lang('Anonymous'); ?>' value='<?php echo $this->db->where('user', '0')->count_all_results('downloads'); ?>' /><set label='<?php lang('Registered'); ?>' value='<?php echo $this->db->where('user !=', '0')->count_all_results('downloads'); ?>' /></chart>");
chart<?php echo $rand; ?>.render("chart_data");

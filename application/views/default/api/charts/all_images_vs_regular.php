<?php
$rand = mt_rand();
?>
var chart<?php echo $rand; ?> = new FusionCharts("<?php echo base_url(); ?>flash/charts/Pie3D.swf", "ChartId", "<?php echo $height; ?>", "<?php echo $width; ?>", "0", "0");
chart<?php echo $rand; ?>.setDataXML("<chart caption='<?php echo lang('All Uploads >> Files vs Images'); ?>' showPercentageValues='0'><set label='<?php echo lang('Regular'); ?>' value='<?php echo $this->db->get_where('refrence', array('is_image' => false))->num_rows(); ?>' /><set label='<?php echo lang('Image'); ?>' value='<?php echo $this->db->get_where('refrence', array('is_image' => true))->num_rows(); ?>' /></chart>");
chart<?php echo $rand; ?>.render("chart_data");

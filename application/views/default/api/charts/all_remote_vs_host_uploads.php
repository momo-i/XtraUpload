<?php
$rand = mt_rand();
?>
var chart<?php echo $rand; ?> = new FusionCharts("<?php echo base_url(); ?>flash/charts/Pie3D.swf", "ChartId", "<?php echo $height; ?>", "<?php echo $width; ?>", "0", "0");
chart<?php echo $rand; ?>.setDataXML("<chart caption='<?php echo lang('Uploads >> Local vs Remote'); ?>' showPercentageValues='1'><set label='<?php echo lang('Local'); ?>' value='<?php echo $this->db->get_where('refrence', array('remote' => false))->num_rows(); ?>' /><set label='<?php echo lang('Remote'); ?>' value='<?php echo $this->db->get_where('refrence', array('remote' => true))->num_rows(); ?>' /></chart>");
chart<?php echo $rand; ?>.render("chart_data");

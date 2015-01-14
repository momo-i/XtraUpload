<?php
$rand = mt_rand();
$total_space = $this->db->select_sum('size')->get('files')->row()->size;
?>
var chart<?php echo $rand; ?> = new FusionCharts("<?php echo base_url(); ?>assets/flash/charts/Pie3D.swf", "ChartId", "<?php echo $height; ?>", "<?php echo $width; ?>", "0", "0");
chart<?php echo $rand; ?>.setDataXML("<chart caption='<?php echo lang('Servers >> Used Space'); ?>' showPercentageValues='1'><?php foreach($servers->result() as $server) { $server_space = $this->db->select_sum('size')->get_where('files', array('server' => $server->url))->row()->size; ?><set label='<?php echo $server->name; ?>' value='<?php echo round($server_space / 1024); ?>' /><?php } ?></chart>");
chart<?php echo $rand; ?>.render("chart_data");

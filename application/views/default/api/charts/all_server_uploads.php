<?php
$rand = mt_rand();
$total_files = $this->files_db->get_admin_num_files();
?>
var chart<?php echo $rand; ?> = new FusionCharts("<?php echo base_url(); ?>assets/flash/charts/Pie3D.swf", "ChartId", "<?php echo $height; ?>", "<?php echo $width; ?>", "0", "0");
chart<?php echo $rand; ?>.setDataXML("<chart caption='<?php echo lang('File-Server Uploads Distrubition'); ?>' showPercentageValues='1'><?php foreach($servers->result() as $server) { $server_files = $this->db->get_where('files', array('server' => $server->url))->num_rows(); ?><set label='<?php echo $server->name; ?>' value='<?php echo $server_files; ?>' /><?php } ?></chart>");
chart<?php echo $rand; ?>.render("chart_data");

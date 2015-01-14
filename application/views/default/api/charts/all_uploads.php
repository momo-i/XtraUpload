<?php
$registered = $this->db->get_where('refrence', array('user' => '0'))->num_rows();
$anonym = $this->db->get_where('refrence', array('user !=' => '0'))->num_rows();
?>
$('#chart_title').html('<?php echo lang('All uploads'); ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ["<?php echo lang('Anonymous'); ?>", <?php echo $anonym; ?>],
      ["<?php echo lang('Registered'); ?>", <?php echo $registered; ?>],
    ],
    type: 'pie',
  },
  axis: {
    x: {
      label: ''
    },
    y: {
      label: ''
    }
  },
  legend: {
    show: false
  }
});

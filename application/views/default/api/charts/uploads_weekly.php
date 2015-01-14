$('#chart_title').html('<?php echo lang('Past 7 Days Uploads'); ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ['<?php $d = '-6'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db ->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php $d = '-5'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php $d = '-4'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php $d = '-3'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php $d = '-2'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php $d = '-1'; echo date('Y-m-d', strtotime($d.' days')); ?>', <?php echo $this->db->get_where('refrence', array('time >' => strtotime($d.' days 12:00 AM'), 'time <' => strtotime($d.' days 11:59:59 PM')))->num_rows(); ?>],
      ['<?php echo date('Y-m-d', strtotime('today')); ?>',<?php echo $this->db->get_where('refrence', array('time >' => strtotime('today 12:00 AM'), 'time <' => strtotime('today 11:59:59 PM')))->num_rows(); ?>]
    ],
    type: 'bar',
  },
  axis: {
    x: {
      show: false,
      type: 'categorized'
    }
  },
  bar: {
    width: {
      ratio: 0.3,
    },
  }
});


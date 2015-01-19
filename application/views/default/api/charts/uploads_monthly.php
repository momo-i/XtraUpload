$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    x: 'x',
    columns: [
      ['x', '<?php echo $week3['d']; ?>', '<?php echo $week2['d']; ?>', '<?php echo $week1['d']; ?>', '<?php echo $thisweek['d']; ?>'],
      ['<?php echo lang('All uploads'); ?>', <?php echo $week3['num']; ?>, <?php echo $week2['num']; ?>, <?php echo $week1['num']; ?>, <?php echo $thisweek['num']; ?>]
    ],
    groups: [['<?php echo lang('All uploads'); ?>']],
    type: 'bar',
  },
  axis: {
    x: {
      type: 'categorized',
      label: '<?php echo $xlabel; ?>'
    },
    y: {
      position: 'outer-middle',
      text: '<?php echo $ylabel; ?>'
    }
  },
  bar: {
    width: {
      ratio: 0.3,
    },
  },
});

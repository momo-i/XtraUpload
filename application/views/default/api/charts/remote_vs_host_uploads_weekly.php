$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    x: 'x',
    columns: [
      ['x', '<?php echo $day6['d']; ?>', '<?php echo $day5['d']; ?>', '<?php echo $day4['d']; ?>', '<?php echo $day3['d']; ?>', '<?php echo $day2['d']; ?>', '<?php echo $day1['d']; ?>', '<?php echo $today['d']; ?>'],
      ['<?php echo lang('Local'); ?>', <?php echo $local['day6']['num']; ?>, <?php echo $local['day5']['num']; ?>, <?php echo $local['day4']['num']; ?>, <?php echo $local['day3']['num']; ?>, <?php echo $local['day2']['num']; ?>, <?php echo $local['day1']['num']; ?>, <?php echo $local['today']['num']; ?>],
      ['<?php echo lang('Remote'); ?>', <?php echo $remote['day6']['num']; ?>, <?php echo $remote['day5']['num']; ?>, <?php echo $remote['day4']['num']; ?>, <?php echo $remote['day3']['num']; ?>, <?php echo $remote['day2']['num']; ?>, <?php echo $remote['day1']['num']; ?>, <?php echo $remote['today']['num']; ?>],
    ],
    groups: [['<?php echo lang('Local'); ?>', '<?php echo lang('Remote'); ?>']],
    type: 'bar'
  },
  axis: {
    x: {
      type: 'categorized',
      label: '<?php echo $xlabel; ?>'
    },
    y: {
      label: {
        position: 'outer-middle',
        text: '<?php echo $ylabel; ?>'
      }
    }
  },
  bar: {
    width: {
      ratio: 0.3,
    },
  },
});

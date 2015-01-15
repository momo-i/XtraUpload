$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    x: 'x',
    columns: [
      ['x', '<?php echo $day6['d']; ?>', '<?php echo $day5['d']; ?>', '<?php echo $day4['d']; ?>', '<?php echo $day3['d']; ?>', '<?php echo $day2['d']; ?>', '<?php echo $day1['d']; ?>', '<?php echo $today['d']; ?>'],
      ['<?php echo lang('Image'); ?>', <?php echo $images['day6']['num']; ?>, <?php echo $images['day5']['num']; ?>, <?php echo $images['day4']['num']; ?>, <?php echo $images['day3']['num']; ?>, <?php echo $images['day2']['num']; ?>, <?php echo $images['day1']['num']; ?>, <?php echo $images['today']['num']; ?>],
      ['<?php echo lang('Regular'); ?>', <?php echo $regular['day6']['num']; ?>, <?php echo $regular['day5']['num']; ?>, <?php echo $regular['day4']['num']; ?>, <?php echo $regular['day3']['num']; ?>, <?php echo $regular['day2']['num']; ?>, <?php echo $regular['day1']['num']; ?>, <?php echo $regular['today']['num']; ?>],
    ],
    groups: [['<?php echo lang('Image'); ?>', '<?php echo lang('Regular'); ?>']],
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

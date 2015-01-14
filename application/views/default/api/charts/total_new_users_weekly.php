$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    x: 'x',
    columns: [
      ['x', '<?php echo $day6['d']; ?>', '<?php echo $day5['d']; ?>', '<?php echo $day4['d']; ?>', '<?php echo $day3['d']; ?>', '<?php echo $day2['d']; ?>', '<?php echo $day1['d']; ?>', '<?php echo $today['d']; ?>'],
      ['New users', <?php echo $day6['num']; ?>, <?php echo $day5['num']; ?>, <?php echo $day4['num']; ?>, <?php echo $day3['num']; ?>, <?php echo $day2['num']; ?>, <?php echo $day1['num']; ?>, <?php echo $today['num']; ?>]
    ],
    groups: [['New users']],
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
  legend: {
    show: true
  }
});

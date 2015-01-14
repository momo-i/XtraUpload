$('#chart_title').html('<?php echo lang('Past 7 Days Uploads'); ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ['<?php echo $data['6days']['d']; ?>', <?php echo $data['6days']['num']; ?>],
      ['<?php echo $data['5days']['d']; ?>', <?php echo $data['5days']['num']; ?>],
      ['<?php echo $data['4days']['d']; ?>', <?php echo $data['4days']['num']; ?>],
      ['<?php echo $data['3days']['d']; ?>', <?php echo $data['3days']['num']; ?>],
      ['<?php echo $data['2days']['d']; ?>', <?php echo $data['2days']['num']; ?>],
      ['<?php echo $data['1day']['d']; ?>', <?php echo $data['1day']['num']; ?>],
      ['<?php echo $data['today']['d']; ?>',<?php echo $data['today']['num']; ?>]
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
  },
  legend: {
    show: false
  }
});

function toggle(id) {
    chart.toggle(id);
}

d3.select('.container').insert('div', '.chart_data').attr('class', 'legend_uploads_weekly').selectAll('span')
    .data(['<?php echo $data['6days']['d']; ?>', '<?php echo $data['5days']['d']; ?>', '<?php echo $data['4days']['d']; ?>', '<?php echo $data['3days']['d']; ?>', '<?php echo $data['2days']['d']; ?>', '<?php echo $data['1day']['d']; ?>', '<?php echo $data['today']['d']; ?>'])
  .enter().append('span')
    .attr('data-id', function (id) { return id; })
    .html(function (id) { return id; })
    .each(function (id) {
        d3.select(this).style('background-color', chart.color(id));
    })
    .on('mouseover', function (id) {
        chart.focus(id);
    })
    .on('mouseout', function (id) {
        chart.revert();
    })
    .on('click', function (id) {
        chart.toggle(id);
    });


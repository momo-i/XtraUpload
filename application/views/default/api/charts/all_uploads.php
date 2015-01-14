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
  legend: {
    show: false
  }
});

function toggle(id) {
    chart.toggle(id);
}

d3.select('.container').insert('div', '.chart_data').attr('class', 'legend_all_uploads').selectAll('span')
    .data(['<?php echo lang('Anonymous'); ?>', '<?php echo lang('Registered'); ?>'])
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

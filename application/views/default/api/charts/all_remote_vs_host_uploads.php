$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ["<?php echo $local['name']; ?>", <?php echo $local['num']; ?>],
      ["<?php echo $remote['name']; ?>", <?php echo $remote['num']; ?>],
    ],
    type: 'pie',
  },
});

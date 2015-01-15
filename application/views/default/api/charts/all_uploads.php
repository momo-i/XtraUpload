$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ["<?php echo $anonymous['name']; ?>", <?php echo $anonymous['num']; ?>],
      ["<?php echo $registered['name']; ?>", <?php echo $registered['num']; ?>],
    ],
    type: 'pie',
  },
});

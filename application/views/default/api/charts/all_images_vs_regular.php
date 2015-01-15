$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
      ["<?php echo $regular['name']; ?>", <?php echo $regular['num']; ?>],
      ["<?php echo $image['name']; ?>", <?php echo $image['num']; ?>],
    ],
    type: 'pie',
  },
});

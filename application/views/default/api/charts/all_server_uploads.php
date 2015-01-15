$('#chart_title').html('<?php echo $title; ?>');
var chart = c3.generate({
  bindto: '#chart_data',
  data: {
    columns: [
<?php
foreach($servers as $server)
{
?>
      ['<?php echo $server['name']; ?>', '<?php echo $server['num']; ?>'],
<?php
}
?>
    ],
    type: 'pie',
  },
});

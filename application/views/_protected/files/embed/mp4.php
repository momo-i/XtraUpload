<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <style>
      *{padding:0; margin:0;}
    </style>
    <title><?php echo lang('MP4 Embed'); ?></title>
    <script src="<?php echo base_url(); ?>players/jquery.js"></script>
    <script src="<?php echo base_url(); ?>players/mediaelement-and-player.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>players/mediaelementplayer.min.css">
  </head>
  <body>
    <video id="player2" src="<?php echo site_url('files/stream/'.$file->file_id.'/'.md5($this->config->config['encryption_key'].$file->file_id.$this->input->ip_address()).'/'.$file->link_name); ?>" controls="controls" width="470" height="320" preload="none" type="video/mp4"></video> 
    <span id="player2-mode"></span>
    <script type="text/javascript">
      //<![CDATA[
      $('audio,video').mediaelementplayer({
        success: function(player, node) {
          $('#' + node.id + '-mode').html('mode: ' + player.pluginType);
        }
      });
      //--]]>
    </script>
  </body>
</html>

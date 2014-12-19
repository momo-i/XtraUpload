<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <style>
      *{padding:0; margin:0;}
    </style>
    <title><?php echo lang('MP3 Embed'); ?></title>
    <script src="<?php echo base_url(); ?>assets/players/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/players/mediaelement-and-player.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/players/mediaelementplayer.min.css">
  </head>
  <body>
    <audio id="player2" src="<?php echo site_url('files/stream/'.$file->file_id.'/'.md5($this->config->config['encryption_key'].$file->file_id.$this->input->ip_address()).'/'.$file->link_name); ?>" controls="controls" type="audio/mp3"></audio>
    <script type="text/javascript">
      //<![CDATA[
        $('audio').mediaelementplayer();
      //--]]>
    </script>
  </body>
</html>

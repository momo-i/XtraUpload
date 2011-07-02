<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <style>
      *{padding:0; margin:0;}
    </style>
    <title><?php echo lang('MP3 Embed'); ?></title>
    <script type="text/javascript">
      //<![CDATA[
      function ___imageClose(){return '<?php echo base_url(); ?>images/lightbox-btn-close.gif';}
      function ___imageLoading(){return '<?php echo base_url(); ?>images/loading.gif';}
      function ___baseUrl(){return '<?php echo base_url(); ?>';}
      //--]]>
    </script>
    <script src="<?php echo base_url(); ?>js/main.php" type="text/javascript"></script>
  </head>
  <body>
    <div id="player"></div>
    <script type="text/javascript">
      //<![CDATA[
      var so = new SWFObject('<?php echo base_url(); ?>flash/player.swf','mpl','470','20','9');
      so.addParam('allowscriptaccess','always');
      so.addParam('allowfullscreen','false');
      so.addParam('flashvars','file=<?php echo site_url('files/stream/'.$file->file_id.'/'.md5($this->config->config['encryption_key'].$file->file_id.$this->input->ip_address()).'/'.$file->link_name); ?>');
      so.write('player');
      //--]]>
    </script>
  </body>
</html>

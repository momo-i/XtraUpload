<?php
$language = isset($this->startup->locale) ? $this->startup->locale : "en_US";
?>
<!DOCTYPE html>
<html lang="<?php echo get_language($language); ?>">
  <head>
    <meta charset="utf-8">
    <title><?php echo lang('XtraUpload V3'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/install.css">
    <script type="text/javascript">function ___baseUrl(){return '';}</script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/main.php"></script>
  </head>
  <body>
    <div id="logostrip">
      <img src="<?php echo base_url(); ?>images/install/logo.png" alt="<?php echo lang('XtraUpload V3'); ?>" width="450" height="70" border="0">
    </div>
    <div class="fade"></div>
    <br>

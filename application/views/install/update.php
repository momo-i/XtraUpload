    <div class="centerbox">
      <div class="tableborder">
<?php
if(isset($error_message))
{
?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>css/main.css">
        <div><span class="alert"><?php echo $error_message; ?></span></div>
<?php
}
?>
        <div class="maintitle"><?php echo lang('Welcome to XtraUpload Updater'); ?></div> 
        <div class="pformstrip"><?php echo lang('Details about the system requirements are below.'); ?></div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td width="12%" valign="middle">
              <center><img style="vertical-align:middle;" src="<?php echo base_url(); ?>images/install/angle/update.png" width="128" height="128"></center>
            </td>
            <td width="88%">
              <p>
                <strong><?php printf(lang('Update %s from %s'),XU_VERSION_READ ,XU_DB_VERSION_READ); ?></strong>
              </p>
<?php
if($this->updated)
{
?>
              <div align="center">
                <span class="cssbutton">
                  <a class="buttonGreen" href="<?php echo site_url('install/update/do_update') ?>">
                    <img src="<?php echo base_url() ?>img/icons/ok_16.png" border="0" alt=""><?php echo lang('Continue'); ?>

                  </a>
                </span>
              </div>
<?php
}
else
{
?>
              <p><?php echo $flash_message; ?></p>
<?php
}
?>
            </td>
          </tr>
        </table>
      </div>
      <div class="fade"></div>
    </div>

    <div class="centerbox">
      <div class="tableborder">
        <div class="maintitle"><?php echo lang('Welcome to XtraUpload Installer'); ?></div> 
        <div class="pformstrip"><?php echo lang('Details about the system requirements are below.'); ?></div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td width="12%" valign="middle">
              <center><img style="vertical-align:middle;" src="<?php echo base_url() ?>images/install/angle/install.png" width="128" height="128"></center>
            </td>
            <td width="88%">
              <p>
                <?php echo lang('Thank you for Downloading XtraUpload.'); ?><br>
                <?php echo lang('We hope you find the installation of this script a breeze as it is all automated making it simple to use for someone with very limited knowledge of php, Installing etc.'); ?><br><br>
                <strong><?php echo lang('XtraUpload works best on PHP 5.3.0 or better, MySQL 5.1 or better, and an unused database.'); ?></strong><br>
                <?php echo lang('Please gather this information ready:'); ?>

                <ul>
                  <li><?php echo lang('SQL database name (Create on via cpanel or some other software you have)'); ?></li>
                  <li><?php echo lang('Your SQL username'); ?></li>
                  <li><?php echo lang('Your SQL host address (Usually localhost but if all else fails try the ip address)'); ?></li>
                </ul>
                <?php echo lang('Click continue to carry on with this installation. You will be given a progress bar to show you how much is left to install.'); ?><br>
                <strong><?php echo lang('Do not click back once you have reached step 5. This Will cause database errors!'); ?></strong>
              </p>
              <div align="center">
                <span class="cssbutton">
                  <a class="buttonGreen" href="<?php echo site_url('install/step/1') ?>">
                    <img src="<?php echo base_url() ?>img/icons/ok_16.png" border="0" alt=""><?php echo lang('Continue'); ?>

                  </a>
                </span>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <div class="fade"></div>
    </div>

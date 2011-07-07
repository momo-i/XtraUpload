    <div style="margin:auto; text-align:center;"><h1><?php echo lang('License Agreement'); ?></h1></div>
    <div class="progressMenu">
      <ul>
        <li class="current">
          <img src="<?php echo base_url(); ?>img/icons/about_16.png" border="0" alt="<?php printf(lang('Step %d'), 1); ?>">
          <?php printf(lang('Step %d'), 1); ?>

        </li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 2); ?></li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 3); ?></li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 4); ?></li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 5); ?></li>
      </ul>
    </div>
    <div class="centerbox">
      <div class="tableborder">
        <div class="maintitle">
          <?php echo lang('Welcome to XtraUpload Installer'); ?>
        </div>
        <div class="pformstrip">
          <?php echo lang('XtraUpload License Agreement Below'); ?>
        </div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td width="12%" valign="middle" style="text-align:middle">
              <img style="vertical-align:middle;" src="<?php echo base_url(); ?>images/install/angle/install.png" width="128" height="128">
            </td>
            <td width="88%">
              <p class="style2">
                <?php echo lang('You must agree to the XtraUpload License before installing XtraUpload.'); ?><br>
                <?php echo lang('By Installing XtraUpload, regardless if you read the License, you are bound by it.'); ?>

              </p>
              <?php printf(lang('An online copy of this license can be found at %s'), '<a href="http://www.opensource.org/licenses/Apache-2.0">http://www.opensource.org/licenses/Apache-2.0</a>'); ?><br>
              <textarea name="textarea" readonly="readonly" cols="80" rows="15" wrap="off" id="textarea">
                <?php echo file_get_contents(realpath(BASEPATH.'../LICENSE')); ?>
              </textarea>
              <p>
                <strong>
                  <?php echo lang('By clicking the "Continue" button below and/or installing/upgrading/using XtraUpload you hearby agree to and are bound by the above license aggrement, including Section 10: ammendments to the Apache License v2.0.'); ?>

                </strong>
              </p>
            </td>
          </tr>
        </table>
        <div class="pformstrip" style="text-align: center; vertical-align:middle;">
          <div style="float:left">
            <span class="cssbutton">
              <a class="buttonRed" href="<?php echo site_url('install/setup'); ?>">
                <img src="<?php echo base_url(); ?>img/icons/back_16.png" border="0" alt=""><?php echo lang('Go Back'); ?>

              </a>
            </span>
          </div>
          <div style="float:right">
            <span class="cssbutton">
              <a class="buttonGreen" href="<?php echo site_url('install/step/2'); ?>">
                <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt=""><?php echo lang('Continue'); ?>

              </a>
            </span>
          </div><br><br>
        </div>
      </div>
      <div class="fade"></div>
      <br>
    </div>

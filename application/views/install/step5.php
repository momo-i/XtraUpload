    <div style="margin:auto; text-align:center"><h1><?php lang('Complete!'); ?></h1></div>
    <div class="progressMenu">
      <ul>
        <li class="complete">
          <a href="<?php echo site_url('install/step/1') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 1); ?>">
            <?php printf(lang('Step %d'), 1); ?> 
          </a>
        </li>
        <li class="complete">
          <a href="<?php echo site_url('install/step/2') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 2); ?>">
            <?php printf(lang('Step %d'), 2); ?> 
          </a>
        </li>
        <li>&raquo;</li>
        <li class="complete">
          <a href="<?php echo site_url('install/step/3') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 3); ?>">
            <?php printf(lang('Step %d'), 3); ?> 
          </a>
        </li>
        <li>&raquo;</li>
        <li class="complete">
          <a href="<?php echo site_url('install/step/4') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 4); ?>">
            <?php printf(lang('Step %d'), 3); ?> 
          </a>
        </li>
        <li class="current">
          <img src="<?php echo site_url(); ?>img/icons/about_16.png" border="0" alt="<?php printf(lang('Step %d'), 5); ?>">
          <?php printf(lang('Step %d'), 5); ?> 
        </li>
      </ul>
    </div>
    <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>">
      <div class="centerbox">
        <div class="tableborder">
          <div class="maintitle"><?php echo lang('Success! XtraUpload Has Installed Succefully'); ?></div>
          <div class="pformstrip"><?php echo lang('Details About The Install Are Below'); ?></div>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="19%" valign="middle">
                <center>
                  <img src="<?php echo base_url(); ?>images/install/angle/checkmark.png" width="128" height="128">
                </center>
              </td>
              <td width="100%">
                <center>
                  <h1 style="color:#009900"><?php echo lang('XtraUpload was installed successfully!'); ?></h3>
                  <p><strong><?php echo lang('The admin login information is:'); ?></strong></p>
                  <p>
                    <strong><?php echo lang('Username:'); ?></strong> <?php echo $this->input->post('username')?><br>
                    <strong><?php echo lang('Password:'); ?></strong> <?php echo $this->input->post('password')?>
                  </p>
                </center>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div align="center" class="pformstrip" style="text-align:center; vertical-align:middle">
                  <div style="float:right">
                    <span class="cssbutton">
                      <a class="buttonGreen" href="<?php echo base_url() ?>" onclick="return $('#form1').validate().form();">
                        <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="">
                        <?php echo lang('Continue'); ?> 
                      </a>
                    </span>
                  </div>
                  <br><br>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="fade">&nbsp;</div>
        <br>
      </div>
    </form>

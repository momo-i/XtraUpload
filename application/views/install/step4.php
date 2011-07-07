    <div style="margin:auto; text-align:center"><h1><?php echo lang('Database and Config Details'); ?></h1></div>
    <div class="progressMenu">
      <ul>
        <li class="complete">
          <a href="<?php echo site_url('install/step/1') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 1); ?>">
            <?php printf(lang('Step %d'), 1); ?>

          </a>
        </li>
        <li>&raquo;</li>
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
        <li class="current">
          <img src="<?php echo base_url(); ?>img/icons/about_16.png" border="0" alt="<?php printf(lang('Step %d'), 4); ?>">
          <?php printf(lang('Step %d'), 4); ?>
        </li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 5); ?></li>
      </ul>
    </div>
    <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<?php echo site_url('install/step/5'); ?>">
      <input type="hidden" name="url" value="<?php echo $url?>">
      <input type="hidden" name="enc" value="<?php echo $enc?>">
      <div class="centerbox">
        <div class="maintitle"><?php echo lang('Admin Details'); ?></div>
        <div class="pformstrip"><?php echo lang('This section requires information to create your administration account. Please enter the data carefully!'); ?></div>
        <table width="100%" cellspacing="1">
          <tr>
            <td class="pformleftw">
              <strong><?php echo lang('Admin Username'); ?></strong>
            </td>
            <td class="pformright">
              <input class="required" minlength="4" maxlength="32" name="username" type="text" id="username" size="45">
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <strong><?php echo lang('Password'); ?></strong>
            </td>
            <td class="pformright">
              <input class="required" minlength="5" name="password" type="password" id="password" size="45">
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <strong><?php echo lang('Password Confirmation'); ?></strong>
            </td>
            <td class="pformright">
              <input class="required" minlength="5" equalTo="#password" name="pass_conf" type="password" id="pass_conf" size="45">
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <strong><?php echo lang('Email'); ?></strong>
              <div class="description"><?php echo lang('Optional'); ?></div>
            </td>
            <td class="pformright">
              <input class="email" name="email" type="text" id="name" size="45">
            </td>
          </tr>
        </table>
        <div class="pformstrip" style="text-align:center; vertical-align:middle">
          <div style="float:left">
            <span class="cssbutton">
              <a class="buttonRed" href="<?php echo site_url('install/step/3'); ?>">
                <img src="<?php echo base_url(); ?>img/icons/back_16.png" border="0" alt="<?php echo lang('Go Back'); ?>">
                <?php echo lang('Go Back'); ?>

              </a>
            </span>
          </div>
          <div style="float:right">
            <span class="cssbutton">
              <a class="buttonGreen" href="javascript:document.form1.submit();" onclick="return $('#form1').validate().form();">
                <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php echo lang('Continue'); ?>">
                <?php echo lang('Continue'); ?>

              </a>
            </span>
          </div>
          <br><br>
        </div>
      </div>
      <div class="fade"></div>
    </form>

    <div style="margin:auto; text-align:center"><h1><?php echo lang('Filesystem And Version Check'); ?></h1></div>
    <div class="progressMenu">
      <ul>
        <li class="complete">
          <a href="<?php echo site_url('install/step/1') ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 1); ?>">
            <?php printf(lang('Step %d'), 1); ?>

          </a>
        </li>
        <li>&raquo;</li>
        <li class="current">
          <img src="<?php echo base_url(); ?>img/icons/about_16.png" border="0" alt="<?php printf(lang('Step %d'), 2); ?>">
          <?php printf(lang('Step %d'), 2); ?>
        </li>
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
        <div class="maintitle"><?php echo lang('Server Test Results'); ?></div>
        <div class="pformstrip">
          <?php echo lang('This section outputs the results of a series of tests to ensure everything is configured correctly before installing.'); ?>

        </div>
        <table width="100%" cellspacing="1" id="perms">
          <tr>
            <td class="pformleftw" colspan="2">
              <span style="font-size:20px; font-weight:bold"><?php echo lang('Permission Checks'); ?></span>
            </td>
          </tr>
<?php foreach($pass_fail as $file => $arr): ?>
          <tr>
            <td class="pformleftw">
              <strong>
                <?php printf(lang('File: %s'), $file); ?><br>
                <?php printf(lang('Permissions Required: %s'), $arr['perm']) ?><br>
              </strong>
            </td>
            <td class="pformright">
              <?php echo $arr['html']; ?>
            </td>
          </tr>
<?php endforeach; ?>
          <tr>
            <td class="pformleftw" colspan="2">
              <span style="font-size:20px; font-weight:bold"><?php echo lang('Version Checks'); ?></span>
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <?php echo lang('PHP Version'); ?><br>
              <?php echo lang('Minimum Version:'); ?> <strong>v5.3.0</strong><br>
              <?php echo lang('Version Found:'); ?> <strong>v<?php echo phpversion()?></strong>
            </td>
            <td class="pformright">
              <?php echo $phpver; ?>

            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <?php echo lang('GD Version'); ?><br>
              <?php echo lang('Minimum Version:'); ?> <strong>v2</strong><br>
              <?php echo lang('Version Found:'); ?> <strong>v<?php echo $gd_ver; ?></strong>
            </td>
            <td class="pformright">
              <?php echo $gdmsg; ?>
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <?php echo lang('FreeType Support Check'); ?><br>
              <?php echo lang('Found:'); ?> <strong><?php echo $fts; ?></strong>
            </td>
            <td class="pformright">
              <?php echo $ftsmsg; ?>
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <?php echo lang('SimpleXML Support Check'); ?><br>
              <?php echo lang('Found:'); ?> <strong><?php echo $slf; ?></strong>
            </td>
            <td class="pformright">
              <?php echo $slfmsg; ?>
            </td>
          </tr>
          <tr>
            <td class="pformleftw">
              <?php echo lang('FTP Support Check'); ?><br>
              <?php echo lang('Found:'); ?> <strong><?php echo $ftpc; ?></strong>
            </td>
            <td class="pformright">
              <?php echo $ftpcmsg; ?>
            </td>
          </tr>
        </table>
        <div align="center" class="pformstrip" style="text-align:center; vertical-align:middle">
          <div style="float:left">
            <span class="cssbutton">
              <a class="buttonRed" href="<?php echo site_url('install/step/1'); ?>">
                <img src="<?php echo base_url(); ?>img/icons/back_16.png" border="0" alt="">
                <?php echo lang('Go Back'); ?>

              </a>
            </span>
          </div>
          <div style="float:right">
            <span class="cssbutton">
              <a class="buttonGreen" href="<?php echo site_url('install/step/3'); ?>">
                <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="">
                <?php echo lang('Continue'); ?>

              </a>
            </span>
          </div>
<?php if(!$is_chmod): ?>
          <span style="color: #0000FF;"><strong>
            <?php echo lang('There were errors during testing.'); ?><br>
            <?php echo lang('Please fix these errors before continuing.'); ?><br>
            <?php echo lang('If you chose to continue you do so at your own risk.'); ?><br>
          </strong></span>
<?php else: ?>
          <br><br>
<?php endif; ?>
          <div class="fade"></div>
        </div>
      </div>
    </div>

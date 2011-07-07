    <div style="margin:auto; text-align:center"><h1><?php echo lang('Database and Config Details'); ?></h1></div>
    <div class="progressMenu">
      <ul>
        <li class="complete">
          <a href="<?php echo site_url('install/step/1'); ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 1); ?>">
            <?php printf(lang('Step %d'), 1); ?>

          </a>
        </li>
        <li>&raquo;</li>
        <li class="complete">
          <a href="<?php echo site_url('install/step/2'); ?>">
            <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="<?php printf(lang('Step %d'), 2); ?>">
            <?php printf(lang('Step %d'), 2); ?>

          </a>
        </li>
        <li>&raquo;</li>
        <li class="current">
          <img src="<?php echo base_url(); ?>img/icons/about_16.png" border="0" alt="<?php printf(lang('Step %d'), 3); ?>">
          <?php printf(lang('Step %d'), 3); ?>
        </li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 4); ?></li>
        <li>&raquo;</li>
        <li><?php printf(lang('Step %d'), 5); ?></li>
      </ul>
    </div>
    <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<?php echo site_url('install/step/4') ?>">
      <div class="centerbox">
        <div class="tableborder">
          <div class="maintitle"><?php echo lang('Website Address'); ?></div>
          <div class="pformstrip"><?php echo lang('Enter your website address here.'); ?></div>
          <table width="100%" cellspacing="1">
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('Make sure this is correct:'); ?></strong>
                <div class="description">
                  <?php printf(lang('Please make sure it starts with %s This is the address of all your files EG: http://www.yoursite.com/upload'), '<strong>http://</strong>'); ?><br>
                  <strong><?php echo lang('Trailing slash is required!'); ?></strong>
                </div>
              </td>
              <td class="pformright">
                <input class="required" type="text" size="45" name="url" value="<?php echo $servername;?>">
              </td>
            </tr>
          </table>
        </div>
        <div class="fade"></div>
        <br>
        <div class="tableborder">
          <div class="maintitle"><?php echo lang('Website Settings'); ?></div>
          <div class="pformstrip"><?php echo lang('This allows you to set custom cookie prefixes and a custom encryption key. Leave blank to autogenerate these values.'); ?></div>
          <table width="100%" cellspacing="1">
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('Cookie Prefix'); ?></strong>
                <div class="description">
                  <?php echo lang('This prevents session collisions with other XU installs around the internet'); ?>

                </div>
              </td>
              <td class="pformrignt"><input type="text" size="45" name="cookie_prefix"></td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('Encryption Key'); ?></strong>
                <div class="description">
                  <?php echo lang('This is the key that will be used to encrypt sensitive data in XU'); ?>

                </div>
              </td>
              <td class="pformright"><input type="text" size="45" name="encryption_key"></td>
            </tr>
          </table>
        </div>
        <div class="fade"></div>
        <br>
        <div class="tableborder">
          <div class="maintitle"><?php echo lang('SQL Data'); ?></div>
          <div class="pformstrip"><?php echo lang('Please enter the correct MySQL info below for the tables to install correctly.'); ?></div>
          <table width="100%" cellspacing="1">
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('SQL Host'); ?></strong>
                <div class="description">
                  <?php echo lang('Localhost is default, but in some cases it can be your Server IP Address'); ?>

                </div>
              </td>
              <td class="pformright">
                <input class="required" name="sql_server" type="text" id="sql_server" value="localhost" size="45">
              </td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('SQL User'); ?></strong>
                <div class="description"></div>
              </td>
              <td class="pformright">
                <input class="required" name="sql_user" type="text" id="sql_user" size="45">
              </td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('SQL Database Name'); ?></strong>
              </td>
              <td class="pformright">
                <input class="required" name="sql_name" type="text" id="sql_name" size="45">
              </td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('SQL Password'); ?></strong>
              </td>
              <td class="pformright">
                <input class="required" name="sql_pass" type="password" id="sql_pass" size="45">
              </td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('Database Prefix'); ?></strong>
                <div class="description"><?php echo lang('Optional'); ?></div>
              </td>
              <td class="pformright">
                <input class="required" name="sql_prefix" type="text" id="sql_prefix" size="45" value="xu3_">
              </td>
            </tr>
            <tr>
              <td class="pformleftw">
                <strong><?php echo lang('SQL Engine'); ?></strong>
                <div class="description">
                  <?php echo lang('You can choose from 7 different SQL database engines. MySQL is the most popular.'); ?>

                </div>
                <td class="pformright">
                  <select name="sql_engine">
                    <option selected="selected" value="mysql">MySQL</option>
                    <option value="mysqli">MySQLi</option>
                    <option value=mssql"">MSSQL</option>
                    <option value="postgresql">PostgreSQL</option>
                    <option value="oci8">Oracle 8i</option>
                    <option value="odbc">ODBC</option>
                    <option value="sqlite">SQLite</option>
                  </select>
                </td>
              </tr>
            </table>
            <div class="pformstrip" style="text-align:center; vertical-align:middle">
              <div style="float:left">
                <span class="cssbutton">
                  <a class="buttonRed" href="<?php echo site_url('install/step/2') ?>">
                    <img src="<?php echo base_url(); ?>img/icons/back_16.png" border="0" alt="">
                    <?php echo lang('Go Back'); ?>

                  </a>
                </span>
              </div>
              <div style="float:right">
                <span class="cssbutton">
                  <a class="buttonGreen" href="javascript:document.form1.submit();" onclick="return $('#form1').validate().form();">
                    <img src="<?php echo base_url(); ?>img/icons/ok_16.png" border="0" alt="">
                    <?php echo lang('Continue'); ?>

                  </a>
                </span>
              </div>
              <br><br>
            </div>
          </div>
          <div class="fade"></div>
        </div>
      </div>
    </form>
    <script language="text/javascript">
      $(document).ready(function() {
        $("#form1").validate();
      });
    </script>

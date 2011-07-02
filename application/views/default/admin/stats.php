        <script type="text/javascript" src="<?php echo base_url(); ?>js/charts.js"></script>
        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/chart_32.png" class="nb" alt="">
          <?php echo lang('Site Stats'); ?> 
        </h2>
        <h3><?php echo lang('Information'); ?></h3>
        <p>
          <?php echo lang('Here you can view a graphical representation of many site stats including weekly upload count, new users, total used space by server, etc. Please select a chart to view the corresponding data.'); ?> 
        </p>
        <div>
          <select onchange="getChart('<?php echo site_url('api/charts/')?>/'+this.value+'/600/300')">
            <option value="null" selected="selected"><?php echo lang('Select a Chart'); ?></option>
            <optgroup label="<?php echo lang('Uploads'); ?>">
              <option value="all_uploads"><?php echo lang('All Uploads'); ?></option>
              <option value="uploads_weekly"><?php echo lang('All Uploads'); ?> &gt;&gt; <?php echo lang('Weekly'); ?></option>
              <option value="all_images_vs_regular"><?php echo lang('All Uploads'); ?> &gt;&gt; <?php echo lang('Files vs Images'); ?></option>
              <option value="all_remote_vs_host_uploads"><?php echo lang('All Uploads'); ?> &gt;&gt; <?php echo lang('Local vs Remote'); ?></option>
              <option value="all_server_uploads"><?php echo lang('All Uploads'); ?> &gt;&gt; <?php echo lang('By Server'); ?></option>
            </optgroup>
            <optgroup label="<?php echo lang('New Uploads'); ?>">
              <option value="images_vs_regular_weekly"><?php echo lang('New Uploads'); ?> &gt;&gt; <?php echo lang('Files vs Images'); ?> &gt;&gt; <?php echo lang('Weekly'); ?></option>
              <option value="remote_vs_host_uploads_weekly"><?php echo lang('New Uploads'); ?> &gt;&gt; <?php echo lang('Local vs Remote'); ?> &gt;&gt; <?php echo lang('Weekly'); ?></option>
            </optgroup>
            <optgroup label="<?php echo lang('Downloads'); ?>">
              <option value="all_downloads"><?php echo lang('All Downloads'); ?></option>
              <option value="downloads_weekly"><?php echo lang('All Downloads'); ?> &gt;&gt; <?php echo lang('Weekly'); ?></option>
            </optgroup>
            <optgroup label="<?php echo lang('Servers'); ?>">
              <option value="all_server_used_space"><?php echo lang('Servers'); ?> &gt;&gt; <?php echo lang('Used Space'); ?></option>
            </optgroup>
            <optgroup label="<?php echo lang('New Users'); ?>">
              <option value="total_new_users_weekly"><?php echo lang('New Users'); ?> &gt;&gt; <?php echo lang('Weekly'); ?></option>
            </optgroup>
          </select>
        </div>
        <h3 id="chart_name"><?php echo lang('Please select a Chart'); ?></h3>
        <p id="chart_data"></p>
        <script type="text/javascript">
          //<![CDATA[
          function getChart(chartUrl)
          {
            $('#chart_data').html('<img src="<?php echo base_url(); ?>images/loading.gif" class="nb">');
            $.ajax({type: 'GET', url: chartUrl+'/r_'+rand(1,999999999), cache: true, dataType: 'script'}); 
            $('#chart_name').html('<?php echo lang('Your Requested Chart'); ?>');
          }
          function rand(min, max)
          {
            // http://kevin.vanzonneveld.net
            // +   original by: Leslie Hoare
            // *     example 1: rand(1, 1);
            // *     returns 1: 1
            if(max)
            {
              return Math.floor(Math.random() * (max - min + 1)) + min;
            }
            else
            {
              return Math.floor(Math.random() * (min + 1));
            }
          }
          //--]]>
        </script>

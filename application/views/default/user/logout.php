        <h2 style="vertical-align:middle">
          <img alt="" class="nb" src="<?php echo base_url(); ?>img/icons/log_out_32.png">
          <?php echo lang('Logout'); ?> 
        </h2>
        <p><?php echo lang('You have been logged out. Please wait while we forward you to the home page.'); ?></p>
        <script type="text/javascript">
          //<![CDATA[
          function r()
          {
            location = '<?php echo site_url('home'); ?>';
          }
          setTimeout('r()',1000);
          //--]]>
        </script>

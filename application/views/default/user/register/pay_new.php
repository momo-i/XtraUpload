        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('Complete Payment'); ?> 
        </h2>
        <?php echo $form;?>
        <script type="text/javascript">
          //<![CDATA[
          function submitPayForm()
          {
            $('#gateway_form_submit').get(0).submit();
          }
          $(document).ready(function()
          {
            setTimeout('submitPayForm()', 2000);
          });
          //--]]>
        </script>

        <!-- main ends -->
        </div>
      <!-- sidebar starts -->
      <div id="sidebar">
<?php
if(stristr(uri_string(),'admin') !== FALSE)
{
	$this->load->view($skin.'/admin/menu');
}
else
{
	$this->load->view($skin.'/menu');
}
?>        
      <!-- sidebar ends -->
      </div>
    <!-- content-wrap ends-->
    </div>
    <!-- footer starts -->
    <div id="footer">
      <p>
        <?php printf(lang('&copy; Copyright %s'), '2006 - '.date('Y').' <strong>'.anchor('http://xtrafile.com/', lang('XtraFile')).'</strong>'); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php printf(lang('Design By: %s'), anchor('http://styleshout.com', 'styleshout')); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo lang('Valid')?> <?php echo anchor('http://jigsaw.w3.org/css-validator/check/referer', 'CSS'); ?> |
        <?php echo anchor('http://validator.w3.org/check/referer', 'HTML5'); ?> -
        <a href="javascript:;" onclick="$('#debug').toggle('fast')"><?php echo lang('Debug Info'); ?></a>
        <span style="color:#FF0000; display:none" id="debug">
          <?php printf(lang('Execution Time: %s sec'), $this->benchmark->elapsed_time()); ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?php printf(lang('Memory Usage: %d KB'), round(memory_get_usage() / 1024)); ?>
        </span>
      </p>
      <!-- footer ends-->
      </div>
    <!-- wrap ends here -->
    </div>
    <script type="text/javascript">
      //<![CDATA[
      $(document).ready(function()
      {
        $('input, select').bind('focus', function()
        {
          $(this).animate({backgroundColor:"#FFFFCC"}, "fast");
        });
        $('input, select').bind('blur', function()
        {
          $(this).animate({backgroundColor:"#FFFFFF"}, "fast");
        });
        $("a[@rel='external']").attr('target', '_blank');
        if($.browser.opera)
        {
          $(".pasteButton").remove();
        }
      });
      //--]]>
    </script>
<?php $this->load->view('_protected/global_footer'); ?>
  </body>
</html>

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
      <div id="footer-wrap">
        <div id="footer-content">
          <div id="footer-columns">    
            <div class="col3">
<?php
if($this->startup->site_config->show_recent_uploads)
{
?>
              <h3><?php echo lang('Recently Uploaded Files'); ?></h3>
              <ul class="col-list">
<?php
	$query = $this->files_db->get_recent_files(5);
	foreach ($query->result() as $file)
	{
		$links = $this->files_db->get_links('', $file);
?>
                <li>
                  <a href="<?php echo $links['down']; ?>">
                    <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
                    <?php echo $this->functions->elipsis($file->o_filename, 10); ?> 
                  </a>
                </li>
<?php
	}
?>
              </ul>
<?php
}
?>
            </div>
            <div class="col3-center">
              <h3 class="no-line">&nbsp;</h3>
            </div>
            <div class="col3">
              <h3><?php echo lang('About'); ?></h3>
              <p>
                <a href="http://xtrafile.com"><img src="<?php echo base_url(); ?>images/thumb.gif" width="50" height="50" alt="icon" class="float-left"></a>
                <?php printf(lang('%s is a next generation file hosting solution, blurring the lines between file hosting and ease of use.'), anchor('http://xtrafile.com/products/xtraupload-v2/', lang('XtraUpload v3'))); ?><br>
                <?php echo lang('Our revolutionary flash-based file uploader technology gets what you want done.'); ?><br>
                <?php echo lang('Upload up to 500 files at once, and get links to them all on the same page.'); ?><br>
                <?php printf(lang('%s is also pushing the envelope on extensibility. Built on the wonderful'), anchor('http://xtrafile.com/products/xtraupload-v2/', lang('XtraUpload v3')));?><br>
                <?php echo anchor('http://www.codeigniter.com/', lang('CodeIgniter')); ?><br>
                <?php echo lang('PHP Framework, XtraUpload is fully OpenSource and extendable.') ?><br>
                <?php echo lang('The documentation is extensive, concise, and clear.'); ?><br>
                <?php echo lang('Database abstraction, page caching, configurable downloads, secure file storage, secure file links, and so much more combine to create the new leader in file hosting technology, XtraUpload. Oh, and its Free.'); ?><br>
              </p>
            </div>
          <!-- footer-columns ends -->
          </div>    
          <div id="footer-bottom">
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
          </div>
        <!-- footer ends-->
        </div>
      </div>
      <!-- wrap ends here -->
      </div>
      <script type="text/javascript">
      //<![CDATA[
      $(document).ready(function()
      {
        $('input, select').css('background-color', '#FFFFFF');
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

        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/colors_32.png" class="nb" alt="">
          <?php echo lang('Skin Manager'); ?> 
        </h2>
        <?php echo $flash_message; ?>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-right">
            <?php echo generate_link_button(lang('Install New Skins'), site_url('admin/skin/installNew'), base_url().'img/icons/new_16.png', NULL); ?>
          </div>
        </div>
        <script type="text/javascript">
          //<![CDATA[
          /*
           * Image preview script 
           * powered by jQuery (http://www.jquery.com)
           * 
           * written by Alen Grakalic (http://cssglobe.com)
           * 
           * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
           *
           */
          this.imagePreview = function()
          {
            /* CONFIG */
            xOffset = 10;
            yOffset = 30;
            // these 2 variable determine popup's distance from the cursor
            // you might want to adjust to get the right result
            /* END CONFIG */
            $("a.preview").hover(function(e) {
              this.t = this.title;
              this.title = "";    
              var c = (this.t != "") ? "<br/>" + this.t : "";
              $("body").append('<p id="preview"><img class="nb" src="'+ this.href +'" alt="<?php echo lang('Image preview'); ?>">'+ c +'</p>');
              $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").fadeIn("fast");                        
            },
            function()
            {
              this.title = this.t;    
              $("#preview").remove();
            });    
            $("a.preview").mousemove(function(e) {
              $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
            });
          };
          // starting the script on page load
          $(document).ready(function() {
            imagePreview();
          });
          //--]]>
        </script>
        <style type="text/css">
        /*  */
        #preview {
          position:absolute;
          border:1px solid #ccc;
          background:#333;
          padding:5px;
          display:none;
          color:#fff;
        }
        /*  */
        </style>
        <table border="0" style="width:95%" id="file_list_table">
          <tr>
            <th class="align-left" style="width:90%"><?php echo lang('Skin name'); ?></th>
            <th style="text-align:center"><?php echo lang('Active?'); ?></th>
          </tr>
<?php
foreach ($skins->result() as $skin)
{
?>            
          <tr <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td style="font-size:12px; font-weight:bold; color:#006600">
<?php
	if($skin->active)
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/colors_16.png" class="nb" alt="">
<?php
	}
?>
              <a href="<?php echo base_url().'application/views/'.$skin->name.'/'.$skin->name; ?>.png" onclick="return false;" target="_blank" class="preview">
                <?php echo ucwords(str_replace('_', ' ', $skin->name)); ?>
              </a>
            </td>
            <td style="text-align:center">
<?php
	if(!$skin->active)
	{
?>
              <a title="<?php echo lang('Activate This Skin'); ?>" href="<?php echo site_url('admin/skin/set_active/'.$skin->name); ?>">
                <img src="<?php echo base_url(); ?>img/icons/off_16.png" class="nb" alt="<?php echo lang('Set Active'); ?>">
              </a>
<?php
	}
	else
	{
?>
              <img src="<?php echo base_url(); ?>img/icons/on_16.png" class="nb" alt="<?php echo lang('Is Active'); ?>">
<?php
	}
	if($skin->name != 'default')
	{
?>
              <a title="<?php echo lang('Delete This Skin'); ?>" href="<?php echo site_url('admin/skin/delete/'.$skin->name); ?>">
                <img src="<?php echo base_url(); ?>img/icons/close_16.png" class="nb" alt="<?php echo lang('Delete'); ?>">
              </a>
<?php
	}
?>
            </td>
          </tr>
<?php
}
?>
        </table>

        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/images_32.png" class="nb" alt="">
          <?php echo lang('Image Gallery - Create'); ?> 
        </h2>
<?php
if($files->num_rows() == 0)
{
?>
        <span class="alert">
          <?php echo lang('You do not have any uploaded images,')?> 
          <?php echo anchor('home', lang('please upload some first.'), array('style' => 'background:none;')); ?> 
        </span>
<?php
}
else
{
?>
        <form action="<?php echo site_url('image/process_new_gallery'); ?>" method="post">
          <p>
            <label for="name"><?php echo lang('Name:'); ?></label>
            <input id="name" size="75" type="text" name="name">
            <label for="desc"><?php echo lang('Description:'); ?></label>
            <textarea id="desc" rows="10" cols="62" name="desc"></textarea><br>
            <label><?php echo lang('Select Files:'); ?></label>
            <table border="0" width="100%" id="file_list_table">
              <tr>
                <th width="300" class="align-left"><?php echo lang('File Name'); ?></th>
                <th width="80"><?php echo lang('Size'); ?></th>
                <th width="60"><?php echo lang('Add?') ?> <input type="checkbox" value="ok" name="checkAll" onchange="switchCheckboxes(this.checked)"></th>
              </tr>
<?php
	foreach ($files->result() as $file)
	{
?>
              <tr>
                <td><?php echo anchor('files/get/'.$file->file_id.'/'.$file->link_name, $file->o_filename, array('target' => '_blank')); ?></td>
                <td><?php echo $this->functions->get_filesize_prefix($file->size); ?></td>
                <td>
                  <input type="checkbox" class="filec" value="<?php echo $file->file_id?>" name="files[]"> <?php echo lang('Add?'); ?>
                </td>
              </tr>
<?php
	}
?>
              <?php echo generate_submit_button(lang('Create Gallery'), base_url().'img/icons/new_16.png')?><br>
            </table>
          </p>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          var checkBoxAllBool = false;
          function switchCheckboxes()
          {
            if(!checkBoxAllBool)
            {
              $('input[@type=checkbox]', document).each(function(i)
              {
                $(this).attr('checked', 'checked');
              });
              checkBoxAllBool = true;
            }
            else
            {
              $('input[@type=checkbox]', document).each(function(i)
              {
                $(this).attr('checked', '');
              });
              checkBoxAllBool = false;
            }
          }
          //--]]>
        </script>
<?php
}
?>

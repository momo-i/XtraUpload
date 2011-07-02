        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/documents_32.png" class="nb" alt="">
          <?php echo lang('Edit File'); ?> 
        </h2>
        <form action="<?php echo site_url('admin/files/edit/'.$id); ?>" method="post">
          <input type="hidden" name="status" value="1">
          <p>
            <?php echo generate_link_button(lang('Manage Files'), site_url('admin/files/view'), base_url().'img/icons/back_16.png'); ?><br><br>
            <label for="o_filename"><?php echo lang('File Name'); ?></label>
            <input type="text" name="o_filename" value="<?php echo $file->o_filename; ?>">
            <label for="downloads"><?php echo lang('File Download'); ?></label>
            <input type="text" name="downloads" value="<?php echo $file->downloads; ?>">
            <label for="pass"><?php echo lang('File Password'); ?></label>
            <input type="text" name="pass" value="<?php echo $file->pass; ?>">
            <label for="descr"><?php echo lang('File Description'); ?></label>
            <textarea name="descr" cols="60" rows="6"><?php echo $file->descr; ?></textarea>
            <label for="featured"><?php echo lang('Featured?'); ?></label>
<?php
$checked = ($file->feature) ? ' checked="checked"' : "";
?>
            <input type="checkbox"<?php echo $checked?> name="feature" value="1">
            <br><br>
            <?php echo generate_submit_button(lang('Edit'), base_url().'img/icons/edit_16.png'); ?>
          </p>
        </form>

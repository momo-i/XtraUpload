        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/sticky_32.png" class="nb" alt="">
          <?php echo lang('Admin Shortcuts'); ?> 
        </h2>
        <form action="<?php echo site_url('admin/menu_shortcuts/add'); ?>" method="post">
          <input type="hidden" name="status" value="1">
          <input type="hidden" name="order" value="<?php echo $order; ?>">
          <h3><?php echo lang('Add Shortcut'); ?></h3>
          <p>
            <label for="title"><?php echo lang('Name'); ?></label>
            <input type="text" name="title" value="<?php echo $title; ?>">
            <label for="link"><?php echo lang('Link'); ?></label>
            <input type="text" name="link" value="<?php echo $link; ?>">
            <br><br>
            <?php echo generate_submit_button(lang('Add'), base_url().'img/icons/add_16.png'); ?><br>
          </p>
        </form>

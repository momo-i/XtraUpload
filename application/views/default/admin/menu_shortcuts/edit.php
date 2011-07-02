        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/sticky_32.png" class="nb" alt="">
          <?php echo lang('Admin Shortcuts'); ?> 
        </h2>
        <form action="<?php echo site_url('admin/menu_shortcuts/edit/'.$shortcut->id); ?>" method="post">
          <input type="hidden" name="status" value="1">
          <h3><?php echo lang('Edit Shortcut'); ?></h3>
          <p>
            <label for="o_filename"><?php echo lang('Name'); ?></label>
            <input type="text" id="o_filename" name="title" value="<?php echo $shortcut->title; ?>">
            <label for="o_link"><?php echo lang('Link'); ?></label>
            <input type="text" id="o_link" name="link" value="<?php echo $shortcut->link; ?>">
            <label for="o_order"><?php echo lang('Order'); ?></label>
            <input type="text" id="o_order" name="order" value="<?php echo $shortcut->order; ?>">
            <br><br>
            <?php echo generate_submit_button(lang('Edit'), base_url().'img/icons/edit_16.png'); ?><br>
          </p>
        </form>

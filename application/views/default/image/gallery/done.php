        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/images_32.png" class="nb" alt="">
          <?php echo lang('Image Gallery - Created'); ?> 
        </h2>
        <label><?php echo lang('Link:') ?></label>
        <input type="text" size="70" readonly="readonly" value="<?php echo site_url('image/gallery/'.$gid); ?>" onfocus="this.select()" onclick="this.select()" ondblclick="this.select()">

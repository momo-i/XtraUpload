        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/pictures_32.png" class="nb" alt="">
          <?php echo lang('Image Info'); ?> 
        </h2>
        <h3><?php echo lang('Name: ')?><?php echo $file->o_filename; ?></h3>
        <p>
          <?php echo $this->lang->line('Image Preview:'); ?><br>
          <a href="<?php echo $direct_url; ?>" class="thickbox">
            <img src="<?php echo $thumb_url; ?>" alt="">
          </a>
          <br><br>
          <?php echo lang('Download: ')?><a target="_blank" href="<?php echo $down; ?>"><?php echo $down; ?></a>
        </p>

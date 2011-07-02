        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/folder_32.png" class="nb" alt="">
          <?php echo lang('File Folder - Created'); ?> 
        </h2>
        <label><?php echo lang('Link:'); ?></label>
        <input type="text" size="70" readonly="readonly" value="<?php echo site_url('folder/view/'.$fid); ?>" onfocus="this.select()" onclick="this.select()" ondblclick="this.select()"><br><br>
        <label><?php echo lang('View File Folder Now'); ?></label>
        <?php echo anchor('folder/view/'.$fid, '', array('target' => '_blank')); ?><br><br>

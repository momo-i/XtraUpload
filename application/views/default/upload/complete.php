        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/backup_32.png" class="nb" alt="">
          <?php echo lang('Upload Complete'); ?> 
        </h2>
        <p><?php echo lang('Your file links are below, make sure to save this URL if you ever need to get these links again.'); ?></p>
        <label><?php echo lang('Download Link:'); ?></label>
        <input readonly="readonly" type="text" id="dl_<?php echo rand(); ?>" size="50" value="<?php echo $link['down']; ?>" onfocus="this.select()" onclick="this.select()">
<?php
if( ! $this->session->userdata('id'))
{
?>
        <br>
        <label><?php echo lang('Delete Link:'); ?></label>
        <input readonly="readonly" type="text" id="del_<?php echo rand(); ?>" size="50" value="<?php echo $link['del']; ?>" onfocus="this.select()" onclick="this.select()">
<?php
}
if(isset($link['img']))
{
?>
        <br>
        <label><?php echo lang('Image Links:'); ?></label>
        <a href="<?php echo $link['img']; ?>"><?php echo $link['img']; ?></a>
<?php
}
?>
        <div style="clear:both"></div><br>
        <?php echo generate_link_button(lang('Upload More Files'), site_url('home'), base_url().'img/icons/back_16.png', 'green'); ?>

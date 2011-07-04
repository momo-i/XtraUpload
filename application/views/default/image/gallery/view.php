        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/other/images_32.png" class="nb" alt="">
          <?php echo lang('Image Gallery') ?> 
        </h2>
        <h3><?php echo lang('Name'); ?>: <?php echo $gall->name; ?></h3>
        <pre><code><?php echo $gall->descr; ?></code></pre>
        <div id="gall">
<?php
foreach($gall_imgs->result() as $image)
{
?>
          <a href="<?php echo $image->direct; ?>" title="<?php echo lang('Download:') ?><a href='<?php echo str_replace('image/show', 'files/get', $image->view); ?>' target='_blank'><?php echo str_replace('image/show', 'files/get', $image->view); ?></a>" class="thickbox" rel="gal_<?php echo $id?>"><img src="<?php echo $image->thumb; ?>" alt="preview"></a>
<?php
}
?>
        </div>

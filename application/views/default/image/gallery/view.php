        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>assets/images/other/images_32.png" class="nb" alt="">
          <?php echo lang('Image Gallery') ?> 
        </h2>
        <h3><?php echo lang('Name'); ?>: <?php echo $gall->name; ?></h3>
        <pre><code><?php echo $gall->descr; ?></code></pre>
        <div id="gall">
<?php
foreach($gall_imgs->result() as $image)
{
	$file = $this->db->get_where('refrence', array('file_id' => $image->fid))->row();
?>
          <a href="<?php echo $image->direct."?type=.".$file->type; ?>" title="<?php echo lang('Download:') ?><a href='<?php echo str_replace('image/show', 'files/get', $image->view); ?>' target='_blank'><?php echo str_replace('image/show', 'files/get', $image->view); ?></a>" class="fancybox" rel="gal_<?php echo $id?>" data-fancybox-group="gallery"><img src="<?php echo $image->thumb; ?>" alt="preview"></a>
<?php
}
?>
        </div>

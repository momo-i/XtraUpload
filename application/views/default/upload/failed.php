<?php
if(isset($link['failed']) && $link['failed'] === TRUE)
{
?>
        <?php echo lang('Error: Upload Failed!!'); ?><br>
        <?php printf(lang('Reason: %s'), $link['reason']); ?>
<?php
}
elseif($link)
{
?>
        <?php echo lang('Download Link:'); ?> 
        <input readonly="readonly" type="text" id="dl_<?php echo rand(); ?>" size="50" value="<?php echo $link['down']; ?>" onfocus="this.select()" onclick="this.select()">
<?php
	if( ! $this->session->userdata('id'))
	{
?>
        <br>
        <?php echo lang('Delete Link:'); ?> 
        <input readonly="readonly" type="text" id="del_<?php echo rand(); ?>" size="50" value="<?php echo $link['del']; ?>" onfocus="this.select()" onclick="this.select()">
<?php
	}
	if(isset($link['img']))
	{
?>
        <br>
        <?php echo lang('Image Links:'); ?> 
        <a href="<?php echo $link['img']; ?>"><?php echo $link['img']; ?></a>
<?php
	}
}
else
{
?>
        <?php echo lang('Error: Upload Failed!!'); ?>
<?php
}
?>

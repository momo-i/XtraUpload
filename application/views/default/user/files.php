        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/search_32.png" class="nb" alt="">
          <?php printf(lang('User Files: %s'), $user->username); ?> 
        </h2>
<?php
if(!$user->public)
{
?>
        <span class="alert"><?php echo lang('This user does not allow public viewing of their files.'); ?></span>
<?
}
else
{
	$this->load->helper('string');
	$this->load->helper('date');
?>
        <div id="massActions" style="clear:both; padding-top:4px;">
          <div class="float-right"></div>
        </div>
        <div style=" clear:both;"></div>
        <?php echo $flash_message; ?> 
        <table border="0" style="width:95%" id="file_list_table">
          <tr>
            <th><?php echo lang('File name') ?></th>
            <th><?php echo lang('Size') ?></th>
            <th style="width:80px"><?php echo lang('Downloads') ?></th>
          </tr>
<?php
	foreach ($files->result() as $file)
	{
		$id = $file->id;
		$link = $this->files_db->get_links($file->secid);
?>
          <tr id="<?php echo $file->file_id; ?>" <?php echo alternator('class="odd"', 'class="even"'); ?>>
            <td>
              <a href="<?php echo site_url('files/get/'.$file->file_id.'/'.$file->link_name); ?>" target="_blank">
                <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
                <?php echo $file->o_filename; ?> 
              </a>
            </td>
            <td>
              <?php echo $this->functions->get_filesize_prefix($file->size); ?>
            </td>
            <td>
              <a href="javascript:;" onclick="$('#<?php echo $file->file_id; ?>-details').toggle()"><img src="<?php echo base_url(); ?>img/icons/about_16.png" title="<?php echo lang('Show/Hide Links'); ?>" class="nb"></a>
            </td>
          </tr>
          <tr class="details" style="display:none; border-top:none;" id="<?php echo $file->file_id; ?>-details">
            <td colspan="4" id="<?php echo $file->file_id; ?>-details-inner">
              <p>
                <img title="<?php echo lang('Download Link'); ?>" class="nb" alt="<?php echo lang('Download Link'); ?>:" src="<?php echo base_url(); ?>img/icons/link_16.png">
                <input class="down_link" readonly="readonly" type="text" size="65" value="<?php echo $link['down']; ?>" onfocus="this.select()" onclick="this.select()" ondblclick="this.select()"><br>
                <img title="<?php echo lang('Date Uploaded'); ?>" alt="<?php echo lang('Date Uploaded'); ?>:" class="nb" src="<?php echo base_url(); ?>img/icons/calendar_16.png">
                <em><?php echo unix_to_human($file->time); ?></em>
<?php
		if(isset($link['img']))
		{
?>
                <br>
                <img title="<?php echo lang('Image Links'); ?>" alt="<?php echo lang('Image Links'); ?>:" class="nb" src="<?php echo base_url(); ?>img/icons/pictures_16.png">
                <a href="<?php echo $link['img']; ?>"><?php echo $link['img']; ?></a>
<?php
		}
?>
              </p>
            </td>
          </tr>
<?php
	}
?>
        </table>
        <?php echo $pagination; ?>
<?php
}
?>


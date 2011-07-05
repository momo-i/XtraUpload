<?php
if($this->session->userdata('id'))
{
?>
        <h3><?php printf(lang('Welcome %s!'), $this->session->userdata('username')); ?></h3>
        <ul class="sidemenu">
          <li>
            <a href="<?php echo site_url('user/manage')?>">
              <img src="<?php echo base_url(); ?>img/icons/options_16.png" class="nb" alt=""> <?php echo lang('Manage Account')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/change_password'); ?>">
              <img src="<?php echo base_url(); ?>img/icons/security_16.png" class="nb" alt=""> <?php echo lang('Change Password')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/logout'); ?>">
              <img src="<?php echo base_url(); ?>img/icons/log_in_16.png" class="nb" alt=""> <?php echo lang('Logout')?>
            </a>
          </li>
        </ul>
<?
}
else
{
?> 
        <h3><?php echo lang('Member Login'); ?></h3>
        <form action="<?php echo site_url('user/login'); ?>" method="post" class="loginform">
          <input type="hidden" name="submit" value="1">
          <p>
            <label for="username"><strong><?php echo lang('Username:')?></strong></label>
            <input style="background:2px center url(<?php echo base_url(); ?>img/icons/user_16.png) no-repeat transparent; padding-left:22px" type="text" id="username" name="username">
            <label for="password"><strong><?php echo lang('Password:')?></strong></label>
            <input style="background:2px center url(<?php echo base_url(); ?>img/other/key_16.png) no-repeat transparent; padding-left:22px" type="password" id="password" name="password"><br><br>
            <?php echo generate_submit_button(lang('Login'), base_url().'img/icons/log_in_16.png', 'green'); ?><br>
          </p>
        </form>
        <ul class="sidemenu">
          <li>
            <a href="<?php echo site_url('user/forgot_password'); ?>">
              <img src="<?php echo base_url(); ?>img/icons/help_16.png" class="nb" alt=""> <?php echo lang('Forgot Your Password?')?>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/register'); ?>">
                <img src="<?php echo base_url(); ?>img/other/user-add_16.png" class="nb" alt=""> <?php echo lang('New? Register Here!')?>
            </a>
          </li>
        </ul>
<?php
}
?>
        <?php echo $this->xu_api->menus->get_sub_menu();?>
<?php
if($this->startup->site_config->show_recent_uploads)
{
?>
        <h3><?php echo lang('Recently Uploaded Files')?></h3>
        <ul class="sidemenu">
<?php
	$query = $this->files_db->get_recent_files(5);
	foreach ($query->result() as $file)
	{
		$links = $this->files_db->get_links('', $file);
?>
          <li>
            <a href="<?php echo $links['down']; ?>">
              <img src="<?php echo base_url().'img/files/'.$this->functions->get_file_type_icon($file->type); ?>" class="nb" alt="">
              <?php echo $this->functions->elipsis($file->o_filename, 10); ?> 
            </a>
          </li>
<?php
	}
?>
        </ul>
<?php
}
?>
        <h3><?php echo lang('About'); ?></h3>
        <p>
          <a href="http://xtrafile.com"><img src="<?php echo base_url(); ?>images/thumb.gif" width="50" height="50" alt="icon" class="float-left"></a>
          <a href="http://xtrafile.com/products/xtraupload-v2/"><?php echo lang('XtraUpload v3'); ?></a>
          <?php printf(lang('%s is a next generation file hosting solution, blurring the lines between file hosting and ease of use.'), anchor('http://xtrafile.com/products/xtraupload-v2/', lang('XtraUpload v3'))); ?><br>
          <?php echo lang('Our revolutionary flash-based file uploader technology gets what you want done.'); ?><br>
          <?php echo lang('Upload up to 500 files at once, and get links to them all on the same page.'); ?><br>
          <?php printf(lang('%s is also pushing the envelope on extensibility. Built on the wonderful'), anchor('http://xtrafile.com/products/xtraupload-v2/', lang('XtraUpload v3')));?><br>
          <?php echo anchor('http://www.codeigniter.com/', lang('CodeIgniter')); ?><br>
          <?php echo lang('PHP Framework, XtraUpload is fully OpenSource and extendable.') ?><br>
          <?php echo lang('The documentation is extensive, concise, and clear.'); ?><br>
          <?php echo lang('Database abstraction, page caching, configurable downloads, secure file storage, secure file links, and so much more combine to create the new leader in file hosting technology, XtraUpload. Oh, and its Free.'); ?><br>
        </p>

        <h3><?php echo lang('Admin Links'); ?></h3>
        <!-- Home Links -->
        <ul class="sidemenu">
          <li>
            <a href="<?php echo site_url('admin/home'); ?>">
              <img src="<?php echo base_url(); ?>img/other/admin_16.png" class="nb" alt="">
              <?php echo lang('Admin Home'); ?> 
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('home'); ?>">
              <img src="<?php echo base_url(); ?>img/other/home2_16.png" class="nb" alt="">
              <?php echo lang('Site Home'); ?> 
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('user/logout'); ?>">
              <img src="<?php echo base_url(); ?>img/icons/log_out_16.png" class="nb" alt="">
              <?php echo lang('Logout'); ?> 
            </a>
          </li>
        </ul>
<?php
// I hate the COM, so if on a windows box dont show the CPU load
$load = $this->functions->get_server_load(0);
if($load > 100)
{
	$load = 100;
}
if(!isset($_SERVER['WINDIR']))
{
?>
        <h3><?php echo lang('Server Load'); ?></h3>
        <ul class="sidemenu">
          <li>
            <div class="progress_border" style="margin-left:2px; width:99%;">
              <div class="progress_img_sliver" style="width:<?php echo round($load); ?>%"></div>
            </div>
            <span><?php echo $load; ?>%</span>
          </li>
        </ul>
<?php
}
?>
        <!-- Config Links -->
        <?php echo $this->xu_api->menus->get_admin_menu(); ?>
        <h3><?php echo lang('Plugins'); ?></h3>
        <ul class="sidemenu">
          <?php echo $this->xu_api->menus->get_plugin_menu(); ?>
        </ul>

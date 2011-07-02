        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/search_32.png" class="nb" alt="">
          <?php echo lang('Search Files'); ?>
        </h2>
<?php
if( ! $this->startup->group_config->can_search)
{
?>
        <span class="alert">
          <?php printf(lang('You are currently not allowed to search files. Please %s to gain access.'), anchor('user/login', lang('login'))); ?> 
        </span>
<?php
}
else
{
?>
        <?php echo $flash_message; ?> 
        <form action="<?php echo site_url('files/search'); ?>" onsubmit="submitSearch(); return false;" method="get">
          <p>
            <label><?php echo lang('Search Text(can be file id, part of a file name, or part of a file description)'); ?></label>
            <input type="text" name="query" id="search" value=""><br><br>
            <?php echo generate_link_button(lang('Search Files'), 'javascript:;', base_url().'img/icons/search_16.png', NULL, array('onclick' => 'submitSearch();')); ?><br>
          </p>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          function submitSearch()
          {
            if($('#search').val() != '')
            {
              window.location = '<?php echo site_url('files/search'); ?>/'+escape($('#search').val());
            }
          }
          //--]]>
        </script>
<?php
}
?>

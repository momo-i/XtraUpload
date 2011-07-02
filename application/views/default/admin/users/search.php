        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/user_32.png" class="nb" alt="">
          <?php echo lang('User Manager - Search'); ?> 
        </h2>
        <form action="<?php echo site_url('admin/user/search'); ?>" onsubmit="submit_search(); return false;" method="get">
          <p>
            <label><?php echo lang('Search Text(can be username, email address, or ip address)'); ?></label>
            <input type="text" name="query" id="search" value=""><br><br>
            <?php echo generate_link_button(lang('Search Users'), 'javascript:;', base_url().'img/icons/search_16.png', NULL, array('onclick' => 'submit_search();')); ?><br>
          </p>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          function submit_search()
          {
            if($('#search').val() != '')
            {
              window.location = '<?php echo site_url('admin/user/search'); ?>/'+escape($('#search').val());
            }
          }
          //--]]>
        </script>

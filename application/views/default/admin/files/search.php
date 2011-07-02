        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/documents_32.png" class="nb" alt="">
          <?php echo lang('File Manager - Search'); ?> 
        </h2>
        <form action="<?php echo site_url('admin/files/search')?>" onsubmit="submit_search(); return false;" method="get">
          <p>
            <label><?php echo lang('Search Text (can be file id, part of a file name, or part of a file description)'); ?></label>
            <input type="text" name="query" id="search" value=""><br><br>
            <?php echo generate_link_button(lang('Search Files'), 'javascript:;', base_url().'img/icons/search_16.png', NULL, array('onclick' => 'submit_search();')); ?><br>
          </p>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          function submit_search()
          {
            if($('#search').val() != '')
            {
              window.location = '<?php echo site_url('admin/files/search')?>/'+escape($('#search').val());
            }
          }
          //--]]>
        </script>

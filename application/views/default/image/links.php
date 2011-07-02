        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/pictures_32.png" class="nb" alt="">
          <?php echo lang('Image BBCode/Links'); ?>
        </h2>
        <p>
          <label><?php echo lang('Image Preview:'); ?></label>
          <img src="<?php echo $thumb_url; ?>" alt=""><br>
          <br>
          <?php echo lang('Download:'); ?><a rel="external" href="<?php echo $down; ?>"><?php echo $down; ?></a>
        </p>
        <h3><?php echo lang('BBCode Links'); ?></h3>
        <p>
          <label><?php echo lang('Show image to friends:'); ?></label>
          <input id="imagelinks_1" value="<?php echo $img_url; ?>" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Hotlink for forums:'); ?></label>
          <input id="imagelinks_2" value="[url=<?php echo $img_url; ?>][img=<?php echo $thumb_url; ?>][/url]" size="70" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" readonly="readonly" type="text">
          <label><?php echo lang('Hotlink for forums (alt):'); ?></label>
          <input id="imagelinks_3" value="[URL=<?php echo $img_url; ?>][IMG]<?php echo $thumb_url; ?>[/IMG][/URL]" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Thumbnail link for forums:'); ?></label>
          <input id="imagelinks_4" value="[URL=<?php echo $img_url; ?>][IMG]<?php echo $thumb_url; ?>[/IMG][/URL]" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Thumbnail link for forums (alt):'); ?></label>
          <input id="imagelinks_5" value="[URL=<?php echo $img_url; ?>][IMG=<?php echo $thumb_url; ?>][/URL]" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Hotlink for websites with thumbnail(HTML):'); ?></label>
          <input id="imagelinks_6" value="&lt;a href='<?php echo $img_url; ?>'&gt;&lt;img src='<?php echo $thumb_url; ?>' border='0' alt='' /&gt;&lt;/a&gt;" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Hotlink for websites, full image(HTML):'); ?></label>
          <input id="imagelinks_7" value="&lt;a href='<?php echo $img_url; ?>'&gt;&lt;img src='<?php echo $direct_url; ?>' border='0' alt='' /&gt;&lt;/a&gt;" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
          <label><?php echo lang('Direct link to image:'); ?></label>
          <input id="imagelinks_8" value="<?php echo $direct_url; ?>" size="70" readonly="readonly" onclick="this.focus();this.select()" ondblclick="this.focus();this.select()" type="text">
        </p>

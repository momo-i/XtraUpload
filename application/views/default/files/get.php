          <h2 style="vertical-align:middle">
            <img src="<?php echo base_url(); ?>img/other/download_32.png" class="nb" alt="">
              <?php echo lang('Download File'); ?> 
              <a href="javascript:;" onclick="$.scrollTo($('#dlhere'),300)">&darr;</a>
          </h2>
          <?php echo $error; ?> 
          <table width="98%">
            <tr>
              <th>
                <a href="javascript:;" onclick="toggleDetails()"><img id="file-details-icon" src="<?php echo base_url(); ?>img/other/remove_16.png" class="nb" alt=""> <?php echo lang('File Details'); ?></a>
              </th>
            </tr>
            <tr id="file-details">
              <td>
<?php if($image): ?>
                <div class="float-right" style="margin-left:8px;">
                  <label for="image"><?php echo lang('Image Preview:'); ?></label>
                  <span id="image"><img src="<?php echo $image['thumb_url']; ?>" alt="" class="nb"></span>
                </div>
<?php endif; ?>
                <code>
                  <label for="name" style="display:inline;padding-bottom:2px">
                    <img class="nb" src="<?php echo base_url(); ?>img/icons/about_16.png" alt="">
                    <?php echo lang('Name:'); ?> 
                  </label>
                  <span id="name">
                    <nobr><?php echo $file->o_filename; ?> <img class="nb" src="<?php echo base_url(); ?>img/files/<?php echo $file->type; ?>.png" alt=""></nobr>
                  </span>
                  <br>
                  <label for="size" style="display:inline;padding-bottom:2px">
                    <img class="nb" src="<?php echo base_url(); ?>img/icons/database_16.png" alt="">
                    <?php echo lang('File Size:'); ?>
                  </label>
                  <span id="size">
                    <?php echo $this->functions->get_filesize_prefix($file->size); ?>
                  </span>
                  <br>
                  <span id="moreInfo">
                    <a href="javascript:;" class="showMoreLink" onclick="showMoreLink()">
                      <img src="<?php echo base_url(); ?>img/icons/add_16.png" alt="" class="nb">
                      <?php echo lang('Show More'); ?> 
                    </a>
                  </span>
                  <span class="hidden" style="display:none">
                    <label for="md5" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/lock_16.png" alt="">
                      <?php echo lang('MD5 Checksum:'); ?> 
                    </label>
                    <span id="md5"><?php echo $file->md5; ?></span>
                    <br>
                    <label for="md5" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/calendar_16.png" alt="">
                      <?php echo lang('Date Uploaded:'); ?> 
                    </label>
                    <span id="md5"><?php echo date('m-D-Y', $file->time); ?></span>
                    <br>
                    <label for="user" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/user_16.png" alt="">
                      <?php echo lang('Uploader:'); ?> 
                    </label>
                    <span id="user"><?php echo $this->users->get_username_by_id($file->user); ?></span>
                    <br>
                    <label for="tags" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/tags_16.png" alt="">
                      <?php echo lang('Tags:'); ?>
                    </label>
                    <span id="tags">
<?php
$tags = explode(',', $file->tags);
$tags = array_map('ucwords', $tags);
?>
                      <?php echo implode(', ', $tags); ?> 
                    </span>
                    <br>
                    <label for="dls" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/save_16.png" alt="">
                      <?php echo lang('Downloads:'); ?>
                    </label>
                    <span id="dls"><?php echo intval($file->downloads); ?></span>
                    <br>
<?php if( ! empty($file->pass)): ?>
                    <label for="pass" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/security_16.png" alt="">
                      <?php echo lang('Password:'); ?> 
                    </label>
                    <span id="pass">
                      <input value="<?php echo $file->pass; ?>" size="42" readonly="readonly">
                    </span>
                    <br>
<?php endif; ?>
<?php if($image): ?>
                    <label for="img_links" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/other/images_16.png" alt="">
                      <?php echo lang('Image BBCode/Links:'); ?>
                    </label>
                    <span id="img_links">
                      <a href="<?php echo $image['code_url']; ?>" rel="external">
                        <?php echo $image['code_url']; ?> 
                      </a>
                    </span>
                    <br>
<?php endif; ?>
<?php if( ! empty($file->descr)): ?>
                    <label for="descr" style="display:inline;padding-bottom:2px">
                      <img class="nb" src="<?php echo base_url(); ?>img/icons/text_16.png" alt="">
                      <?php echo lang('Description:'); ?> 
                    </label>
                    <span id="descr">
                      <textarea readonly="readonly" rows="4" cols="56"><?php echo $file->descr; ?></textarea>
                    </span>
<?php endif; ?>
                </span>
              </code>
            </td>
          </tr>
        </table>
<?php
if($this->startup->site_config->show_preview && $this->xu_api->embed->get_embed_code($file->type))
{
    $embed = $this->xu_api->embed->get_embed_code($file->type);
	switch($file->type)
	{
		case 'mp3':
			$icon = 'music';
		break;
		case 'flv':
		case 'mp4':
			$icon = 'tv';
		break;
		default:
			$icon = 'music';
		break;
	}
?>
        <h3 id="dlhere"><img src="<?php echo base_url(); ?>img/icons/<?php echo $icon; ?>_16.png" class="nb" alt=""> <?php echo lang('Preview File'); ?></h3>
        <p>
          <iframe src="<?php echo site_url('files/embed/'.$file->type.'/'.$file->file_id); ?>" width="<?php echo $embed['width']; ?>" height="<?php echo $embed['height']; ?>" scrolling="no" frameborder="0"></iframe><br>
          <a href="javascript:;" onclick="$('#<?php echo $file->type; ?>_embed_code').slideToggle('normal')">
            <img src="<?php echo base_url(); ?>img/icons/add_16.png" class="nb" alt=""> <?php echo lang('Get Embed Code'); ?>
          </a><br>
          <input style="display:none" id="<?php echo $file->type; ?>_embed_code" type="text" size="60" onclick="this.select();" onfocus="this.select()" value="<iframe src='<?php echo site_url('files/embed/'.$file->type.'/'.$file->file_id); ?>' width='<?php echo $embed['width']; ?>' height='<?php echo $embed['height']; ?>' scrolling='no' frameborder='0'></iframe>">
        </p>
<?php
}
?>
<?php /*File Page Hooks*/ $this->xu_api->hooks->run_hooks('files::get::add_section::before_download', null); ?>

        <h3 id="dlhere"><img src="<?php echo base_url(); ?>img/other/download_16.png" class="nb" alt=""> <?php echo lang('Download Here'); ?></h3>
        <form action="<?php echo site_url('files/gen/'.$file->file_id.'/'.$file->link_name); ?>" method="post" onsubmit="return checkTimer()">
          <div>
            <input type="hidden" name="pass" value="<?php echo $this->input->post('pass'); ?>">
            <input type="hidden" name="waited" value="1">
            <span id="waitL">
              <?php printf(lang('Please Wait %s more second(s) to download.'), '<span id="waittime">'.$sec.'</span>'); ?><br>
            </span>
            <span style="display:none" id="captcha">
<?php if($captcha_bool): ?>
            <label style="font-weight:bold" for="captcha"><?php echo lang('Captcha Test - Type the three(3) letters you see below:')?></label>
            <code>
              <span id="captchaImg"><?php echo $captcha; ?></span>
              <a href="javascript:;" onclick="getNewCaptcha()">
                <img class="nb" title="<?php echo lang('Get New Captcha'); ?>" src="<?php echo base_url(); ?>img/icons/refresh_16.png" alt="">
              </a><br>
              <input type="text" size="3" maxlength="3" name="captcha">
            </code><br>
<?php endif; ?>
            <? echo generate_submit_button(lang('Download'), base_url().'img/icons/backup_16.png', 'green'); ?>
<?php if($image): ?>
            <? echo generate_link_button(lang('View This Image'), $image['direct_url'], base_url().'img/icons/pictures_16.png', null, array('target' => '_blank')); ?>
<?php endif; ?>
            <br>
          </span>
        </div>
      </form>
<?php /*File Page Hooks*/ $this->xu_api->hooks->run_hooks('files::get::add_section::after_download', null); ?>
      <script type="text/javascript">
        //<![CDATA[
<?php
$rand = 'x'.str_replace('-', '', $this->functions->get_rand_id());
$rand1 = 'y'.str_replace('-', '', $this->functions->get_rand_id(32));
?>
        var <?php echo $rand; ?> = <?php echo $sec; ?>;
        //var <?php echo $rand; ?> = <?php // $group->waittimme; ?>;
        var <?php echo $rand1; ?> = false;
        function startCountdown()
        {
          setTimeout('incCountdown()', 1000);
        }
        function incCountdown()
        {
          if(<?php echo $rand; ?> == 1 || <?php echo $rand; ?> == 0)
          {
            <?php echo $rand1; ?> = true;
            $('#captcha').fadeIn('fast');
            $('#waitL').fadeOut('fast');
          }
          else
          {
            <?php echo $rand; ?>  = <?php echo $rand; ?> - 1;
            $('#waittime').html(<?php echo $rand; ?>);
            setTimeout('incCountdown()', 1000);
          }
        }
        function checkTimer()
        {
          if(!(<?php echo $rand1; ?>))
          {
            alert('<?php printf(lang('You need to wait %d more seconds before you can download this file.'), $rand); ?>');
            return false;
          }
        }
        $(document).ready(function()
        {
          startCountdown();
          $('.showMoreLink').hoverIntent({
            sensitivity: 4, // number = sensitivity threshold (must be 1 or higher)
            interval: 2000,   // number = milliseconds of polling interval
            over: showMoreLink,  // function = onMouseOver callback (required)
            timeout: 0,   // number = milliseconds delay before onMouseOut function call
            out: returnFalse    // function = onMouseOut callback (required)
          });
        });
        function returnFalse()
        {
          return false;
        }
        function showMoreLink()
        {
          $('.hidden').slideDown('fast');
          $('#moreInfo').hide();
        }
        function getNewCaptcha()
        {
          var id = $('#captchaImg img').attr('id').split('t_')[1];
          $('#captchaImg').load('<?php echo site_url('api/captcha/get'); ?>/'+escape(id));
        }
        function toggleDetails()
        {
          if($("#file-details").css('display') == 'none')
          {
            $("#file-details-icon").attr('src', '<?php echo base_url(); ?>img/other/remove_16.png');
            $("#file-details").show();
          }
          else
          {
            $("#file-details-icon").attr('src', '<?php echo base_url(); ?>img/icons/add_16.png');
            $("#file-details").hide();
          }
        }
        //--]]>
      </script>

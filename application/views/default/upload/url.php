        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/connect_32.png" class="nb" alt="">
          <?php echo lang('URL Upload(beta)'); ?> 
        </h2>
<?php
if( ! $this->startup->group_config->can_url_upload)
{
?>
        <span class="alert">
          <?php printf(lang('You are currently not allowed to use URL Upload. Please %s to gain access.'), anchor('user/login', lang('login'))); ?> 
        </span>
<?php
}
else
{
?>
        <p>
          <?php echo lang('Upload files from other servers on the internet.'); ?> 
          <?php printf(lang('%sThis is still a beta service%s and there might still be bugs in it. Please email us and let us know if you find any or encounter any "Upload Failed!" errors.'), '<span style="color:#FF0000">', '</span>'); ?><br><br>
        </p>
        <p>
          <label for="linkBlock">
            <a href="javascript:;" style="cursor:pointer" onclick="toggleUploadBlock()">
              <img alt="" id="uploadImgSwitch" src="<?php echo base_url(); ?>img/other/remove_16.png" class="nb">
              <?php echo lang('Remote Upload URLs, one per line'); ?> 
            </a>
          </label>
          <span id="uploadTextBlock">
            <textarea id="linkBlock" name="linkBlock" cols="65" rows="15"></textarea><br>
            <span class="cssButton" style="display:block">
              <a onclick="addToQueue();" class="buttonGreen" href="javascript:;">
                <img alt="" src="<?php echo site_url('img/icons/add_16.png'); ?>">
                <?php echo lang('Add To Queue'); ?> 
              </a>
            </span><br>
          </span>
        </p>
        <div id="files" style="display:">
          <h3><?php echo lang('Queued File List'); ?></h3>
          <div id="file_list">
            <p>
              <?php printf(lang('You have selected the following files for upload (%s Files).'), '<span id="summary">0</span>'); ?><br>
              <span class="alert" id="alert1" style="display:none"></span>
              <span class="alert" id="alert2" style="display:none"></span>
              <span class="float-right"><?php echo generate_link_button(lang('Begin Upload!'), 'javascript:startUploadQueue();', base_url().'img/icons/up_16.png', 'green'); ?><br><br></span>
            </p>
            <div style="clear:both"></div>
            <table border="0" style="width:98%" id="file_list_table">
              <tr>
                <th style="width:470px" class="align-left"><?php echo lang('File Name'); ?></th>
                <th style="width:85px">
                  <?php echo lang('Actions'); ?> 
                  <img title="<?php echo lang('Remove All?'); ?>" src="<?php echo base_url(); ?>img/icons/delete_16.png" onclick="clearUploadQueue()" alt="<?php echo lang('Remove All?'); ?>" style="cursor:pointer" class="nb">
                </th>
              </tr>
            </table>
            <p>
              <span class="float-right"><?php echo generate_link_button(lang('Begin Upload!'), 'javascript:startUploadQueue();', base_url().'img/icons/up_16.png', 'green'); ?><br><br></span>
            </p>
          </div>
        </div>
        <form action="<?php echo $server.'upload/url_process'; ?>" method="post" id="urlUp" style="display:none">
          <p>
            <input type="hidden" id="fid" name="fid" value="">
            <input type="hidden" name="link" id="link" value="">
            <input type="hidden" name="descr" id="descr" value="">
            <input type="hidden" name="pass" id="pass" value="">
            <input type="hidden" name="user" id="pass" value="<?php echo $this->session->userdata('id'); ?>">
          </p>
        </form>
        <script type="text/javascript">
          //<![CDATA[
          var doProgress = false;
          var file_count = 0;
          var filesToUpload = [];
          var currentFileId;
          var allowCheckProgress=false;
          var filePropsObj = new Array();
          var fileIcons = new Array(<?php echo $file_icons; ?>);
          function ___upLang(key)
          {
            var lang = new Array();
            lang['pc' ]     = '<?php echo lang('Percent Complete'); ?>';
            lang['kbr']     = '<?php echo lang('KB Remaining (at '); ?>';
            lang['remain']  = '<?php echo lang('remaining'); ?>';
            lang['desc']    = '<?php echo lang('Description'); ?>';
            lang['fp']      = '<?php echo lang('File Password'); ?>';
            lang['sc']      = '<?php echo lang('Save Changes'); ?>';
            lang['efd']     = '<?php echo lang('Edit File Details'); ?>';
            lang['rm']      = '<?php echo lang('Remove File'); ?>';
            lang['ff1']     = '<?php echo lang('Feature This File?'); ?>';
            lang['ff2']     = '<?php echo lang('Yes'); ?>';
            return lang[key];
          }
          function toggleUploadBlock()
          {
            if($('#uploadTextBlock').css('display') != 'none')
            {
              $('#uploadTextBlock').slideUp('fast');
              $('#uploadImgSwitch').attr('src', '<?php echo base_url(); ?>img/icons/add_16.png');
            }
            else
            {
              $('#uploadTextBlock').slideDown('fast');
              $('#uploadImgSwitch').attr('src', '<?php echo base_url(); ?>img/other/remove_16.png');
            }
          }
          function startUpload(id)
          {
            var idO = id.split('up_')[1];
            var file = filesToUpload[idO];
            var jForm = $("#urlUp");
            currentFileId = genRandId(16);
            $('#fid').attr('value', currentFileId);
            $('#pass').val($('#'+file.id+'_pass').val());
            $('#descr').val($('#'+file.id+'_desc').val());
            $('#link').val(file.link);
            var strName = "uploader_" + (new Date()).getTime();
            alert(strName);
            var jFrame = $("<iframe name=\"" + strName + "\" src=\"<?php echo site_url('blank.html'); ?>\"></iframe>");
            jForm.attr("target", strName);
            jFrame.css("display", "none");
            jFrame.load( function(objEvent)
            {
              syncFileProps(filesToUpload[idO]);
              endProgress(idO);
              $("iframe[name='"+strName+"']").remove();
              $('#'+idO+"-del").empty().html("<strong><?php echo lang('Done!'); ?></strong>");
              filesToUpload[idO].uploaded = true;
              startUploadQueue();
            });
            $("body:first").append(jFrame);
            jForm.submit();
            placeProgressBar(idO);
            startProgress();
          }
          function syncFileProps(file)
          {
            var fFeatured = filePropsObj[file.id]['feat'];
            var fDesc = filePropsObj[file.id]['desc'] ;
            var fPass = filePropsObj[file.id]['pass'];
            if(fPass == '' && fFeatured == '' && fDesc == '')
            {
              $("#"+file.id+"-details-inner").empty().attr('colspan', 3).load('<?php echo site_url('upload/get_links'); ?>/'+currentFileId);
              return;
            }
            $.post(
              '<?php echo site_url('upload/file_upload_props'); ?>',
              {
                fid: currentFileId,
                password: fPass,
                desc: fDesc,
                featured: fFeatured
              },
              function()
              {
                $("#"+file.id+"-details-inner").empty().attr('colspan', 3).load('<?php echo site_url('upload/get_links')?>/'+currentFileId);
              }
            );
          }
          function saveFilePropChanges(file_id)
          {
            if(typeof(filePropsObj[file_id]) == 'undefined')
            {
              filePropsObj[file_id] = new Array();
            }
            filePropsObj[file_id]['desc'] = $('#'+file_id+'_desc').val();
            filePropsObj[file_id]['pass'] = $('#'+file_id+'_pass').val();
            if($('#'+file_id+'_feature').attr('checked') && $('#'+file_id+'_feature').attr('value'))
            {
              filePropsObj[file_id]['feat'] ="1";
            }
            else
            {
              filePropsObj[file_id]['feat'] ="0";
            }
          }
          function ___getFileIcon(icon)
          {
            if(in_array(icon, fileIcons))
            {
              return icon;
            }
            else
            {
              return 'default';
            }
          }
          function in_array(needle, haystack, strict)
          {
            // http://kevin.vanzonneveld.net
            var found = false, key, strict = !!strict;
            for (key in haystack)
            {
              if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle))
              {
                found = true;
                break;
              }
            }
            return found;
          }
          function ___progressURL()
          {
            return "<?php echo site_url('upload/get_progress'); ?>";
          }
          //--]]>
        </script>
<?php
}
?>

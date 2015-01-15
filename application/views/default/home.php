<?php
if( ! $this->startup->group_config->can_flash_upload)
{
?>
          <h2 style="vertical-align:middle">
            <img src="<?php echo base_url(); ?>assets/images/other/home2_32.png" class="nb" alt="">
            <?php echo lang('Home'); ?>
          </h2>
          <span class="alert">
            <?php printf(lang('You are currently not allowed to upload local files. Please %s to gain access.'), anchor('user/login', lang('Login'))); ?>
          </span>
<?php
}
else
{
?>
          <h2 style="vertical-align:middle">
            <img src="<?php echo base_url(); ?>assets/images/other/home2_32.png" class="nb" alt="">
            <?php echo lang('Home'); ?> 
          </h2>
<?php
	if( ! empty($flash_message))
	{
?>
          <p><?php echo $flash_message; ?></p>
<?php
	}
?>
<?php
	if( ! empty($this->startup->site_config->home_info_msg))
	{
?>
          <span class="note"><?php echo $this->startup->site_config->home_info_msg; ?></span>
<?php
	}
?>
          <div id="info_div" style="display:none">
            <h3>
              <a href="javascript:void(0);" onclick="$('#upload_limits').slideDown();$(this).parent().remove();">
                <img src="<?php echo base_url(); ?>assets/images/icons/about_24.png" class="nb" alt="">
                <?php echo lang('Upload Restrictions'); ?> 
              </a>
            </h3>
            <p>
              <span style="display:none" id="upload_limits" rel="no_close" class="info">
                <?php printf(lang('You can upload %s files at %s MB each.'), '<strong>'.intval($upload_num_limit).'</strong>', '<strong>'.intval($upload_limit).'</strong>'); ?>

<?php
	if(trim($files_types) != '' && $files_types != '*')
	{
?>
                <br>
                <?php ($file_types_allow_deny) ? printf(lang('You can upload these file types: %s'), str_replace('|', ', .', $files_types)) : printf(lang('You cannot upload these file types: %s'), str_replace('|', ', .', $files_types)); ?>
<?php
	}
	if(trim($storage_limit) !== "" && $storage_limit !== "0")
	{
?>
                <br><br>
                <strong><?php echo lang('Your account is limited by storage space:'); ?></strong>
                <br>
                <?php printf(lang('You have %d of %d MB remaining.'), '<strong>'.$storage_used.'</strong>', '<strong>'.$storage_limit.'</strong>'); ?>
<?php
	}
?>

              </span>
            </p>
          </div>
          <div id="flash" style="display:">
            <span class="alert">
              <strong><?php echo lang('Error'); ?></strong><br>
              <?php echo lang('Get Flash!'); ?><br>
              <a href="http://get.adobe.com/jp/flashplayer/">
                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="<?php echo lang('Get Adobe Flash player'); ?>">
              </a>
            </span>
            <form enctype="multipart/form-data" action="<?php echo site_url('upload/process/'.md5($this->functions->get_rand_id(32)).'/'.($this->session->userdata('id') ? $this->session->userdata('id') : 0 ))?>" method="post">
              <h3><?php echo lang('Upload a File'); ?></h3>
              <p>
                <span><?php echo lang('This is a backup upload form for our users who do not have Flash installed, to access our advanced uploading features please install the Flash Plugin and enable Javascript.'); ?></span>
                <input type="hidden" name="no_flash" value="1">
                <label for="file">
                  <?php echo lang('File'); ?><input type="file" id="file" name="Filedata">
                </label>
                <label for="passwords">
                  <?php echo lang('Password'); ?><input type="text" id="passwords" name="password" value="">
                </label>
                <label for="description">
                  <?php echo lang('Description'); ?><textarea id="description" name="description" rows="8" cols="40"></textarea>
                </label>
                <br style="clear:both">
                <?php echo generate_submit_button(lang('Upload!'), base_url().'assets/images/icons/up_16.png', 'green'); ?>
                <br style="clear:both">
              </p>
            </form>
          </div>
          <div id="uploader" style="display:none;">
            <h3 style="padding-top:8px;"><?php echo lang('Select Files To Upload'); ?></h3><br>
            <div style="padding-left:12px;">
              <div style="display: block; width:90px; height:22px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px; padding-top:6px; padding-left:6px;"><span id="plupload"><span class="button" style="font-size: 12pt; font-weight:bold; color:#565656; cursor: pointer;"><?php echo lang('Browse...'); ?></span></span></div>
            </div>
            <br>
          </div>
          <div id="drop-target" style="display:none"><?php echo lang('Drop your files or folders here'); ?></div>
          <div id="files" style="display:none">
            <h3 style="padding-top:8px;"><?php echo lang('Queued File List'); ?></h3>
            <div id="file_list">
              <p>
                <?php printf(lang('You have selected the following files for upload (%s) files.'), '<span id="summary">0</span>'); ?><br>
                <span class="alert" id="alert1" style="display:none">
                  <?php echo lang('You have selected previously selected files for upload.'); ?><br>
                  <?php echo lang('These files have been removed.'); ?> 
                </span>
                <span class="alert" id="alert2" style="display:none">
                  <?php echo lang('One or more of the files you selected were too large.'); ?><br>
                  <?php echo lang('These files have been removed.'); ?> 
                </span>
                <span class="alert" id="alert3" style="display:none">
                  <?php echo lang('One or more of the files you selected are not allowed.'); ?><br>
                  <?php echo lang('These files have been removed.'); ?> 
                </span>
                <span class="alert" id="alert4" style="display:none">
                  <?php printf(lang('You have selected to many files to upload at once. You are limited to %s files.'), '<strong>'.intval($upload_num_limit).'</strong>'); ?><br>
                  <?php echo lang('Please select fewer files and try again.'); ?> 
                </span>
                <span class="alert" id="alert5" style="display:none">
                  <?php echo lang('An unknown error has occured.'); ?><br>
                  <?php echo lang('Please contact us about this error'); ?> 
                </span>
              </p>
              <div class="float-right" style=" margin-bottom:1em">
                <?php echo generate_link_button(lang('Upload!'), 'javascript:void(0);', base_url().'assets/images/icons/up_16.png', 'green', array('onclick'=>'uploader.start();')); ?>
              </div>
              <table style="border:0;padding:0;width:98%;clear:both" id="file_list_table">
                <tr>
                  <th style="width:470px" class="align-left"><?php echo lang('File name'); ?></th>
                  <th style="width:90px"><?php echo lang('Size'); ?></th>
                  <th style="width:85px"><?php echo lang('Actions'); ?> <img title="<?php echo lang('Delete All?'); ?>" src="<?php echo base_url(); ?>assets/images/icons/delete_16.png" onclick="clearUploadQueue()" alt="" style="cursor:pointer" class="nb"></th>
                </tr>
              </table>
              <div class="float-right">
                <?php echo generate_link_button(lang('Upload!'), 'javascript:void(0);', base_url().'assets/images/icons/up_16.png', 'green', array('onclick'=>'uploader.start();')); ?>
              </div>
            </div>
          </div>
          <input id="fid" type="hidden">
          <input id="uid" type="hidden" value="<?php echo (intval($this->session->userdata('id')) != 0 ? intval($this->session->userdata('id')) : 0 ); ?>">
          <div id="filesHidden" style="display:none"></div>
          <script type="text/javascript">
            //<![CDATA[
            var fileObj = new Array();
            var prevFile = false;
            var fileToBig = false;
            var fileNotAllowed = false;
            var filePropsObj = new Array();
            var subtractFilesFromTotal = 0;
            var curFileId = '';
            var pbUpd = 0;
            var flashUploadStartTime = '';
            var fileIcons = new Array(<?php echo $file_icons; ?>);
            var fid = "";
            function ___getMaxUploadSize()
            {
              return '<?php echo intval($upload_limit); ?>';
            }
            function ___serverUrl()
            {
              return '<?php echo $server; ?>';
            }
            function ___getFilePipeString()
            {
              return '<?php echo $files_types; ?>';
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
            function ___getFileTypesAllowOrDeny()
            {
              return <?php echo intval($file_types_allow_deny); ?>;
            }
            function ___toManyFilesError()
            {
              $('#alert4').show();
              setTimeout('$("#alert4").hide("normal");', 2500);
              fileToBig = false;
            }
            function ___generalError()
            {
              $('#alert5').show();
              setTimeout('$("#alert5").hide("normal");', 2500);
              fileToBig = false;
            }
            function ___filePropSaveButtons(id)
            {
             var template = "<?php echo str_replace("\n", '', str_replace('"', '\\"', generate_link_button(lang('Save Changes'), 'javascript:;', base_url().'assets/images/icons/ok_16.png', 'green', array('onclick' => 'saveFilePropChanges(\'--id--\');$(\'#--id---details\').hide();$(\'#--id---edit_img\').fadeIn(\'fast\');')).generate_link_button(lang('Discard Changes'), 'javascript:;', base_url().'assets/images/icons/close_16.png', 'red', array('onclick' => '$(\'#--id---details\').hide();$(\'#--id---edit_img\').fadeIn(\'fast\');'))))?>";
              return str_replace('--id--', id, template);
            }
            function str_replace (search, replace, subject, count)
            {
              // Replaces all occurrences of search in haystack with replace
              f = [].concat(search),
              r = [].concat(replace),
              s = subject,
              ra = r instanceof Array, sa = s instanceof Array;    s = [].concat(s);
              if (count) {
                this.window[count] = 0;
              }
              for (i=0, sl=s.length; i < sl; i++) {
                if (s[i] === '') {
                  continue;
                }
                for (j=0, fl=f.length; j < fl; j++) {
                  temp = s[i]+'';
                  repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
                  s[i] = (temp).split(f[j]).join(repl);
                  if (count && s[i] !== temp) {
                    this.window[count] += (temp.length-s[i].length)/f[j].length;
                  }
                }
              }
              return sa ? s : s[0];
            }
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
              lang['ft']      = '<?php echo lang('Tags (seperated by commas)'); ?>';
              return lang[key];
            }
            var maxsize = ___getMaxUploadSize();
            var uploader = new plupload.Uploader({
              runtimes: 'html5,silverlight,flash,html4',
              browse_button: 'plupload',
              drop_element: 'drop-target',
              url: "<?php echo site_url('upload/plupload'); ?>",
              chunk_size: "1gb",
              flash_swf_url: ___baseUrl()+'assets/flash/Moxie.swf',
              silverlight_xap_url: ___baseUrl()+'assets/flash/Moxie.xap',
              filters: {
                max_file_size: maxsize+'mb',
                mime_types: [
                  {title: "All files", extensions: "*"}
                ]
              },
              preinit: {
                UploadFile: function(up, file) {
                  fid = genRandId(32);
                  up.setOption('url', '<?php echo site_url("upload/plupload"). "/'+fid+'/".($this->session->userdata('id') ? $this->session->userdata('id') : 0 )?>');
                }
              },
              init: {
                Init: function(up, params) {
                  if(params.runtime === 'html5' && !$.browser.msie) {
                    $('#drop-target').show();
                  }
                },
                FilesAdded: function(up, files) {
                  plupload.each(files, function(file) {
                    fileDialogComplete();
                    addFileQueue(file);
                  });
                },
                BeforeUpload: function(up, file) {
                  beforeUploadStart(file);
                },
                UploadProgress: function(up, file) {
                  flashUploadProgress(file, up);
                },
                FileUploaded: function(up, file, info) {
                  uploadDone(file);
                  updatePendingFileCount();
                },
                Error: function(up, args) {
                  uploadError(up, args);
                }
              },
            });
            var target = $("#drop-target");
            if(uploader.features.dragdrop) {
              target.ondragover = function(event) {
                event.dataTransfer.dropEffect = "copy";
              };
              target.ondragenter = function() {
                this.className = "dragover";
              };
              target.ondragleave = function() {
                this.className = "";
              };
              target.ondrop = function() {
                this.className = "";
              };
            }
            if(uploader) {
              $('#flash').remove();
              $('#browser').attr('disabled', false);
              $('#uploader').show();
              $('#files').show();
              $('#info_div').show();
            }
            uploader.init();
            function saveFilePropChanges(file_id)
            {
              filePropsObj[file_id]['desc'] = $('#'+file_id+'_desc').val();
              filePropsObj[file_id]['pass'] = $('#'+file_id+'_pass').val();
              filePropsObj[file_id]['tags'] = $('#'+file_id+'_tags').val();
              if($('#'+file_id+'_feat:checked').length)
              {
                filePropsObj[file_id]['feat'] = "1";
              }
              else
              {
                filePropsObj[file_id]['feat'] = "0";
              }
            }
            function beforeUploadStart(file)
            {
              placeProgressBar(file.id);
              flashUploadStartTime = Math.round(new Date().getTime()/1000.0);
              $("#"+file.id+"-details").css('borderTop', 'none').show();
              $("#"+file.id).addClass('details').css('borderBottom', 'none');
              //$.scrollTo( $("#"+file.id), 300);
              return true;
            }
            function syncFileProps(file, curFileId)
            {
console.log(filePropsObj);
              var fFeatured = filePropsObj[file.id]['feat'];
              var fDesc = filePropsObj[file.id]['desc'] ;
              var fPass = filePropsObj[file.id]['pass'];
              var fTags = filePropsObj[file.id]['tags'];
              if(fPass == '' && fFeatured == '' && fDesc == '' && fTags == '')
              {
                $("#"+file.id+"-details-inner").empty().attr('colspan', 3).load('<?php echo site_url('upload/get_links'); ?>/'+curFileId);
                return;
              }
              $.post(
                '<?php echo site_url('upload/file_upload_props'); ?>',
                {
                  fid: curFileId,
                  password: fPass,
                  desc: fDesc,
                  tags: fTags,
                  featured: fFeatured
                },
                function()
                {
                  $("#"+file.id+"-details-inner").empty().attr('colspan', 3).load('<?php echo site_url('upload/get_links'); ?>/'+curFileId);
                }
              );
            }
            function uploadDone(file)
            {
              curFileId = fid;
              var fUser = $('#uid').val();
              var url = ___serverUrl()+"upload/process/"+fid+'/'+fUser;
              $.post(
                url,
                "",
                function() {
                  syncFileProps(file, curFileId);
                  $('#'+file.id+"-del").empty().html("<strong><?php echo lang('Done!'); ?></strong>");
                  $("#"+file.id+"-details").css('borderTop', 'none').show();
                  if(uploader.total.queued > 0)
                  {
                    uploader.start();
                  }
                  else
                  {
                    $.scrollTo( $("#uploader"), 400);
                  }
                }
              );
            }
            //--]]>
          </script>
<?php
}
?>

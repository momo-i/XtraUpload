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
          <div id="plupload">Testing use. This is not work well yet.</div>
          <div id="uploader" style="display:none;">
            <h3 style="padding-top:8px;"><?php echo lang('Select Files To Upload'); ?></h3><br>
            <div style="padding-left:12px;">
              <div style="display: block; width:90px; height:22px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px; padding-top:6px; padding-left:6px;"><span id="spanButtonPlaceholder"></span></div>
            </div>
            <br>
          </div>
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
                <?php echo generate_link_button(lang('Upload!'), 'javascript:void(0);', base_url().'assets/images/icons/up_16.png', 'green', array('onclick'=>'swfu.startUpload();')); ?>
              </div>
              <table style="border:0;padding:0;width:98%;clear:both" id="file_list_table">
                <tr>
                  <th style="width:470px" class="align-left"><?php echo lang('File name'); ?></th>
                  <th style="width:90px"><?php echo lang('Size'); ?></th>
                  <th style="width:85px"><?php echo lang('Actions'); ?> <img title="<?php echo lang('Delete All?'); ?>" src="<?php echo base_url(); ?>assets/images/icons/delete_16.png" onclick="clearUploadQueue()" alt="" style="cursor:pointer" class="nb"></th>
                </tr>
              </table>
              <div class="float-right">
                <?php echo generate_link_button(lang('Upload!'), 'javascript:void(0);', base_url().'assets/images/icons/up_16.png', 'green', array('onclick'=>'swfu.startUpload();')); ?>
              </div>
            </div>
          </div>
          <input id="fid" type="hidden">
          <input id="uid" type="hidden" value="<?php echo (intval($this->session->userdata('id')) != 0 ? intval($this->session->userdata('id')) : 0 ); ?>">
          <div id="filesHidden" style="display:none"></div>
          <script type="text/javascript">
            //<![CDATA[
            function ___server_url()
            {
              return '<?php echo $server; ?>';
            }
            //$(function() {
              //$('#plupload').pluploadQueue({
              var uploader = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'plupload',
                url: "<?php echo site_url('upload/plupload'); ?>", //.md5($this->functions->get_rand_id(32)).'/'.($this->session->userdata('id') ? $this->session->userdata('id') : 0 ))?>",
                chunk_size: "1mb",
                unique_names: true,
                flash_swf_url: '/assets/flash/Moxie.swf',
                silverlight_xap_url: '/assets/flash/Moxie.xap',
                filters: {
                  max_file_size: '100gb',
                  mime_types: [
                    {title: "All files", extensions: "*"}
                  ]
                },
                init: {
                  PostInit: function() {
                  },
                  FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                      $('#files').show();
                      $('#file_list').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                  },
                  BeforeUpload: function(up, file) {
                    var fid = gen_rand_id(32);
                    var cur_file_id = fid;
                    var stats = plupload.QUEUED;
                    var f_user = $('#uid').val();
                    var url = ___server_url()+"upload/process/"+fid+'/'+f_user;
                    placeProgressBar(file.id);
                    //var flashUploadStartTime = Math.round(new Date().getTime()/1000.0);
                    //$("#"+file.id+"-details").css('borderTop', 'none').show();
                    //$("#"+file.id).addClass('details').css('borderBottom', 'none');
                  },
                  UploadComplete: function(up, files) {
					alert('test');
                    upload_done(files);
                  }
                },
              });
              uploader.init();
            //});
            function sync_file_props(file)
            {
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
            function upload_done(file)
            {
              syncFileProps(file);
              $('#'+file.id+"-del").empty().html("<strong><?php echo lang('Done!'); ?></strong>");
              $("#"+file.id+"-details").css('borderTop', 'none').show();
              var stats = swfu.getStats();
              if(stats.files_queued > 0)
              {
                swfu.startUpload();
              }
              else
              {
                $.scrollTo( $("#uploader"), 400);
              }
            }
            //--]]>
          </script>
<?php
}
?>

function flashUpdate(total, remain, speed, per)
{
	$("#progress_img").stop(true);
	if(total != '')
		$('#total').html(total);
	
	if(remain != '')
		$('#remaining').html(remain);
	
	if(speed != '')
		$('#speed').html(speed);
	
	if(per != '')
	{
		$('#percent').html(per);
		$("#progress_img").animate({width: per+"%"}, 'fast');
	}
}

var pbUpd=0;

//function flashUploadProgress(file, sofar, total)
function flashUploadProgress(file, up)
{
	var flashCurrentTime = Math.round(new Date().getTime()/1000.0);
	var sofar = up.total.loaded;
	var lapsed =  flashCurrentTime - flashUploadStartTime;
	var bRead = sofar;
	var total = up.total.size;
	var bSpeed = 0; 
	var speed = 0; 
	var remaining = 0;
	var percent = 0;

	if(lapsed > 0)
	{ 
		bSpeed = (bRead / lapsed); 
	}

	//bSpeed = up.total.bytesPerSec;
	if(bSpeed > 0)
	{ 
		remaining = Math.round((total - sofar) / bSpeed); 
	}

	var remaining_sec = (remaining % 60); 
	var remaining_min = (((remaining - remaining_sec) % 3600) / 60); 
	var remaining_hours = ((((remaining - remaining_sec) - (remaining_min * 60)) % 86400) / 3600); 
	
	if(remaining_sec < 10){ remaining_sec = "0"+remaining_sec; }
	if(remaining_min < 10){ remaining_min = "0"+remaining_min; }
	if(remaining_hours < 10){ remaining_hours = "0"+remaining_hours; }
	
	var remainingf = remaining_hours+"h : "+remaining_min+"m : "+remaining_sec+"s"; 
	
	percent = up.total.percent;
	if(lapsed > 1)
	{
		speed = Math.round(bRead / lapsed);
	}
	else
	{
		speed = 0;
	}
	speed = Math.round(speed / 1024);	
	
	flashUpdate(Math.round((total-sofar)/1024), remainingf, speed, percent);
}

function rm_file(id)
{
	$('#'+id).remove();
	$('#'+id+"-details").remove();
	//plupload.cancelUpload(id);
	delete fileObj[id];
	
	// remove from count
	updatePendingFileCount();
}

function updatePendingFileCount()
{
	$('#summary').html(parseInt(uploader.total.queued));
}

function convert_bits(bytes) 
{
	var kb = bytes / 1024;
	if (kb < 1024) 
	{
		return Math.round(kb) + ' KB';
	} 
	else 
	{
		mb = kb / 1024;
		return Math.round(mb * 10) / 10 + ' MB';
	}
}

function placeProgressBar(id)
{
	$("#"+id+"-details-inner").empty().html(
		'<strong>'+___upLang('pc')+'</strong>: '+
		'<span id="percent">0</span>%'+
		'<table width="350" height="24" border="0"><tr><td width="25" height="24">'+
		'<img src="'+___baseUrl()+'assets/images/icons/import_24.png" class="nb" width="24" height="24">'+
		'</td><td width="300">'+
		'<div class="progress_border">'+
		'<div class="progress_img" id="progress_img"></div></div>'+
		'</td><td width="25">'+
		'<img src="'+___baseUrl()+'assets/images/icons/event_24.png" class="nb" width="24" height="24">'+
		'</td></tr></table>'+
		'<span id="total">0</span>'+___upLang('kbr')+'<span id="speed">0</span> KB/s) <br>'+
		'<span id="remaining">00h : 00m : 00s</span> '+___upLang('remain'));
}

function addFileQueue(file)
{
	if(typeof(fileObj[file.id]) != 'undefined')
	{
		plupload.removeFile(file);
		subtractFilesFromTotal++;
		prevFile = true;
		return true;
	}

	if(file.size > 1024 * 1024 * ___getMaxUploadSize())
	{
		plupload.removeFile(file);
		subtractFilesFromTotal++;
		fileToBig = true;
		return true;
	}
	
	if(___getFilePipeString() != '' && ___getFilePipeString() != '*')
	{
		// setup file checking routine
		var fileTypes = ___getFilePipeString().split('|');
		var extension = getExtension(file.name);
		
		if(___getFileTypesAllowOrDeny())// 1 = Allow types, 0 = Deny types
		{
			//check if file is allowed
			var allow = false;
			for(var i=0; i<fileTypes.length; i++)
			{
				if(extension == fileTypes[i] && !allow)
				{
					allow = true;
					break;
				}
			}
			
			if(!allow)
			{
				plupload.removeFile(file);
				subtractFilesFromTotal++;
				fileNotAllowed = true;
				return true;	
			}
		}
		else
		{
			//check if file is not allowed
			var notAllow = false;
			for(var i=0; i<fileTypes.length; i++)
			{
				if(extension == fileTypes[i])
				{
					notAllow = true;
					break;
				}
			}
			
			if(notAllow)
			{
				plupload.removeFile(file);
				subtractFilesFromTotal++;
				fileNotAllowed = true;
				return true;	
			}
			
		}
		
	}
	filePropsObj = new Array();
    filePropsObj[file.id] = new Array();
    filePropsObj[file.id]['feat'] = ''; 
    filePropsObj[file.id]['desc'] = '';
    filePropsObj[file.id]['tags'] = ''; 
    filePropsObj[file.id]['pass'] = '';
	fileObj[file.id] = file.name;

	$('#file_list_table').append(""+
		"<tr id='"+file.id+"'>"+
			"<td class='align-left' style='vertical-align:middle'>"+
				"<img class='nb' src='"+___baseUrl()+"assets/images/files/"+___getFileIcon(getExtension(file.name))+".png' border='0' />&nbsp;" +
				file.name +
			"</td>"+
			"<td>" + 
				convert_bits(file.size) + 
			"</td>"+
			"<td id='"+file.id+"-del'>"+
				"<img id='"+file.id+"-edit_img' onclick=\"$('#"+file.id+"-details').show();$(this).fadeOut('fast')\" src='"+___baseUrl()+"assets/images/icons/edit_16.png' title='"+___upLang('efd')+"' style='cursor:pointer' class='nb'>&nbsp;"+
				"<img onclick=\"rm_file('" + file.id + "');\" src='"+___baseUrl()+"assets/images/icons/close_16.png' title='"+___upLang('rm')+"' style='cursor:pointer' class='nb'>"+
			"</td>"+
		"</tr>"+
		"<tr id='"+file.id+"-details' class='details' style='display:none'>"+
			"<td id='"+file.id+"-details-inner' colspan='3'>"+
				'<span class="float-right"><label for="'+file.id+'_desc">'+___upLang('desc')+'</label>'+
				'<textarea name="'+file.id+'_desc" id="'+file.id+'_desc" cols="30" style="height:180px" rows="4"></textarea></span>'+
                
				'<label for="'+file.id+'_pass">'+___upLang('fp')+'</label>'+
				'<input name="'+file.id+'_pass" id="'+file.id+'_pass" size="35" maxlength="32" type="text" /><br />'+
				
				'<label for="'+file.id+'_tags">'+___upLang('ft')+'</label>'+
				'<input name="'+file.id+'_tags" id="'+file.id+'_tags" size="35" maxlength="200" type="text" /><br />'+
                
                '<label for="'+file.id+'_feat">'+___upLang('ff1')+'</label>'+
				'<input name="'+file.id+'_feat" id="'+file.id+'_feat" type="checkbox" /> '+___upLang('ff2')+'<br /><br />'+
				
				___filePropSaveButtons(file.id)+
				
			"</td>"+
		"</tr>"
	);
}

function fileDialogComplete(num)
{
	if(prevFile)
	{
		$('#alert1').show();
		setTimeout('$(".alert").hide("normal");', 2500);
		prevFile = false;
	}
	
	if(fileToBig)
	{
		$('#alert2').show();
		setTimeout('$(".alert").hide("normal");', 2500);
		fileToBig = false;
	}
	
	if(fileNotAllowed)
	{
		$('#alert3').show();
		setTimeout('$(".alert").hide("normal");', 2500);
		fileNotAllowed = false;
	}
	updatePendingFileCount();
	
	var files = $('#filesHidden').html();
	$('#file_list_table').append(files);
	$('#file_list_table:nth-child(even)').addClass('row-b');
	$('#files').show();
	$('#filesHidden').empty();
}

function clearUploadQueue()
{
	while(uploader.total.queued > 0) {
		plupload.removeFile();
		file = plupload.getFile();
		rm_file(file.id);
	}
};

function flashUploadError(file, errorCode, message)
{
	alert("Upload Failed("+errorCode+"): "+ message);
	$('.debug').append(errorCode+": "+message+"\n");
}

function flashUploadQueueError(file,errorCode, message)
{
	if(errorCode == -110)
	{
		fileToBig = true;
	}
	else if(errorCode == -120)
	{
		
	}
	else if(errorCode == -100)
	{
		___toManyFilesError();
	}
	else
	{
		___generalError();
	}
}

function genRandId(length)
{
  chars = "abcdef1234567890";
  pass = "";
  for(x=0;x<length;x++)
  {
	i = Math.floor(Math.random() * 16);
	pass += chars.charAt(i);
  }
  return pass;
}

function debug_function(message)
{
	$('.debug').append(message+"\n");
}
	

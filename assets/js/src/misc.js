
// Enable cross browser/platform clipboard access.
function copyToClipboard(inElement) 
{
	if (inElement.createTextRange) 
	{
		var range = inElement.createTextRange();
		if (range)
		{
			range.execCommand('Copy');
		}
	} 
	else 
	{
		var flashcopier = 'flashcopier';
		if(!document.getElementById(flashcopier)) 
		{
			var divholder = document.createElement('div');
			divholder.id = flashcopier;
			document.body.appendChild(divholder);
		}
		document.getElementById(flashcopier).innerHTML = '';
		var divinfo = '<embed src="'+___baseUrl()+'/assets/flash/_clipboard.swf" FlashVars="clipboard='+encodeURIComponent(inElement.value)+'" width="0" height="0" type="application/x-shockwave-flash"></embed>';
		document.getElementById(flashcopier).innerHTML = divinfo;
	}
}

function end(array) 
{
	// http://kevin.vanzonneveld.net
	var last_elm, key;
	
	if (array.constructor == Array)
	{
		last_elm = array[(array.length-1)];
	} 
	else 
	{
		for (key in array)
		{
			last_elm = array[key];
		}
	}

	return last_elm;
}

function in_array(needle, haystack, strict) 
{
	// http://kevin.vanzonneveld.net
	var found = false, key, strict = !!strict;
 
	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}
	return found;
}

function getExtension(file)
{
	return end(file.split('.')).toLowerCase();	
}

function switch_checkboxes(checked) {
	var the_id = this.id;
	if(checked == false) {
		$("input:checkbox").each( function() {
			if(this.id != the_id) {
				this.checked = false;
			}
		});
	} else {
		$("input:checkbox").each( function() {
			if(this.id != the_id) {
				this.checked = true;
			}
		});
	}
}

function switch_checkbox(id) {
	$('#'+id).each( function() {
		$(this).get(0).checked = !$(this).get(0).checked;
	});
}

function sort_form(col, dir) {
	$('#formS').val(col);
	$('#formD').val(dir);
	$('#sort_form').get(0).submit();
}

function manage_checkboxes() {
	var boxes = [];
	var is_all_checked = true;
	var i = 0;
	// get all main checkboxes and manage them muwahhahh!!!!
	$("input[id^='check-']:checkbox").each( function() {
		if(this.id != 'switch_box' && is_all_checked == true) {
			if(this.checked === false) {
				is_all_checked = false;
			}
		}
	});
	if(is_all_checked) {
		$('#switch_box').get(0).checked = true;
	} else {
		$('#switch_box').get(0).checked = false;
	}
}

$(document).ready(function() {
	$('.fancybox').fancybox();
});

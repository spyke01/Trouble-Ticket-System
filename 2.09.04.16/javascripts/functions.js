/*-------------------------------------------------------------------------*/
// General Functions
/*-------------------------------------------------------------------------*/	
function confirmDelete(text) {
    return confirm("Are you sure you want to delete this "+ text +"?");
}

function fetchItem(itemID) {
	if (document.getElementById) { return document.getElementById(itemID); }
	else if (document.all) { return document.all[itemID]; }
	else if (document.layers) { return document.layers[itemID]; }
	else { return null; }
}

function sqr_show_hide(id) {
	var item = fetchItem(id)

	if (item && item.style) {
		if (item.style.display == "none") {
			item.style.display = "";
		}
		else {
			item.style.display = "none";
		}
	}
	else if (item) {
		item.visibility = "show";
	}
}

function sqr_show_hide_with_img(itemID) {
	obj = fetchItem('slideDiv' + itemID);
	img = fetchItem('slideImg' + itemID);

	if (!obj) {
		// nothing to collapse!
		if (img) {
			// hide the clicky image if there is one
			img.style.display = 'none';
		}
		return false;
	}
	else {
		if (obj.style.display == 'none') {
			obj.style.display = '';
			if (img) {
				img_re = new RegExp("_collapsed\\.jpg$");
				img.src = img.src.replace(img_re, '.jpg');
			}
		}
		else {
			obj.style.display = 'none';
			if (img) {
				img_re = new RegExp("\\.jpg$");
				img.src = img.src.replace(img_re, '_collapsed.jpg');
			}
		}
	}
	return false;
}

/*-------------------------------------------------------------------------*/
// Ajax Functions
/*-------------------------------------------------------------------------*/	
function ajaxDeleteNotifier(spinDivID, action, text, row) {
    if (confirm("Are you sure you want to delete this "+ text +"?")) {
		sqr_show_hide(spinDivID);
		new Ajax.Request(action, {asynchronous:true, onSuccess:function(){ new Effect.SlideUp(row); }});
	}
}

function ajaxUpdaterWithSpinner(spinDivID, action) {
	sqr_show_hide(spinDivID);
	new Ajax.Request(action, {asynchronous:true, onSuccess:function(){ sqr_show_hide(spinDivID) }});
}

function ajaxChangeLanguage(spinDivID, action, locationURL) {
	sqr_show_hide(spinDivID);
	new Ajax.Request(action, {asynchronous:true, onSuccess:function(){ window.location = locationURL; }});	
}

function ajaxShowHideSliderWithImg(itemID) {
	obj = fetchItem('slideDiv' + itemID);
	img = fetchItem('slideImg' + itemID);
	status = fetchItem('slideStatus' + itemID);

	if (!obj) {
		// nothing to collapse!
		if (img) {
			// hide the clicky image if there is one
			img.style.display = 'none';
		}
		return false;
	}
	else {
		if (status.value == '0') {
			new Effect.SlideDown('slideDiv' + itemID);
			status.value = '1';
			if (img) {
				img_re = new RegExp("_collapsed\\.jpg$");
				img.src = img.src.replace(img_re, '.jpg');
			}
		}
		else {
			new Effect.SlideUp('slideDiv' + itemID);
			status.value = '0';
			if (img) {
				img_re = new RegExp("\\.jpg$");
				img.src = img.src.replace(img_re, '_collapsed.jpg');
			}
		}
	}
	return false;
}
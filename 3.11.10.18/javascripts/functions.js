/*-------------------------------------------------------------------------*/
// Ajax Functions
/*-------------------------------------------------------------------------*/	
invoicesProductRowNumber = 0;

function ajaxDeleteNotifier(spinDivID, action, text, row) {
    if (confirm("Are you sure you want to delete this " + text + "?")) {
		$('#' + spinDivID).toggle();	
		jQuery.get(action, function(data) { $('#' + row).hide('drop',{},500); });
	}
}

function ajaxGetWithProgress(spinDivID, action) {
		$('#' + spinDivID).toggle();	
		jQuery.get(action, function(data) { $('#' + spinDivID).toggle(); });
}

function ajaxQuickDivUpdate(action, divID, spinnerHTML) {
	jQuery.get(action, function(data) {
		// Clear the current graph and show the new one
		$('#' + divID).html(spinnerHTML);
		$('#' + divID).html(data);
	});
}

$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = -1;
	});
};

function returnSuccessMessage(itemName) { 
    return "<span class=\"greenText bold\">Successfully created " + itemName + "!</span>";
}
<?php 
/***************************************************************************
 *                               tickets.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***************************************************************************/
	
	//=========================================================
	// Gets a status name from a statusid
	//=========================================================
	function getTicketStatus($status) {
		global $TICKET_STATUS;
		
		if ($status < count($TICKET_STATUS)) { return $TICKET_STATUS[$status]; }
		else { return "Unknown Status"; }
	}
 
	//=========================================================
	// Gets a tickets owner from a userid
	//=========================================================
	function getTicketOwnerFromID($ticketID) {
		$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "tickets` WHERE id='" . $ticketID . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['user_id'];
			}	
			mysql_free_result($result);
		}
	}
 
	//=========================================================
	// Gets a tickets tech from a userid
	//=========================================================
	function getTicketTechFromID($ticketID) {
		$sql = "SELECT tech_id FROM `" . DBTABLEPREFIX . "tickets` WHERE id='" . $ticketID . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['tech_id'];
			}	
			mysql_free_result($result);
		}
	}
 
	//=========================================================
	// Gets a tickets title from a userid
	//=========================================================
	function getTicketTitleFromID($ticketID) {
		$sql = "SELECT title FROM `" . DBTABLEPREFIX . "tickets` WHERE id='" . $ticketID . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['title'];
			}	
			mysql_free_result($result);
		}
	}
	
	//=========================================================
	// Sends update emails for tickets
	// UpdateTypes
	//    0 = New Ticket
	// 	  1 = Ticket Reply
	//=========================================================
	function sendTicketEMail($ticketID, $userID, $updateType) {
		global $tts_config, $LANG;
		
		// Find out if we are emailing the admins or the users
		if ($userID == getTicketOwnerFromID($ticketID)) {
			// The user was the one who made the update
			$emailAddress = ($updateType == 1) ? getEmailAddressFromID(getTicketTechFromID($ticketID)) : "";
			$emailAddress = ($emailAddress == "") ? $tts_config['ftstts_admin_email'] : $emailAddress;
		}
		else {
			// An admin was the one who made the update
			$emailAddress = getEmailAddressFromID($userID);
		}
		
		// Create our subject and message
		$url = "<a href=\"http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/\">http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/</a>";
		$subject = ($updateType == 0) ? "New " . $tts_config['ftstts_site_name'] . " Trouble Ticket #" . $ticketID : "Your " . $tts_config['ftstts_site_name'] . " Trouble Ticket #" . $ticketID . " has been updated";
		$message = ($updateType == 0) ? "A new trouble ticket has been created by " . getUsernameFromID($userID) . ", you can view this ticket by visiting " . $url . "." : "A new entry has been posted on your trouble ticket by " . getUsernameFromID($userID) . ", you can view this update by visiting " . $url . ".";
		
		// Send the message
		$success = emailMessage($emailAddress, $subject, $message);
	}
	
 	//=================================================
	// Print the Tickets Table
	//=================================================
	function printViewTicketTable($ticketID) {
		global $menuvar, $tts_config, $LANG;
		
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "tickets` WHERE id = '" . $ticketID . "'";
		$result = mysql_query($sql);
		
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "viewTicketTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_TICKET'] . " #" . $ticketID . "", "colspan" => "8")), "", "title1", "thead");
		
		// Create section title
		$table->addNewRow(array(array("data" => $LANG['TABLEHEADERS_TICKET_INFORMATION'], "colspan" => "2")), "", "title2", "thead");
		
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_TICKET_INFORMATION'], "colspan" => "2")), "", "greenRow");
		}
		else {
			while ($row = mysql_fetch_array($result)) {
				$ticketUser = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? createDropdown("users", "user_id", $row['user_id'], "ajaxGetWithProgress('updateUserSpinner', 'ajax.php?action=updateitem&table=tickets&item=user_id&id=" . $ticketID . "&value=' + $('#user_id').val())", "") . "<span id=\"updateUserSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID($row['user_id']);
				$ticketTech = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? createDropdown("techs", "tech_id", $row['tech_id'], "ajaxGetWithProgress('updateTechSpinner', 'ajax.php?action=updateitem&table=tickets&item=tech_id&id=" . $ticketID . "&value=' + $('#tech_id').val())", "") . "<span id=\"updateTechSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID($row['tech_id']);
				$ticketStatus = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? createDropdown("ticketstatus", "status", $row['status'], "ajaxGetWithProgress('updateStatusSpinner', 'ajax.php?action=updateitem&table=tickets&item=status&id=" . $ticketID . "&value=' + $('#status').val())", "") . "<span id=\"updateStatusSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getTicketStatus($row['status']);
				
				// Add our data
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_TITLE']),
						array("data" => $row['title'])
					), "", "row1", "thead"
				);
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_USER']),
						array("data" => $ticketUser)
					), "", "row2", "thead"
				);
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_PROBLEM_CATEGORY']),
						array("data" => getCatNameByID($row['cat_id']))
					), "", "row1", "thead"
				);
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_TECHNICIAN']),
						array("data" => $ticketTech)
					), "", "row2", "thead"
				);
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_DATE_CREATED']),
						array("data" => makeShortDateTime($row['datetimestamp']))
					), "", "row1", "thead"
				);
				$table->addNewRow(
					array(
						array("type" => "th", "data" => $LANG['TABLEHEADERS_STATUS']),
						array("data" => $ticketStatus)
					), "", "row2", "thead"
				);
			}
			mysql_free_result($result);
		}
		
		// Create section title
		$table->addNewRow(array(array("data" => $LANG['TABLEHEADERS_TICKET_ENTRIES'], "colspan" => "8")), "", "title2", "thead");
				
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "entries` WHERE ticket_id = '" . $ticketID . "' ORDER BY datetimestamp ASC";
		$result = mysql_query($sql);
		
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_TICKET_ENTRIES'], "colspan" => "8")), "viewTicketTableDefaultRow", "greenRow");
		}
		else {
			$x = 1;
			
			while ($row = mysql_fetch_array($result)) {
				$deleteButton = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? "<br />" . createDeleteLinkWithImage($row['id'], $row['id'] . "_row", "entries", "entry") : "";
				$table->addNewRow(
					array(
						array("data" => "<strong>" . getUsernameFromID($row['user_id']) . "</strong><br />" . makeShortDateTime($row['datetimestamp']) . $deleteButton, "class" => "center"),
						array("data" => bbcode($row['text']))
					), $row['id'] . "_row", "row" . $x
				);
				
				$x = ($x == 1) ? 2 : 1;
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML() . "
				<div id=\"viewTicketTableUpdateNotice\"></div>";
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// tickets table
	//=================================================
	function returnViewTicketTableJQuery() {							
		$JQueryReadyScripts = "";
		
		return $JQueryReadyScripts;
	}
	
 	//=================================================
	// Print the Search Tickets Form
	//=================================================
	function printSearchTicketsTable($getVars = array()) {
		global $menuvar, $tts_config, $LANG;
			
		$x = 1; //reset the variable we use for our row colors	
		
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_SEARCH_TICKETS'], "colspan" => "2")), "", "title1");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_SEARCH_HOW_TO_MESSAGE'], "colspan" => "2")
			), "", "title2"
		);
							
		// Add our data
		$table->addNewRow(
			array(
				array("data" => $LANG['TABLEHEADERS_ID'] . ":"),
				array("data" => "<input type=\"text\" name=\"id\" size=\"40\" value=\"" . keeptasafe($getVars['id']) . "\" />")
			), "", "row1"
		);
		$table->addNewRow(
			array(
				array("data" => $LANG['TABLEHEADERS_TITLE'] . ":"),
				array("data" => "<input type=\"text\" name=\"title\" size=\"40\" value=\"" . keeptasafe($getVars['title']) . "\" />")
			), "", "row2"
		);
		$table->addNewRow(
			array(
				array("data" => $LANG['TABLEHEADERS_USER'] . ":"),
				array("data" => createDropdown("users", "user_id", keeptasafe($getVars['user_id']), "", ""))
			), "", "row1"
		);
		$table->addNewRow(
			array(
				array("data" => $LANG['TABLEHEADERS_TECHNICIAN'] . ":"),
				array("data" => createDropdown("techs", "tech_id", keeptasafe($getVars['tech_id']), "", ""))
			), "", "row2"
		);

		
		// Return the table's HTML
		$content = "
				<form name=\"searchTicketsForm\" id=\"searchTicketsForm\" action=\"" . $menuvar['TICKETS'] . "\" method=\"post\" onsubmit=\"return false;\">
					" . $table->returnTableHTML() . "
					<input type=\"submit\" name=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_SEARCH'] . "\" />
				</form>";
		
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// search ticket form
	//=================================================
	function returnSearchTicketsTableJQuery() {			
		$JQueryReadyScripts = "
			var v = jQuery(\"#searchTicketsForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {	
					jQuery.get('ajax.php?action=searchTickets', $('#searchTicketsForm').serialize(), function(data) {
						// Update the proper div with the returned data
						$('#updateMeTickets').html(data);
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}
	
 	//=================================================
	// Print the Tickets Table
	//=================================================
	function printTicketsTable($getVars = array()) {
		global $menuvar, $tts_config, $LANG;
		
		// Allow us to pick ticket state
		$ticketStatus = $getVars['status'];
		$ticketStatusText = ($ticketStatus != "") ? " - " . getTicketStatus($ticketStatus) . " " . $LANG['TABLETITLES_CURRENT_TICKETS_ONLY'] : " - " . $LANG['TABLETITLES_CURRENT_ALL_TICKETS'];
		
		// Allow Searching
		$search_ticketID = keepsafe($getVars['id']);
		$search_ticketTitle = keeptasafe($getVars['title']);
		$search_ticketUserID = keepsafe($getVars['user_id']);
		$search_ticketTechID = keepsafe($getVars['tech_id']);
		
		// Create extra SQL for our query
		$extraSQL = "";
		if (($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN)) {
			$extraSQL .= ($search_ticketUserID != "") ? " user_id = '" . $search_ticketUserID . "'" : "";
		}
		else {
			$extraSQL .= " user_id = '" . $_SESSION['userid'] . "'";
		}
		
		if ($search_ticketID != "") {
			$extraSQL = ($extraSQL != "") ? $extraSQL . " AND" : "";
			$extraSQL .= " id = '" . $search_ticketID . "'";
		}
		
		if ($search_ticketTitle != "") {
			$extraSQL = ($extraSQL != "") ? $extraSQL . " AND" : "";
			$extraSQL .= " title LIKE '%" . $search_ticketTitle . "%'";
		}
		
		if ($search_ticketTechID != "") {
			$extraSQL = ($extraSQL != "") ? $extraSQL . " AND" : "";
			$extraSQL .= " tech_id = '" . $search_ticketTechID . "'";
		}
		
		if ($ticketStatus != "") {
			$extraSQL = ($extraSQL != "") ? $extraSQL . " AND" : "";
			$extraSQL .= " status = '" . $ticketStatus . "'";
		}
		
		$extraSQL = ($extraSQL != "") ? " WHERE" . $extraSQL : "";
		
		// Execute our custom query
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "tickets`" . $extraSQL . " ORDER BY title ASC";
		$result = mysql_query($sql);
		//echo $sql;
		
		$numRows = ($result && mysql_num_rows($result) > 0) ? mysql_num_rows($result) : 0;
		
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "ticketsTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_CURRENT_TICKETS'] . " (" . $numRows . ")" . $ticketStatusText, "colspan" => "8")), "", "title1", "thead");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_ID']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_TITLE']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USER']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_PROBLEM_CATEGORY']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_TECHNICIAN']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_DATE_CREATED']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_STATUS']),
				array("type" => "th", "data" => "")
			), "", "title2", "thead"
		);
							
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_TICKETS'], "colspan" => "8")), "ticketsTableDefaultRow", "greenRow");
		}
		else {
			while ($row = mysql_fetch_array($result)) {	
				$rowColor = ($row['status'] == 0) ? "greenRow" : "redRow";
				$rowColor = ($row['status'] == 2) ? "yellowRow" : $rowColor;
				
				$table->addNewRow(
					array(
						array("data" => $row['id']),
						array("data" => "<a href=\"" . $menuvar['VIEWTICKET'] . "&id=" . $row['id'] . "\">" . $row['title'] . "</a>"),
						array("data" => getUsernameFromID($row['user_id'])),
						array("data" => getCatNameByID($row['cat_id'])),
						array("data" => getUsernameFromID($row['tech_id'])),
						array("data" => makeShortDateTime($row['datetimestamp'])),
						array("data" => getTicketStatus($row['status'])),
						array("data" => "<a href=\"" . $menuvar['VIEWTICKET'] . "&id=" . $row['id'] . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/check.png\" alt=\"Edit Ticket Details\" /></a> " . createDeleteLinkWithImage($row['id'], $row['id'] . "_row", "tickets", "ticket"), "class" => "center")
					), $row['id'] . "_row", $rowColor
				);
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML() . "
				<div id=\"ticketsTableUpdateNotice\"></div>";
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// tickets table
	//=================================================
	function returnTicketsTableJQuery() {							
		$JQueryReadyScripts = "
				$('#ticketsTable').tablesorter({ headers: { 7: { sorter: false } } });";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Create a form to add new tickets
	//
	// Used so that we can display it in many places
	//=================================================
	function printNewTicketForm() {
		global $menuvar, $tts_config, $LANG;
		
		$content .= "
				<div id=\"newTicketResponse\">
				</div>
				<form name=\"newTicketForm\" id=\"newTicketForm\" action=\"" . $menuvar['TICKETS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_NEW_TICKET'] . "</legend>
						<div><label for=\"title\">" . $LANG['FORMITEMS_TITLE'] . " <span>- Required</span></label> <input name=\"title\" id=\"title\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"cat_id\">" . $LANG['FORMITEMS_PROBLEM_CATEGORY'] . " <span>- Required</span></label> " . createDropdown("categories", "cat_id", "", "", "required") . "</div>";
		
		$content .= ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? "
						<div><label for=\"user_id\">" . $LANG['FORMITEMS_USER'] . " <span>- Required</span></label> " . createDropdown("users", "user_id", "", "", "required") . "</div>" : "";
		
		$content .= "
						<div><label for=\"text\">" . $LANG['FORMITEMS_PROBLEM'] . " <span>- Required</span></label> <textarea name=\"text\" id=\"text\" cols=\"45\" rows=\"10\" class=\"required\"></textarea></div>
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"Create Ticket\" /> <input type=\"button\" id=\"clearFormButton\" class=\"button\" value=\"Clear Form\" /></div>
					</fieldset>
				</form>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new ticket form
	//=================================================
	function returnNewTicketFormJQuery($reprintTable = 0, $allowModification = 1) {		
		$extraJQuery = ($reprintTable == 0) ? "
  						// Update the proper div with the returned data
						$('#newTicketResponse').html('" . progressSpinnerHTML() . "');
						$('#newTicketResponse').html(data);
						$('#newTicketResponse').effect('highlight',{},500);" 
						: "
						// Clear the default row
						$('#ticketsTableDefaultRow').remove();
  						// Update the table with the new row
						$('#ticketsTable > tbody:last').append(data);
						$('#ticketsTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
						// Show a success message
						$('#newTicketResponse').html('" . progressSpinnerHTML() . "');
						$('#newTicketResponse').html(returnSuccessMessage('ticket'));";
							
		$JQueryReadyScripts = "
			var v = jQuery(\"#newTicketForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {			
					jQuery.get('ajax.php?action=createTicket&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification . "', $('#newTicketForm').serialize(), function(data) {
  						" . $extraJQuery . "
					});
				}
			});
			$('#clearFormButton').click(function () {
				if (confirm('Are you sure you want to clear this form?')) {
					$('#newTicketForm').clearForm();
				}
			});";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Create a form to add new tickets
	//
	// Used so that we can display it in many places
	//=================================================
	function printNewTicketEntryForm() {
		global $menuvar, $tts_config, $LANG;
		
		$content .= "
				<div id=\"newTicketEntryResponse\">
				</div>
				<form name=\"newTicketEntryForm\" id=\"newTicketEntryForm\" action=\"" . $menuvar['TICKETS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_NEW_TICKET_ENTRY'] . "</legend>
						<div><label for=\"user_id\">" . $LANG['FORMITEMS_USER'] . " </label> " . $_SESSION['username'] . "</div>
						<div><label for=\"text\">" . $LANG['FORMITEMS_ENTRY'] . " <span>- Required</span></label> <textarea name=\"text\" id=\"text\" cols=\"45\" rows=\"10\" class=\"required\"></textarea></div>
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"Add Ticket Entry\" /> <input type=\"button\" id=\"clearFormButton\" class=\"button\" value=\"Clear Form\" /></div>
					</fieldset>
				</form>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new ticket form
	//=================================================
	function returnNewTicketEntryFormJQuery($ticketID, $reprintTable = 0) {		
		$extraJQuery = ($reprintTable == 0) ? "
  						// Update the proper div with the returned data
						$('#newTicketEntryResponse').html('" . progressSpinnerHTML() . "');
						$('#newTicketEntryResponse').html(data);
						$('#newTicketEntryResponse').effect('highlight',{},500);" 
						: "
  						// Update the table with the new row
						$('#viewTicketTable > tbody:last').append(data);
						$('#ticketsTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
						// Show a success message
						$('#newTicketEntryResponse').html('" . progressSpinnerHTML() . "');
						$('#newTicketEntryResponse').html(returnSuccessMessage('ticket'));";
							
		$JQueryReadyScripts = "
			var v = jQuery(\"#newTicketEntryForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {			
					jQuery.get('ajax.php?action=createTicketEntry&reprinttable=" . $reprintTable . "&id=" . $ticketID . "', $('#newTicketEntryForm').serialize(), function(data) {
  						" . $extraJQuery . "
					});
				}
			});
			$('#clearFormButton').click(function () {
				if (confirm('Are you sure you want to clear this form?')) {
					$('#newTicketEntryForm').clearForm();
				}
			});";
		
		return $JQueryReadyScripts;
	}

?>
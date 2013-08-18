<?php 
/***************************************************************************
 *                               reports.php
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
	
	//=================================================
	// Print the Tickets Report
	//=================================================
	function printTicketsReport($startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "") {
		global $LANG;
		
		$extraSQL = ($startDatetimestamp == "" || $stopDatetimestamp == "") ? "" : " WHERE datetimestamp >= '" . $startDatetimestamp . "' AND datetimestamp < '" . $stopDatetimestamp . "'";
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "tickets`" . $extraSQL . " ORDER BY datetimestamp DESC";
		$result = mysql_query($sql);
			
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "ticketsReportTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_TICKETS'] . $titleSuffix, "colspan" => "9")), "", "title1", "thead");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_ID']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_TITLE']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USER']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_PROBLEM_CATEGORY']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_TECHNICIAN']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_DATE_CREATED']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_STATUS'])
			), "", "title2", "thead"
		);
		
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_TICKETS'], "colspan" => "9")), "ticketsReportTableDefaultRow", "greenRow");
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
						array("data" => getTicketStatus($row['status']))
					), $row['id'] . "_row", $rowColor
				);
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML();
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// Invoices report
	//=================================================
	function returnTicketsReportJQuery() {
		$JQueryReadyScripts = "
				$('#ticketsReportTable').tablesorter({ widgets: ['zebra'] });";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Print the Ticket Entries Report
	//=================================================
	function printTicketEntriesReport($startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "") {
		global $LANG;
		$ticketID = "";
		$x = 1;
		
		$extraSQL = ($startDatetimestamp == "" || $stopDatetimestamp == "") ? "" : " WHERE datetimestamp >= '" . $startDatetimestamp . "' AND datetimestamp < '" . $stopDatetimestamp . "'";
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "entries`" . $extraSQL . " ORDER BY datetimestamp DESC, ticket_id";
		$result = mysql_query($sql);
			
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "ticketEntriesReportTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_TICKET_ENTRIES'] . $titleSuffix, "colspan" => "3")), "", "title1", "thead");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_DATE_CREATED']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USER']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_ENTRY'])
			), "", "title2 noWrap", "thead"
		);
		
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_TICKET_ENTRIES'], "colspan" => "3")), "ticketEntriesReportTableDefaultRow", "greenRow");
		}
		else {
			while ($row = mysql_fetch_array($result)) {
				if ($ticketID != $row['ticket_id']) {
					$status = getTicketStatus($row['ticket_id']);
					$rowColor = ($status == 0) ? "greenRow" : "redRow";
					$rowColor = ($status == 2) ? "yellowRow" : $rowColor;
					
					$table->addNewRow(array(array("type" => "th", "data" => getTicketTitleFromID($row['ticket_id']), "colspan" => "3")), "ticketEntriesReportTableDefaultRow", $rowColor);
					$x =  1;
				}
			
				$table->addNewRow(array(
					array("data" => makeShortDateTime($row['datetimestamp'])),
					array("data" => getUsernameFromID($row['user_id'])),
					array("data" => bbcode($row['text']))
				), "", "row" . $x);
				
				$x = ($x == 1) ? 2 : 1;
				$ticketID = $row['ticket_id'];
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML();
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// Ticket Entries report
	//=================================================
	function returnTicketEntriesReportJQuery() {
		$JQueryReadyScripts = "";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Print the User Details Report
	//=================================================
	function printUserDetailsReport($startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "") {
		global $LANG;
		
		$extraSQL = ($startDatetimestamp == "" || $stopDatetimestamp == "") ? "" : " WHERE signup_date >= '" . $startDatetimestamp . "' AND signup_date < '" . $stopDatetimestamp . "'";
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "users`" . $extraSQL . " ORDER BY user_level, last_name, first_name";
		$result = mysql_query($sql);
			
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "userDetailsReportTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_USER_DETAILS'] . $titleSuffix, "colspan" => "18")), "", "title1", "thead");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USER_LEVEL']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_LAST_NAME']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_FIRST_NAME']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_COMPANY']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_EMAIL_ADDRESS']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_WEBSITE']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USERNAME']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_NOTES'])
			), "", "title2 noWrap", "thead"
		);
		
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_USERS'], "colspan" => "18")), "userDetailsReportTableDefaultRow", "greenRow");
		}
		else {
			while ($row = mysql_fetch_array($result)) {				
				$table->addNewRow(array(
					array("data" => $row['user_level']),
					array("data" => $row['last_name']),
					array("data" => $row['first_name']),
					array("data" => $row['company']),
					array("data" => $row['email_address']),
					array("data" => $row['website']),
					array("data" => $row['username']),
					array("data" => bbcode($row['notes']))
				), "", "noWrap");
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML();
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// User Details report
	//=================================================
	function returnUserDetailsReportJQuery() {		
		$JQueryReadyScripts = "
				$('#userDetailsReportTable').tablesorter({ widgets: ['zebra'] });";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Create a form to run a custom report
	//
	// Used so that we can display it in many places
	//=================================================
	function printNewReportForm() {
		global $menuvar, $tts_config, $LANG;

		$content .= "
				<form name=\"newReportForm\" id=\"newReportForm\" action=\"" . $menuvar['VIEWREPORT'] . "\" method=\"post\" class=\"inputForm\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_GENERATE_A_CUSTOM_REPORT'] . "</legend>
						<h3>1. " . $LANG['FORMITEMS_CHOOSE_REPORT'] . "</h3>
						<div><label for=\"report\">" . $LANG['FORMITEMS_REPORT'] . " <span>- Required</span></label> " . createDropdown("reports", "report", "invoicedVsPaid", "", "required") . "</div>
						
						<h3>2. " . $LANG['FORMITEMS_CHOOSE_DATE_RANGE'] . "</h3>
						<div><label for=\"daterange\">" . $LANG['FORMITEMS_DATE_RANGE'] . " <span>- Required</span></label> " . createDropdown("daterange", "daterange", "allTime", "", "required") . "</div>
						<div><label for=\"start_date\">" . $LANG['FORMITEMS_START_DATE'] . " </label> <input type=\"text\" name=\"start_date\" id=\"start_date\" size=\"60\" /></div>
						<div><label for=\"stop_date\">" . $LANG['FORMITEMS_STOP_DATE'] . " </label> <input type=\"text\" name=\"stop_date\" id=\"stop_date\" size=\"60\" /></div>
						
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_CREATE_REPORT'] . "\" /></div>
					</fieldset>
				</form>
				<div id=\"newReportResponse\">
				</div>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new graph form
	//=================================================
	function returnNewReportFormJQuery($reprintGraph = 0) {
						
		$JQueryReadyScripts = "
			$('#start_date').datepicker({
				showButtonPanel: true
			});
			$('#stop_date').datepicker({
				showButtonPanel: true
			});";
		
		return $JQueryReadyScripts;
	}

?>
<?php 
/***************************************************************************
 *                               admin.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * This program is licensed under the Fast Track Sites Program license 
 * located inside the license.txt file included with this program. This is a 
 * legally binding license, and is protected by all applicable laws, by 
 * editing this page you fall subject to these licensing terms.
 *
 ***************************************************************************/

if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD) {
	$action = $_GET['action'];
	$action = parseurl($action);
	$postusername = parseurl($_POST['username']);
	$ticketid = parseurl($_POST['ticketid']);
	$tech = parseurl($_POST['tech']);
	$pcat = parseurl($_POST['pcat']);
	$limit1 = '0';
	$limit2 = '100';
		
	if(isset($_POST['limit1'])) $limit1 = $_POST['limit1'];
	if(isset($_POST['limit2'])) $limit2 = $_POST['limit2'];
			
	unset($_POST['limit1']);
	unset($_POST['limit2']);
			
	//=======================================
	// See if were searching the db, odering 
	// it, or just checking them all
	//=======================================
	if ($action == 'search') {
		$msg = "\n<strong><u>Search Results:</u></strong><br />";
		if (isset($_POST['username']) && $_POST['username'] != '') {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_user_id='" . $postusername . "'";
			unset($_POST['username']);
		}
		elseif (isset($_POST['ticketid']) && $_POST['ticketid'] != '') {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_id='" . $ticketid . "'";
			unset($_POST['ticketid']);
		}
		elseif (isset($_POST['tech']) && $_POST['tech'] != '') {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_tech_id='" . $tech . "'";
			unset($_POST['tech']);
		}
		elseif (isset($_POST['pcat']) && $_POST['pcat'] != '') {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_pcat_id='" . $pcat . "'";
			unset($_POST['tech']);
		}
		else {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT " . $limit1 . ", " . $limit2;
		}		
	}
	
	// ************** POSSIBLE ACTION: Change a ticket 
	if(isset($_POST['ChgTicket'])) {
		foreach($_POST['ChgTicket'] as $itemid => $qty) {
			if($qty == 1) {
				$ticketid = $itemid;
				$status = $_POST['status'][$ticketid];
				$tech = $_POST['tech'][$ticketid];
					
				$ticketid = str_replace("\\", "",str_replace("'", "", $ticketid));
				$sql = "UPDATE `" . $DBTABLEPREFIX . "tickets` SET tickets_status = '" . $status . "', tickets_tech_id = '" . $tech . "' WHERE tickets_id = '" . $ticketid . "' LIMIT 1";
				//$content .= $sql;
				$result = mysql_query($sql);
			}
		}
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT " . $limit1 . ", " . $limit2;
		unset($_POST['ChgTicket']);
	}
	
	if ($action == "open") { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_status='" . TICKET_OPEN . "' ORDER BY `tickets_opened` DESC LIMIT " . $limit1 . ", " . $limit2; }
	elseif ($action == "closed") { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_status='" . TICKET_CLOSED . "' ORDER BY `tickets_opened` DESC LIMIT " . $limit1 . ", " . $limit2; }
	elseif ($action != "search") { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` ORDER BY `tickets_opened` DESC LIMIT " . $limit1 . ", " . $limit2; }
		
	version_functions(yes);
		
	//=======================================
	// Show our search box
	//=======================================
	$content .= "
					<br />
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
						<tr>
							<td class=\"title2\" style=\"text-align: center;\"><a href=\"$menuvar[ADMIN]\">$T_VIEW_ALL_TICKETS</a> &nbsp;|&nbsp; <a href=\"$menuvar[ADMIN]&action=open\">$T_VIEW_ALL_OPEN_TICKETS</a> &nbsp;|&nbsp; <a href=\"$menuvar[ADMIN]&action=closed\">$T_VIEW_ALL_CLOSED_TICKETS</a></td>
						</tr>
					</table>
					<br />
					<div class=\"center\">
						<form name=\"search\" action=\"" . $menuvar['ADMIN'] . "&action=search\" method=\"post\">
							<table class=\"contentBoxHalfWidth\" cellpadding=\"1\" cellspacing=\"1\">
								<tr>
									<td class=\"title1\" colspan=\"2\">" . $T_SEARCH . "</td>
								</tr>
								<tr>
									<td class=\"title2\" colspan=\"2\">" . $T_SEARCH_WARNING . "</td>
								</tr>
								<tr>
									<td class=\"row2\"><strong>" . $T_USER . ": </strong></td>
									<td class=\"row2\">
										" . createDropdown("users", "username", "") . "
									</td>
								</tr>
								<tr>
									<td class=\"row2\"><strong>" . $T_TICKET_NUMBER . ": </strong></td>
									<td class=\"row2\"><input type=\"text\" name=\"ticketid\" /></td>
								</tr>
								<tr>
									<td class=\"row2\"><strong>" . $T_PROBLEM_CATEGORY . ": </strong></td>
									<td class=\"row2\">
										" . createDropdown("problemcategories", "pcat", "") . "
									</td>
								</tr>
								<tr>
									<td class=\"row2\"><strong>" . $T_TECHNICIAN . ": </strong></td>
									<td class=\"row2\">
										" . createDropdown("techs", "tech", "") . "
									</td>
								</tr>
							</table>
						<br />
						<input type=\"submit\" value=\"" . $T_SEARCH . "\" class=\"button\" />
						</form>
					</div>
					<br />
					" . $msg . "
					<div id=\"updateMe\">" . returnTicketsTable($sql, $limit1, $limit2) . "</div>
					<br /><br />
					<div id=\"response\"></div>
					<form name=\"newTicketForm\" id=\"newTicketForm\" action=\"" . $menuvar['post'] . "?action=newticket\" method=\"post\" onSubmit=\"return false;\">
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">" . $T_NEW_TROUBLE_TICKET . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_NAME . ": </strong></td>
								<td class=\"row1\">
									" . createDropdown("users", "userID", "") . "								
								</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_TICKET_TITLE . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"ticketTitle\" id=\"ticketTitle\" size=\"73\" class=\"required\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_PHONE_NUMBER . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"phoneNumber\" size=\"73\" class=\"required\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_MODEL_NAME . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"modelName\" size=\"73\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_SERIAL_NUMBER . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"serialNumber\" size=\"73\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_PROBLEM_CATEGORY . ": </strong></td>
							  	<td class=\"row1\">
									" . createDropdown("problemcategories", "pCatID", "") . "
							  	</td>
							</tr>
							<tr>
							  <td class=\"title2\" colspan=\"2\"><div class=\"center\"><textarea cols=\"70\" rows=\"8\" name=\"message\" wrap=\"virtual\" class=\"required\"></textarea><br /></div></td>
							</tr>		
						</table>	
						<br />
						<div class=\"center\"><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"" . $T_CREATE_TICKET . "\" class=\"button\" /> <span id=\"submitSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></div>	
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('newTicketForm', {immediate : true, useTitles:true, onFormValidate : ValidateForm});
				
						function ValidateForm(result, formRef) {
							if (result == true) {
								submitButton = fetchItem('submit');
								submitButton.disabled = true;
								sqr_show_hide('submitSpinner');
								new Ajax.Updater('updateMe', 'ajax.php?action=showIndicator', {asynchronous:true, evalScripts:true});
								new Ajax.Updater('response', 'ajax.php?action=postTicket', {onComplete:function(){ sqr_show_hide('submitSpinner'); submitButton.disabled = false; new Ajax.Updater('updateMe', 'ajax.php?action=printTicketsTable', {asynchronous:true, evalScripts:true}); },asynchronous:true, parameters:Form.serialize(document.newTicketForm), evalScripts:true});								
							}
							return false;
			 			}
					</script>";

	$page->setTemplateVar('PageContent', version_functions(yes) . $content);
}
else {
	$page->setTemplateVar('PageContent', "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>
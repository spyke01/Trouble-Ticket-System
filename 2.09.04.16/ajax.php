<?php 
/***************************************************************************
 *                               ajax.php
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
	include 'includes/header.php';
	
	$actual_id = keepsafe($_GET['id']);
	
	//================================================
	// Main updater and get functions
	//================================================
	// Update an item in a DB table
	if ($_GET['action'] == "updateitem") {
		$item = keepsafe($_GET['item']);
		$table = keepsafe($_GET['table']);
		$value = keepsafe($_REQUEST['value']);
		$tableabrev = ($_GET['table'] == "problemcategories") ? "pcats" : $table;
		$updateto = ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") ? strtotime($value) : $value;
		
		$sql = "UPDATE `" . $DBTABLEPREFIX . $table . "` SET " . $tableabrev . "_" . $item ." = '" . $updateto . "' WHERE " . $tableabrev . "_id = '" . $actual_id . "'";
		//echo $sql;
		
		// Only admins or Mods should be able to get whatever they want things
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			$result = mysql_query($sql);
			echo stripslashes($updateto);	
		}		
		else {
			// Run checks to verify access rights
		 	$authorized = 0;
			
			if ($table == "users" && $item == "language") { $authorized = 1; }
			
			if ($authorized) {
				$result = mysql_query($sql);
				echo stripslashes($updateto);
			}
		}		
	}
	
	// Get an item from a DB table
	elseif ($_GET['action'] == "getitem") {
		$item = keepsafe($_GET['item']);
		$table = keepsafe($_GET['table']);
		$tableabrev = ($_GET['table'] == "problemcategories") ? "pcats" : $table;
		$sqlrow = $tableabrev . "_" . $item;
		
		$sql = "SELECT $sqlrow FROM `" . $DBTABLEPREFIX . $table . "` WHERE " . $tableabrev . "_id = '" . $actual_id . "'";
		$result = mysql_query($sql);
		
		$row = mysql_fetch_array($result);
		mysql_free_result($result);		
		
		// Only admins or Mods should be able to get whatever they want things
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			if ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") { 
				$result =  (trim($row[$sqlrow]) != "") ? @gmdate('m/d/Y h:i A', $row[$sqlrow] + (3600 * '-5.00')) : ""; 
				echo $result;
			}
			else { echo bbcode($row[$sqlrow]); }		
		}		
	}	
	
	// Delete a row from a DB table
	elseif ($_GET['action'] == "deleteitem") {
		$table = $_GET['table'];
		$tableabrev = ($_GET['table'] == "problemcategories") ? "pcats" : $table;
		$sql = "DELETE FROM `" . $DBTABLEPREFIX . $table . "` WHERE " . $tableabrev . "_id = '" . $actual_id . "'";
		
		// Only admins or Mods should delete things
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			$result = mysql_query($sql);
		}
	}
		
	//================================================
	// Put our new category into the database
	//================================================
	elseif ($_GET['action'] == "postPCat") {
		$newcatname = keeptasafe($_POST['newcatname']);	
		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "problemcategories` (`pcats_name`) VALUES ('" . $newcatname . "')";
		$result = mysql_query($sql);
		
		echo returnPCatsTable();
	}
		
	//================================================
	// Put our new ticket into the database
	//================================================
	elseif ($_GET['action'] == "postTicket") {
		$datetimestamp = time();
		$userID = (($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) && isset($_POST['userID']) & $_POST['userID'] != "") ? keeptasafe($_POST['userID']) : $_SESSION['userid'];
		$ticketTitle = keeptasafe($_POST['ticketTitle']);
		$phoneNumber = keeptasafe($_POST['phoneNumber']);
		$modelName = keeptasafe($_POST['modelName']);
		$serialNumber = keeptasafe($_POST['serialNumber']);
		$pCatID = keeptasafe($_POST['pCatID']);
		$message = keeptasafe($_POST['message']);	
		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "tickets` (tickets_title, tickets_opened, tickets_user_id, tickets_phone_number, tickets_system, tickets_serial, tickets_pcat_id) VALUES('" . $ticketTitle . "', '" . $datetimestamp . "', '" . $userID . "', '" . $modelName . "', '" . $phoneNumber . "', '" . $serialNumber . "', '" . $pCatID . "')";
		$result = mysql_query($sql);
		
		$tickets_id = mysql_insert_id();
    
   		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "entries` (entries_ticket_id, entries_date, entries_user_id, entries_text) VALUES('" . $tickets_id . "', '" . $datetimestamp . "', '" . $userID . "', '" . $message . "')";
    	$result = mysql_query($sql);
		
		$content = ($result) ? "	<span style=\"color: green; font-weight: bold;\">Successfully created ticket!</span>" : "	<span style=\"color: red; font-weight: bold;\">Failed to create ticket!!!</span>";
		
		emailCreatedMessage($tickets_id, $userID);
		
		echo $content;
	}
		
	//================================================
	// Place a reply to a ticket into the database
	//================================================
	elseif ($_GET['action'] == "postReplyToTicket") {
		$datetimestamp = time();
		$message = keeptasafe($_POST['message']);	
    	
		if (trim($actual_id) != "") {
   			$sql = "INSERT INTO `" . $DBTABLEPREFIX . "entries` (entries_ticket_id, entries_date, entries_user_id, entries_text) VALUES('" . $actual_id . "', '" . $datetimestamp . "', '" . $_SESSION['userid'] . "', '" . $message . "')";
    		$result = mysql_query($sql);
		
			$content = ($result) ? "	<span style=\"color: green; font-weight: bold;\">Successfully replied to ticket!</span>" : "	<span style=\"color: red; font-weight: bold;\">Failed to reply to ticket!!!</span>";
			emailUpdateMessage($actual_id, $_SESSION['userid']);
		}
		else { $content = "There is an error on the page, your ticket ID was not found."; }
		
		echo $content;
	}
		
	//================================================
	// Print My Tickets Table
	//================================================
	elseif ($_GET['action'] == "printMyTicketsTable") {
		echo returnMyTicketsTable($_SESSION['userid']);
	}
		
	//================================================
	// Print Tickets Table
	//================================================
	elseif ($_GET['action'] == "printTicketsTable") {
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT 0, 100";
		echo returnTicketsTable($sql);
	}
		
	//================================================
	// Print Ticket Entries Table
	//================================================
	elseif ($_GET['action'] == "printTicketEntries") {
		echo returnTicketEntries($actual_id);
	}
		
	//================================================
	// Echos the indicator to the screen
	//================================================
	elseif ($_GET['action'] == "showIndicator") {		
		echo "	Please Wait...
				<br />
				<img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" />";
	}

	//================================================
	// Checks to see if a username is already in use
	//================================================
	if ($action == 'checkusername') {	
		$sql_username_check = mysql_query("SELECT users_username FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_username='" . $value . "'");
	
		if (mysql_num_rows($sql_username_check) > 0) {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">" . $T_CHECKER_IN_USE . "</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">" . $T_CHECKER_GOOD . "</a>";
		}
	}
	
	//================================================
	// Checks to see if an email address is already in use
	//================================================
	elseif ($action == 'checkemailaddress') {	
		$sql_username_check = mysql_query("SELECT users_email_address FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_email_address='" . $value . "'");
	
		if (mysql_num_rows($sql_username_check) > 0) {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">" . $T_CHECKER_IN_USE . "</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">" . $T_CHECKER_GOOD . "</a>";
		}
	}
	
	else {
		// Do Nothing
	}

?>

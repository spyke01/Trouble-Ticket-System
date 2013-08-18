<? 
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
	include 'includes/header.php';
	
	$actual_id = keepsafe($_GET['id']);
	$actual_action = parseurl($_GET['action']);
	$actual_value = parseurl($_GET['value']);
	$actual_type = parseurl($_GET['type']);
	$actual_showButtons = parseurl($_GET['showButtons']);
	$actual_showClient = parseurl($_GET['showClient']);
	
	// Only logged in users should be able to utilize any of these functions
	if ($_SESSION['user_level'] != BANNED) {
		
		// Delete a row from a DB table
		if ($actual_action == "deleteitem") {
			$table = parseurl($_GET['table']);
			$errorCount = 0;
						
			// Client admins can only modify certain tables
			if ($_SESSION['user_level'] == SYSTEM_ADMIN || ($_SESSION['user_level'] == TICKET_ADMIN && ($table != "config" || $table != "products" || $table != "users"))) {
				// Delete and associated foreign items
				if ($table == "tickets") {
					// Delete Ticket Entries
					$sql = "DELETE FROM `" . DBTABLEPREFIX . "entries` WHERE ticket_id = '" . $actual_id . "'";
					$result = mysql_query($sql);
					$errorCount += ($result) ? 0 : 1;
				}
				
				$table = ($table == "users") ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;			
				
				// Delete actual table row
				$sql = "DELETE FROM `" . $table . "` WHERE id = '" . $actual_id . "'";
				$result = mysql_query($sql);
				$errorCount += ($result) ? 0 : 1;
				
				$success = ($errorCount == 0) ? 1 : 0;
				
				echo $success;
			}
			// Regular users can do the following
			elseif ($_SESSION['user_level'] == USER) {
				// Check that this is for an allowed table
				if ($table == "tickets") {
					// Delete and associated foreign items
					if ($table == "tickets") {
						// Delete Ticket Entries
						$sql = "DELETE FROM `" . DBTABLEPREFIX . "entries` WHERE ticket_id = '" . $actual_id . "'";
						$result = mysql_query($sql);
						$errorCount += ($result) ? 0 : 1;
					}
					
					$table = ($table == "users") ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;
					
					// Delete actual table row
					$sql = "DELETE FROM `" . $table . "` WHERE id = '" . $actual_id . "'";
					$result = mysql_query($sql);
					$errorCount += ($result) ? 0 : 1;
					
					$success = ($errorCount == 0) ? 1 : 0;
					
					echo $success;
				}
			}
		}
				
		//================================================
		// Update our users in the database
		//================================================
		elseif ($actual_action == "createAccount") {
			$datetimestamp = time();
			$first_name = keeptasafe($_GET['first_name']);
			$last_name = keeptasafe($_GET['last_name']);
			$email_address = keeptasafe($_GET['email_address']);
			$username = keeptasafe($_GET['username']);
			$password = keeptasafe($_GET['password']);
			$password2 = keeptasafe($_GET['password2']);
			$language = keeptasafe($_GET['language']);
			
			$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE `username` = '" . $username . "'";
			$username_check = mysql_query($sql);
			
			$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE `email_address` = '" . $email_address . "'";
			$email_check = mysql_query($sql);
			
			if ($username_check && mysql_num_rows($username_check) == 0) {
				if ($email_check && mysql_num_rows($email_check) == 0) {
					if ($password == $password2) {
						$password = md5($password);
										
						$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (`username`, `password`, `email_address`, `first_name`, `last_name`, `language`, `signup_date`) VALUES ('" . $username . "', '" . $password . "', '" . $email_address . "', '" . $first_name . "', '" . $last_name . "', '" . $language . "', '" . $datetimestamp . "')";
						$result = mysql_query($sql);
						$userID = mysql_insert_id();
						
						$content = ($result) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_ACCOUNT'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_ACCOUNT'] . "</span>";
					}
					else {
						$content = "<span class=\"redText bold\">" . $LANG['ERROR_PASSWORDS_DONT_MATCH'] . "</span>";			
					}
				}
				else {
					$content = "<span class=\"redText bold\">" . $LANG['ERROR_EMAIL_ADDRESS_TAKEN'] . "</span>";			
				}
			}
			else {
				$content = "<span class=\"redText bold\">" . $LANG['ERROR_USERNAME_TAKEN'] . "</span>";			
			}
				
			echo $content;
		}
	
		//================================================
		// Create a ticket 
		//================================================
		elseif ($actual_action == "createTicket") {
			$errors = 0;
			$datetimestamp = time();
			$title = keeptasafe($_GET['title']);
			$cat_id = keepsafe($_GET['cat_id']);
			$user_id = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? keepsafe($_GET['user_id']) : $_SESSION['userid'];
			$text = keeptasafe($_GET['text']);
			
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "tickets` (`title`, `cat_id`, `user_id`, `datetimestamp`) VALUES ('" . $title . "', '" . $cat_id . "', '" . $user_id . "', '" . $datetimestamp . "')";
			$result = mysql_query($sql);
			$ticketID = mysql_insert_id();
			if (!$result) $errors++;
			
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "entries` (`ticket_id`, `user_id`, `text`, `datetimestamp`) VALUES ('" . $ticketID . "', '" . $user_id . "', '" . $text . "', '" . $datetimestamp . "')";
			$result = mysql_query($sql);
			if (!$result) $errors++;
			
			$content = ($errors > 0) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_TICKET'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_TICKET'] . "</span>";
			
			switch(keepsafe($_GET['reprinttable'])) {
				case 1:	
					$finalColumnData = ($actual_showButtons == 1) ? createDeleteLinkWithImage($ticketID, $ticketID . "_row", "tickets", "ticket") : "";
				
					$tableHTML = "
						<tr class=\"greenRow\" id=\"" . $ticketID . "_row\">
							<td>" . $ticketID . "</td>
							<td>" . $title . "</td>
							<td>" . getUsernameFromID($user_id) . "</td>
							<td>" . getCatNameByID($cat_id) . "</td>
							<td>Ticket Not Assigned Yet</td>
							<td>" . makeDateTime($datetimestamp) . "</td>
							<td>" . getTicketStatus(0) . "</td>
							<td class=\"center\"><a href=\"" . $menuvar['VIEWTICKET'] . "&id=" . $ticketID . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/check.png\" alt=\"Edit Ticket Details\" /></a> " . $finalColumnData . "</td>
						</tr>";
						
					echo $tableHTML;
					break;
				default:
					echo $content;
					break;
			}
			
			// Send ticket created email
			sendTicketEMail($ticketID, $user_id, 0);
		}
	
		//================================================
		// Create a ticket entry
		//================================================
		elseif ($actual_action == "createTicketEntry") {
			$datetimestamp = time();
			$user_id = $_SESSION['userid'];
			$text = keeptasafe($_GET['text']);
			
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "entries` (`ticket_id`, `user_id`, `text`, `datetimestamp`) VALUES ('" . $actual_id . "', '" . $user_id . "', '" . $text . "', '" . $datetimestamp . "')";
			$result = mysql_query($sql);
			$entryID = mysql_insert_id();
			
			$content = ($result) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_TICKET_ENTRY'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_TICKET_ENTRY'] . "</span>";
			$deleteButton = ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) ? "<br />" . createDeleteLinkWithImage($entryID, $entryID . "_row", "entries", "entry") : "";
				
			switch(keepsafe($_GET['reprinttable'])) {
				case 1:	
					$tableHTML = "
						<tr class=\"row1\" id=\"" . $entryID . "_row\">
							<td class=\"center\">
								<strong>" . getUsernameFromID($user_id) . "</strong><br />
								" . makeShortDateTime($datetimestamp) . $deleteButton . "
							</td>
							<td>" . bbcode($text) . "</td>
						</tr>";
						
					echo $tableHTML;
					break;
				default:
					echo $content;
					break;
			}
			
			// Send ticket reply email
			sendTicketEMail($actual_id, $user_id, 1);
		}
	}
	
	// Only admoins should be able to utilize any of these functions
	if ($_SESSION['user_level'] == SYSTEM_ADMIN || $_SESSION['user_level'] == TICKET_ADMIN) {
		
		//================================================
		// Main updater and get functions
		//================================================
		// Update an item in a DB table
		if ($actual_action == "updateitem") {
			$item = parseurl($_GET['item']);
			$table = parseurl($_GET['table']);
			$updateto = ($table == "notes" && $item == "text") ? preg_replace('/\<br(\s*)?\/?\>/i', "\n", $updateto) : $updateto;
			$updateto = ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") ? strtotime(keeptasafe($_REQUEST['value'])) : keeptasafe($_REQUEST['value']);
			
			// Client admins can only modify certain tables
			if ($_SESSION['user_level'] == SYSTEM_ADMIN || ($_SESSION['user_level'] == TICKET_ADMIN && ($table != "config" || $table != "users"))) {
				$table = ($table == "users") ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;
				
				$sql = "UPDATE `" . $table . "` SET " . $item ." = '" . $updateto . "' WHERE id = '" . $actual_id . "'";
				$result = mysql_query($sql);
				
				if ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") { 
					$result =  (trim($updateto) != "") ? makeDateTime($updateto) : "";
					echo $result;
				}
				elseif ($item == "discount") { 
					echo formatCurrency($updateto);
				}
				elseif ($item == "note") { 
					echo ajaxnl2br($updateto);
				}
				else { echo stripslashes($updateto); }
			}
		}
		
		// Get an item from a DB table
		elseif ($actual_action == "getitem") {
			$item = parseurl($_GET['item']);
			$table = parseurl($_GET['table']);
			
			// Client admins can only modify certain tables
			if ($_SESSION['user_level'] == SYSTEM_ADMIN || ($_SESSION['user_level'] == TICKET_ADMIN && ($table != "config" || $table != "products" || $table != "users"))) {
				$table = ($table == "users") ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;
			
				$sql = "SELECT " . $item ." FROM `" . $table . "` WHERE id = '" . $actual_id . "'";
				$result = mysql_query($sql);
				
				if ($result && mysql_num_rows($result) > 0) {
					while ($row = mysql_fetch_array($result)) {	
						if ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") { 
							$returnVar =  (trim($row[$sqlrow]) != "") ? makeShortDateTime($row[$sqlrow]) : ""; 
							echo $returnVar;
						}
						elseif ($item == "note") { 
							echo $row[$sqlrow];
						}
						else { echo bbcode($row[$sqlrow]); }
					}
					mysql_free_result($result);
				}
			}
		}	
		
		//================================================
		// Update our cats in the database
		//================================================
		elseif ($actual_action == "createCategory") {
			$name = keeptasafe($_GET['catname']);	
			
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "categories` (`name`) VALUES ('" . $name . "')";
			$result = mysql_query($sql);
			$categoryID = mysql_insert_id();
			
			$content = ($result) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_PROBLEM_CATEGORY'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_PROBLEM_CATEGORY'] . "</span>";
			
			switch(keepsafe($_GET['reprinttable'])) {
				case 1:
					$finalColumnData = ($actual_showButtons == 1) ? createDeleteLinkWithImage($categoryID, $categoryID . "_row", "categories", "category") : "";
					
					$tableHTML = "
						<tr class=\"even\" id=\"" . $categoryID . "_row\">
							<td>" . $name . "</td>
							<td class=\"center\">" . $finalColumnData . "</td>
						</tr>";
						
					echo $tableHTML;
					break;
				default:
					echo $content;
					break;
			}
		}
		
		// Only System Admins can utilize these functions
		if ($_SESSION['user_level'] == SYSTEM_ADMIN) {
				
			//================================================
			// Search our tickets table
			//================================================
			if ($actual_action == "searchTickets") {
				echo printTicketsTable($_GET);
			}
				
			//================================================
			// Update our users in the database
			//================================================
			elseif ($actual_action == "createUser") {
				$datetimestamp = time();
				$first_name = keeptasafe($_GET['first_name']);
				$last_name = keeptasafe($_GET['last_name']);
				$email_address = keeptasafe($_GET['email_address']);
				$username = keeptasafe($_GET['username']);
				$password = keeptasafe($_GET['password']);
				$password2 = keeptasafe($_GET['password2']);
				$company = keeptasafe($_GET['company']);
				$website = keeptasafe($_GET['website']);
				$userlevel = keeptasafe($_GET['userlevel']);
				$language = keeptasafe($_GET['language']);
				
				$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE `username` = '" . $username . "'";
				$username_check = mysql_query($sql);
				
				$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE `email_address` = '" . $email_address . "'";
				$email_check = mysql_query($sql);
				
				if ($username_check && mysql_num_rows($username_check) == 0) {
					if ($email_check && mysql_num_rows($email_check) == 0) {
						if ($password == $password2) {
							$password = md5($password);
											
							$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (`username`, `password`, `email_address`, `user_level`, `first_name`, `last_name`, `company`, `website`, `language`, `signup_date`) VALUES ('" . $username . "', '" . $password . "', '" . $email_address . "', '" . $userlevel . "', '" . $first_name . "', '" . $last_name . "', '" . $company . "', '" . $website . "', '" . $language . "', '" . $datetimestamp . "')";
							$result = mysql_query($sql);
							$userID = mysql_insert_id();
							
							$content = ($result) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_USER'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_USER'] . "</span>";
						}
						else {
							$content = "<span class=\"redText bold\">" . $LANG['ERROR_PASSWORDS_DONT_MATCH'] . "</span>";			
						}
					}
					else {
						$content = "<span class=\"redText bold\">" . $LANG['ERROR_EMAIL_ADDRESS_TAKEN'] . "</span>";			
					}
				}
				else {
					$content = "<span class=\"redText bold\">" . $LANG['ERROR_USERNAME_TAKEN'] . "</span>";			
				}
					
				switch(keepsafe($_GET['reprinttable'])) {
					case 1:				
						$finalColumnData = ($actual_showButtons == 1) ? "<a href=\"" . $menuvar['USERS'] . "&amp;action=edituser&amp;id=" . $userID . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/check.png\" alt=\"Edit User Details\" /></a> " . createDeleteLinkWithImage($userID, $userID . "_row", "users", "user") : "";
						
						if ($userID) {						
							$tableHTML = "
								<tr class=\"even\" id=\"" . $userID . "_row\">
									<td>" . $username . "</td>
									<td>" . $email_address . "</td>
									<td>" . $first_name . " " . $last_name . "</td>
									<td>" . makeDate($datetimestamp) . "</td>
									<td>" . getUserlevelFromID($userID) . "</td>
									<td class=\"center\">" . $finalColumnData . "</td>
								</tr>";
						}
						else {						
							$tableHTML = "
								<tr class=\"even\">
									<td colspan=\"6\">" . $content . "</td>
								</tr>";
						}
						echo $tableHTML;
						break;
					default:
						echo $content;
						break;
				}
			}
				
			//================================================
			// Update our users in the database
			//================================================
			elseif ($actual_action == "editUser") {
				$first_name = keeptasafe($_GET['first_name']);
				$last_name = keeptasafe($_GET['last_name']);
				$email_address = keeptasafe($_GET['email_address']);
				$username = keeptasafe($_GET['username']);
				$password = keeptasafe($_GET['password']);
				$password2 = keeptasafe($_GET['password2']);
				$company = keeptasafe($_GET['company']);
				$website = keeptasafe($_GET['website']);
				$userlevel = keeptasafe($_GET['userlevel']);
				$language = keeptasafe($_GET['language']);
				
				if ($password == $password2) {
					$passwordSQL = ($password != "") ? " `password` = '" . md5($password) . "', " : "";
					
					$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET `username` = '" . $username . "'," . $passwordSQL . " `email_address` = '" . $email_address . "', `user_level` = '" . $userlevel . "', `first_name` = '" . $first_name . "', `last_name` = '" . $last_name . "', `website` = '" . $website . "', `language` = '" . $language . "' WHERE `id` = '" . $actual_id . "'";
					$result = mysql_query($sql);
					
					$content = ($result) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_UPDATE_USER'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_UPDATE_USER'] . "</span>";
				}
				else {
					$content = "<span class=\"redText bold\">" . $LANG['ERROR_PASSWORDS_DONT_MATCH'] . "</span>";			
				}
					
				echo $content;
			}
				
			//================================================
			// Search our user table
			//================================================
			elseif ($actual_action == "searchUsers") {
				echo printUsersTable($_GET, "");
			}
		}
	}
?>

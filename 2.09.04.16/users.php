<?php 
/***************************************************************************
 *                               users.php
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
if ($_SESSION['user_level'] == ADMIN) {
	//==================================================
	// Handle editing, adding, and deleting of users
	//==================================================	
	if ($_GET['action'] == "newuser") {
		if (isset($_POST['submit'])) {
			if ($_POST['password'] == $_POST['password2']) {
				$password = md5(keepsafe($_POST['password']));
								
				$sql = "INSERT INTO `" . $USERSDBTABLEPREFIX . "users` (`users_username`, `users_password`, `users_email_address`, `users_tech`, `users_user_level`, `users_full_name`, `users_website`) VALUES ('" . keepsafe($_POST['username']) . "', '" . $password . "', '" . keepsafe($_POST['emailaddress']) . "', '" . keepsafe($_POST['tech']) . "', '" . keepsafe($_POST['userlevel']) . "', '" . keeptasafe($_POST['fullname']) . "', '" . keepsafe($_POST['website']) . "')";
				$result = mysql_query($sql);
				
				if ($result) {
					$content = "<div class=\"center\">" . $T_ADD_USER_SUCCESS . "</div>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['USERS'] . "\">";
				}
				else {
					$content = "<div class=\"center\">" . $T_ADD_USER_ERROR . "</div>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";						
				}
			}
			else {
				$content = "<div class=\"center\">" . $T_PASSWORDS_DONT_MATCH . "</div>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";			
			}
		}
		else {
			$content .= "
						<form name=\"newuserform\" action=\"" . $menuvar['USERS'] . "&amp;action=newuser\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">" . $T_ADD_USER . "</td>
								</tr>
								<tr class=\"row2\">
									<td><strong>" . $T_NAME . ":</strong></td><td><input name=\"fullname\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>" . $T_USERNAME . ":</strong></td><td><input name=\"username\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>" . $T_PASSWORD . ":</strong></td><td><input name=\"password\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>" . $T_CONFIRM_PASSWORD . ":</strong></td><td><input name=\"password2\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>" . $T_EMAIL_ADDRESS . ":</strong></td><td><input name=\"emailaddress\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>" . $T_WEBSITE . ":</strong></td><td><input name=\"website\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>" . $T_TECHNICIAN . ":</strong></td><td><input name=\"tech\" type=\"checkbox\" value=\"1\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>" . $T_USER_LEVEL . ":</strong></td><td>
										" . createDropdown("userlevel", "userlevel", "", "", "settingsDropDown") . "
									</td>
								</tr>
							</table>									
							<br />
							<div class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"" . $T_ADD_USER . "\" /></div>
						</form>";
		}
	}	
	elseif ($_GET['action'] == "edituser" && isset($_GET['id'])) {
		if (isset($_POST['submit'])) {
			if ($_POST['password'] != "") {
				if ($_POST['password'] == $_POST['password2']) {
					$password = md5(keepsafe($_POST['password']));								

					$sql = "UPDATE `" . $USERSDBTABLEPREFIX . "users` SET users_username = '" . keepsafe($_POST['username']) . "', users_password = '" . $password . "', users_email_address = '" . keepsafe($_POST['emailaddress']) . "', users_tech = '" . keepsafe($_POST['tech']) . "', users_user_level = '" . keepsafe($_POST['userlevel']) . "', users_full_name = '" . keeptasafe($_POST['fullname']) . "', users_website = '" . keepsafe($_POST['website']) . "' WHERE users_id = '" . keepsafe($_GET['id']) . "'";
				}
				else {
					$content = "<div class=\"center\">" . $T_PASSWORDS_DONT_MATCH . "</div>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";			
				}
			}
			else {
					$sql = "UPDATE `" . $USERSDBTABLEPREFIX . "users` SET users_username = '" . keepsafe($_POST['username']) . "', users_email_address = '" . keepsafe($_POST['emailaddress']) . "', users_tech = '" . keepsafe($_POST['tech']) . "', users_user_level = '" . keepsafe($_POST['userlevel']) . "', users_full_name = '" . keeptasafe($_POST['fullname']) . "', users_website = '" . keepsafe($_POST['website']) . "' WHERE users_id = '" . keepsafe($_GET['id']) . "'";
			}
			$result = mysql_query($sql);
			
			if ($result) {
				$content = "<div class=\"center\">" . $T_EDIT_USER_SUCCESS . "</div>
							<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['USERS'] . "\">";
			}
			else {
				$content = "<div class=\"center\">" . $T_UPDATE_ERROR . "</div>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id = '" . $_GET[id] . "' LIMIT 1";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == 0) {
				$content = "<div class=\"center\">" . $T_EDIT_USER_ERROR . "</div>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				$content .= "
							<form name=\"newpageform\" action=\"" . $menuvar['USERS'] . "&amp;action=edituser&amp;id=" . $row['users_id'] . "\" method=\"post\">
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\" colspan=\"2\">" . $T_EDIT_USER . "</td>
									</tr>
									<tr class=\"row2\">
										<td><strong>" . $T_NAME . ":</strong></td><td><input name=\"fullname\" type=\"text\" size=\"60\" value=\"" . $row['users_full_name'] . "\" /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>" . $T_USERNAME . ":</strong></td><td><input name=\"username\" type=\"text\" size=\"60\" value=\"" . $row['users_username'] . "\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>" . $T_PASSWORD . ":</strong></td><td><input name=\"password\" type=\"password\" size=\"60\" /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>" . $T_CONFIRM_PASSWORD . ":</strong></td><td><input name=\"password2\" type=\"password\" size=\"60\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>" . $T_EMAIL_ADDRESS . ":</strong></td><td><input name=\"emailaddress\" type=\"text\" size=\"60\" value=\"" . $row['users_email_address'] . "\" /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>" . $T_WEBSITE . ":</strong></td><td><input name=\"website\" type=\"text\" size=\"60\" value=\"" . $row['users_website'] . "\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>" . $T_TECHNICIAN . ":</strong></td><td><input name=\"tech\" type=\"checkbox\" value=\"1\"" . testChecked($row['users_tech'], 1) . " /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>" . $T_USER_LEVEL . ":</strong></td><td>
											" . createDropdown("userlevel", "userlevel", $row['users_user_level'], "", "settingsDropDown") . "
										</td>
									</tr>
								</table>									
								<br />
								<div class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"" . $T_UPDATE . "\" /></div>
							</form>";							
			}			
		}
	}
	else {
		if ($_GET['action'] == "deleteuser") {
			$sql = "DELETE FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id='" . $_GET['id'] . "' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		//==================================================
		// Print out our users table
		//==================================================
		$sql = "SELECT * FROM `" . $USERSDBTABLEPREFIX . "users` ORDER BY users_username ASC";
		$result = mysql_query($sql);
		
		$x = 1; //reset the variable we use for our row colors	
		
		$content = "<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"4\">
									<div style=\"float: right;\"><a href=\"" . $menuvar['USERS'] . "&amp;action=newuser\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/add.png\" alt=\"" . $T_ADD_USER . "\" /></a></div>
									" . $T_CURRENT_USERS . "
								</td>
							</tr>
							<tr class=\"title2\">
								<td><strong>" . $T_USERNAME . "</strong></td><td><strong>" . $T_NAME . "</strong></td><td><strong>" . $T_USER_LEVEL . "</strong></td><td></td>
							</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			$level = ($row['users_user_level'] == ADMIN) ? "Administrator" : "Moderator";
			$level = ($row['users_user_level'] == USER) ? "User" : $level;
			$level = ($row['users_user_level'] == BANNED) ? "Banned" : $level;
			
			$content .=			"<tr class=\"row" . $x . "\">
									<td>" . $row['users_username'] . "</td>
									<td>" . $row['users_full_name'] . "</td>
									<td>" . $level . "</td>
									<td>
										<div class=\"center\"><a href=\"" . $menuvar['USERS'] . "&amp;action=edituser&amp;id=" . $row['users_id'] . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/check.png\" alt=\"" . $T_EDIT_USER . "\" /></a> <a href=\"" . $menuvar['USERS'] . "&amp;action=deleteuser&amp;id=" . $row['users_id'] . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/delete.png\" alt=\"" . $T_DELETE_USER . "\" /></a></div>
									</td>
								</tr>";
			$x = ($x==2) ? 1 : 2;
		}
		mysql_free_result($result);
		
	
		$content .=		"</table>";
	}
	$page->setTemplateVar("PageContent", $content);
}
else {
	$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>
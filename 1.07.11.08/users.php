<? 
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
include 'includes/header.php';
if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD) {
	//==================================================
	// Handle editing, adding, and deleting of pages
	//==================================================	
	if ($_GET[action] == "newuser") {
		if (isset($_POST['submit'])) {
			if ($_POST['password'] == $_POST['password2']) {
				$password = md5($_POST['password']);
								
				$sql = "INSERT INTO `" . $DBTABLEPREFIX . "users` (`users_username`, `users_password`, `users_email_address`, `users_tech`, `users_user_level`) VALUES ('$_POST[username]', '$password', '$_POST[emailaddress]', '$_POST[tech]', '$_POST[userlevel]')";
				$result = mysql_query($sql);
				
				if ($result) {
					echo "\n<center>$T_ADD_USER_SUCCESS</center>";
					echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[USERS]'>";
				}
				else {
					echo "\n<center>$T_ADD_USER_ERROR</center>";
					echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";						
				}
			}
			else {
				echo "\n<center>$T_PASSWORDS_DONT_MATCH</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";			
			}
		}
		else {
			echo "\n<form name=\"newuserform\" action=\"$menuvar[USERS]?action=newuser\" method=\"post\">
						<table border='0' cellpadding='0' cellspacing='0' class='data-table'>
							<tr>
								<td class=\"datatable-header1\" colspan=\"2\">$T_ADD_USER</td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_USERNAME:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"username\" type=\"text\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_PASSWORD:</strong></td><td width=\"80%\" class=\"datatable-row2\"><input name=\"password\" type=\"password\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_CONFIRM_PASSWORD:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"password2\" type=\"password\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_EMAIL_ADDRESS:</strong></td><td width=\"80%\" class=\"datatable-row2\"><input name=\"emailaddress\" type=\"text\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_TECHNICIAN:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"tech\" type=\"checkbox\" value=\"1\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_USER_LEVEL:</strong></td><td width=\"80%\" class=\"datatable-row2\">
									<select name=\"userlevel\">
										<option value=\"" . BANNED . "\">Banned</option>
										<option value=\"" . USER . "\">User</option>
										<option value=\"" . MOD . "\">Moderator</option>
										<option value=\"" . ADMIN . "\">Administrator</option>
									</select>
								</td>
							</tr>
						</table>									
						<br />
						<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_ADD_USER_BUTTON\" /></center>
					</form>";
		}
	}	
	elseif ($_GET[action] == "edituser" && isset($_GET[id])) {
		if (isset($_POST[submit])) {
			if ($_POST[password] != "") {
				if ($_POST[password] == $_POST[password2]) {
					$password = md5($_POST[password]);								

					$sql = "UPDATE `" . $DBTABLEPREFIX . "users` SET users_username = '$_POST[username]', users_password = '$password', users_email_address = '$_POST[emailaddress]', users_tech = '$_POST[tech]', users_user_level = '$_POST[userlevel]' WHERE users_user_id = '$_GET[id]'";
				}
				else {
					echo "\n<center>$T_PASSWORDS_DONT_MATCH</center>";
					echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";			
				}
			}
			else {
				$sql = "UPDATE `" . $DBTABLEPREFIX . "users` SET users_username = '$_POST[username]', users_email_address = '$_POST[emailaddress]', users_tech = '$_POST[tech]', users_user_level = '$_POST[userlevel]' WHERE users_user_id = '$_GET[id]'";
			}
			$result = mysql_query($sql);
			
			if ($result) {
				echo "\n<center>$T_EDIT_USER_SUCCESS</center>";
				echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[USERS]'>";
			}
			else {
				echo "\n<center>$T_EDIT_USER_ERROR</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_user_id = '$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == 0) {
				echo "\n<center>$T_EDIT_USER_ERROR</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				echo "\n<form name=\"newpageform\" action=\"$menuvar[USERS]?action=edituser&amp;id=$row[users_user_id]\" method=\"post\">
					<table border='0' cellpadding='0' cellspacing='0' class='data-table'>
							<tr>
								<td class=\"datatable-header1\" colspan=\"2\">$T_EDIT_USER</td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_USERNAME:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"username\" type=\"text\" size=\"60\" value=\"$row[users_username]\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_PASSWORD:</strong></td><td width=\"80%\" class=\"datatable-row2\"><input name=\"password\" type=\"password\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_CONFIRM_PASSWORD:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"password2\" type=\"password\" size=\"60\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_EMAIL_ADDRESS:</strong></td><td width=\"80%\" class=\"datatable-row2\"><input name=\"emailaddress\" type=\"text\" size=\"60\" value=\"$row[users_email_address]\" /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row1\"><strong>$T_TECHNICIAN:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"tech\" type=\"checkbox\" value=\"1\"" . ischecked($row[users_user_level], "1") . " /></td>
							</tr>
							<tr>
								<td width=\"20%\" class=\"datatable-row2\"><strong>$T_USER_LEVEL:</strong></td><td width=\"80%\" class=\"datatable-row2\">
									<select name=\"userlevel\">
										<option value=\"" . BANNED . "\"" . isselected($row[users_user_level], BANNED) . ">Banned</option>
										<option value=\"" . USER . "\"" . isselected($row[users_user_level], USER) . ">User</option>
										<option value=\"" . MOD . "\"" . isselected($row[users_user_level], MOD) . ">Moderator</option>
										<option value=\"" . ADMIN . "\"" . isselected($row[users_user_level], ADMIN) . ">Administrator</option>
									</select>
								</td>
							</tr>
						</table>									
						<br />
						<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_EDIT_USER_BUTTON\" /></center>
					</form>";							
			}			
		}
	}
	else {
		if ($_GET[action] == "deleteuser") {
			$sql = "DELETE FROM `" . $DBTABLEPREFIX . "users` WHERE users_user_id='$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		//==================================================
		// Print out our users table
		//==================================================
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` ORDER BY users_username ASC";
		$result = mysql_query($sql);
		
		$x = '1'; //reset the variable we use for our row colors	
		
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-header1\" colspan=\"3\">";
		echo "\n			<div style='float: right;'><a href=\"$menuvar[USERS]?action=newuser\"><img src='images/plus.png' alt='$T_ADD_USER' /></a></div>";
		echo "\n				$T_CURRENT_USERS";
		echo "\n		</td>";
		echo "\n	</tr>";
		echo "\n	<tr class=\"datatable-header2\">";
		echo "\n		<td class=\"datatable-header2\"><strong>$T_USERNAME</strong></td><td class=\"datatable-header2\"><strong>$T_USER_LEVEL</strong></td><td class=\"datatable-header2\">&nbsp;</td>";
		echo "\n	</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			$level = ($row[users_user_level] == ADMIN) ? "Administrator" : $row[users_user_level];
			$level = ($level == MOD && is_numeric($level)) ? "Moderatorator" : $level;
			$level = ($level == BANNED && is_numeric($level)) ? "Banned" : $level;
			$level = ($level == USER && is_numeric($level)) ? "User" : $level;
			
			echo "\n	<tr>";
			echo "\n		<td width=\"40%\" class=\"datatable-row" . $x . "\">$row[users_username]</td>";
			echo "\n		<td width=\"40%\" class=\"datatable-row" . $x . "\">$level</td>";
			echo "\n		<td width=\"20%\" class=\"datatable-row" . $x . "\">";
			echo "\n			<center><a href=\"$menuvar[USERS]?action=edituser&amp;id=$row[users_user_id]\"><img src='images/check.png' alt='$T_EDIT_USER' /></a> <a href=\"$menuvar[USERS]?action=deleteuser&amp;id=$row[users_user_id]\"><img src=\"images/x.png\" alt='$T_DELETE_USER' /></a></center>";
			echo "\n		</td>";
			echo "\n	</tr>";
			$x = ($x==2) ? 1 : 2;
		}
		mysql_free_result($result);
		
		echo "\n</table>";
	}
}
else {
	echo $T_NOT_AUTHORIZED;
}

include('includes/footer.php');
?>
<? 
/***************************************************************************
 *                               config.php
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
	// Handle editing, adding, and deleting of pcats
	//==================================================	
	if ($_GET[action] == "newpcat") {
		if (isset($_POST[submit])) {
			$sql = "INSERT INTO `" . $DBTABLEPREFIX . "problemcategories` (`pcats_name`) VALUES ('$_POST[pcatname]')";
			$result = mysql_query($sql);
			
			if ($result) {
				echo "\n<center>$T_ADD_PCAT_SUCCESS</center>";
				echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[CONFIG]'>";
			}
			else {
				echo "\n<center>$T_ADD_PCAT_ERROR</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[CONFIG]'>";						
			}
		}
		else {
			echo "\n<form name=\"newpcatform\" action=\"$menuvar[CONFIG]?action=newpcat\" method=\"post\">";
			echo "\n	<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
			echo "\n		<tr>";
			echo "\n			<td class=\"datatable-header1\" colspan=\"2\">$T_ADD_USER</td>";
			echo "\n		</tr>";
			echo "\n		<tr>";
			echo "\n			<td width=\"20%\" class=\"datatable-row1\"><strong>$T_PROBLEM_CATEGORY_NAME:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"pcatname\" type=\"text\" size=\"60\" /></td>";
			echo "\n		</tr>";
			echo "\n	</table>";									
			echo "\n	<br />";
			echo "\n	<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_ADD_PCAT_BUTTON\" /></center>";
			echo "\n</form>";
		}
	}	
	elseif ($_GET[action] == "editpcat" && isset($_GET[id])) {
		if (isset($_POST[submit])) {
			$sql = "UPDATE `" . $DBTABLEPREFIX . "problemcategories` SET pcats_name = '$_POST[pcatname]' WHERE pcats_id = '$_GET[id]'";
			$result = mysql_query($sql);
			
			if ($result) {
				echo "\n<center>$T_EDIT_PCAT_SUCCESS</center>";
				echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[CONFIG]'>";
			}
			else {
				echo "\n<center>$T_EDIT_PCAT_ERROR</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[CONFIG]'>";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "problemcategories` WHERE pcats_id = '$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == 0) {
				echo "\n<center>$T_EDIT_PCAT_ERROR</center>";
				echo "\n<meta http-equiv='refresh' content='5;url=$menuvar[CONFIG]'>";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				echo "\n<form name=\"newpageform\" action=\"$menuvar[CONFIG]?action=editpcat&amp;id=$row[pcats_id]\" method=\"post\">";
				echo "\n	<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
				echo "\n		<tr>";
				echo "\n			<td class=\"datatable-header1\" colspan=\"2\">$T_EDIT_USER</td>";
				echo "\n		</tr>";
				echo "\n		<tr>";
				echo "\n			<td width=\"20%\" class=\"datatable-row1\"><strong>$T_PROBLEM_CATEGORY_NAME:</strong></td><td width=\"80%\" class=\"datatable-row1\"><input name=\"pcatname\" type=\"text\" size=\"60\" value=\"$row[pcats_name]\" /></td>";
				echo "\n		</tr>";
				echo "\n	</table>";								
				echo "\n	<br />";
				echo "\n	<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_EDIT_PCAT_BUTTON\" /></center>";
				echo "\n</form>";							
			}			
		}
	}
	else {
		if ($_GET[action] == "deletepcat") {
			$sql = "DELETE FROM `" . $DBTABLEPREFIX . "problemcategories` WHERE pcats_id='$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
		}	
		if ($_GET[action] == "changelanguage") {
			$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[defaultlanguage]' WHERE config_name='language' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		$sql = "SELECT config_value FROM `" . $DBTABLEPREFIX . "config` WHERE config_name = 'language' LIMIT 1";
		$result = mysql_query($sql);
		
		if (mysql_num_rows($result) != 0) {
			$row = mysql_fetch_array($result);	
				
			//==================================================
			// Print out our default language table
			//==================================================
			echo "\n<form action=\"$menuvar[CONFIG]?action=changelanguage\" method=\"post\">";
			echo "\n	<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
			echo "\n		<tr>";
			echo "\n			<td class=\"datatable-header1\" colspan=\"2\">$T_TTS_SETTINGS</td>";
			echo "\n		</tr>";
			echo "\n		<tr>";
			echo "\n			<td class=\"datatable-row2\"><strong>$T_TTS_DEFAULT_LANGUAGE</strong></td><td class=\"datatable-row2\">";
			echo "\n				<select name=\"defaultlanguage\">";
			
			ksort($LANGUAGES);
			
			foreach ($LANGUAGES as $abbrev => $long) {
				echo "\n					<option value=\"$abbrev\"" . isselected($row[config_value], $abbrev) . ">$long</option>";
			}
			
			echo "\n				</select>";
			echo "\n			</td>";
			echo "\n		</tr>";
			echo "\n	</table>";
			echo "\n	<br />";
			echo "\n	<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_CHANGE_LANGUAGE\" /></center>";
			echo "\n</form>";
		}

		//==================================================
		// Print out our pcats table
		//==================================================
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "problemcategories` ORDER BY pcats_name ASC";
		$result = mysql_query($sql);
		
		$x = '1'; //reset the variable we use for our row colors			
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-header1\" colspan=\"3\">";
		echo "\n			<div style='float: right;'><a href=\"$menuvar[CONFIG]?action=newpcat\"><img src='images/plus.png' alt='$T_ADD_USER' /></a></div>";
		echo "\n				$T_PROBLEM_CATEGORIES";
		echo "\n		</td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-header2\"><strong>$T_PROBLEM_CATEGORY_NAME</strong></td><td class=\"datatable-header2\">&nbsp;</td>";
		echo "\n	</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			echo "\n	<tr>";
			echo "\n		<td width=\"80%\" class=\"datatable-row" . $x . "\">$row[pcats_name]</td>";
			echo "\n		<td width=\"20%\" class=\"datatable-row" . $x . "\">";
			echo "\n			<center><a href=\"$menuvar[CONFIG]?action=editpcat&amp;id=$row[pcats_id]\"><img src='images/check.png' alt='$T_EDIT_USER' /></a> <a href=\"$menuvar[CONFIG]?action=deletepcat&amp;id=$row[pcats_id]\"><img src=\"images/x.png\" alt='$T_DELETE_USER' /></a></center>";
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
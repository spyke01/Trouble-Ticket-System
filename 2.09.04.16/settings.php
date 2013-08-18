<?php 
/***************************************************************************
 *                               settings.php
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
		if (isset($_POST['submit'])) {		
			foreach($_POST as $name => $value) {
				if ($name != 'submit'){			
					if ($name == "ftstss_active") {
						if ($value == "") { $value = 0; }
						else { $value = 1; }	
					}
					$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value = '$value' WHERE config_name = '$name'";
					
					$result = mysql_query($sql);
				}
			}		
			unset($_POST['submit']);
		}
		
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "config`";
		$result = mysql_query($sql);
		
		// This is used to let us get the actual items and not just config_name and config_value
		while ($row = mysql_fetch_array($result)) {
			$current_config[$row['config_name']] = $row['config_value'];
		}	
		//extract($current_config);
			
		//==================================================
		// Print out our config table
		//==================================================
		$content = "<form action=\"$menuvar[SETTINGS]\" method=\"post\" target=\"_top\">
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr><td class=\"title1\" colspan=\"2\">$T_TTS_SETTINGS</td></tr>
							<tr class=\"row1\">
								<td width=\"32%\"><strong>$T_ACTIVE: </strong></td>
								<td width=\"68%\">
									<select name=\"ftstts_active\">
										<option value=\"". ACTIVE . "\"" . testSelected($current_config['ftstts_active'], ACTIVE) . ">Active</option>
										<option value=\"". INACTIVE . "\"" . testSelected($current_config['ftstts_active'], INACTIVE) . ">Inactive</option>
									</select>
								</td>
							</tr>
							<tr class=\"row2\">
								<td><strong>$T_INACTIVE_MSG:</strong></td>
								<td>
									<textarea name=\"ftstts_inactive_msg\" cols=\"45\" rows=\"10\">" . $current_config['ftstts_inactive_msg'] . "</textarea>
								</td>
							</tr>
							<tr class=\"row1\">
								<td><strong>$T_COOKIE_NAME: </strong></td>
								<td>
									<input name=\"ftstts_cookie_name\" type=\"text\" size=\"60\" value=\"" . $current_config['ftstts_cookie_name'] . "\" />
								</td>
							</tr>
							<tr class=\"row2\">
								<td><strong>$T_TTS_DEFAULT_LANGUAGE: </strong></td>
								<td>
									<select name=\"ftstts_language\">";
		foreach ($LANGUAGES as $abbrev => $long) {
			$content .= "\n								<option value=\"" . $abbrev . "\"" . testSelected($current_config['ftstts_language'], $abbrev) . ">" . $long . "</option>";
		}
		$content .= "								
									</select>
								</td>
							</tr>
							<tr class=\"row1\">
								<td><strong>$T_ADMIN_EMAIL: </strong></td>
								<td>
									<input name=\"ftstts_admin_email\" type=\"text\" size=\"60\" value=\"" . $current_config['ftstts_admin_email'] . "\" />
								</td>
							</tr>
						</table>
						<br />
						<div class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"$T_SETTINGS_UPDATE_BUTTON\" /></div>
					</form>";

		//==================================================
		// Print out our pcats table
		//==================================================
		$content .= "<br /><br />
					<div id=\"updateMe\">" . returnPCatsTable() . "</div>";
	
	$page->setTemplateVar('PageContent', $content);
}
else {
	$page->setTemplateVar('PageContent', "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>
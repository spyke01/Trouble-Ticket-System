<?php 
/***************************************************************************
 *                               themes.php
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

	//==================================================
	// Handle editing, adding, and deleting of pages
	//==================================================	
	if (isset($_POST['submit'])) {
		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value ='" . $_POST['theme'] . "' WHERE config_name ='ftstts_theme' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result) {
			$content = $T_CHANGE_THEME_SUCCESS . "
						<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['THEMES'] . "\">";	
		}
		else {
			$content = $T_CHANGE_THEME_ERROR . "
						<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['THEMES'] . "\">";	
		}		
	}		
	else {
		
		$x = 1; //reset the variable we use for our row colors	
		
		//==================================================
		// Get and store our available themes
		//==================================================		
		$stylepath = "themes";
		if($dir = opendir($stylepath)){					
			$sub_dir_names = array();
			while (false !== ($file = readdir($dir))) {				
				if ($file != "." && $file != ".." && $file != "installer" && is_dir($stylepath . '/' . $file)) {
					$sub_dir_names[$file] .= '';	
				}
			}			
		}		
		ksort($sub_dir_names); //sort by name			
			
		//==================================================
		// Print our table
		//==================================================
		$content = "<form name=\"themechanger\" action=\"" . $menuvar['THEMES'] . "\" method=\"post\">
						<table class=\"contentBox\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"4\">" . $T_THEMES . "</td>
							</tr>							
							<tr class=\"title2\">
								<td><strong>" . $T_PREVIEW . "</strong></td><td><strong>" . $T_NAME . "</strong></td><td><strong>" . $T_AUTHOR . "</strong></td><td><strong>" . $T_ACTIVE . "</strong></td>
							</tr>";			
			
		foreach($sub_dir_names as $name => $nothing) { 			
			$preview = (is_file($stylepath . '/' . $name . '/preview.jpg')) ? $stylepath . "/" . $name . "/preview.jpg" : "images/nopreview.jpg"; // Thanks Joe!		
			$THEME_NAME = "N/A"; // Reset variable
			$THEME_AUTHOR = "N/A"; // Reset variable
			
			if (file_exists($stylepath . '/' . $name . '/themedetails.php')) { include ($stylepath . '/' . $name . '/themedetails.php'); }	
			
			$content .=			"<tr class=\"row" . $x . "\">
									<td width=\"20%\"><center><img src=\"" . $preview . "\" alt=\"" . $T_PREVIEW . "\" /></center></td>
									<td width=\"40%\">" . $THEME_NAME . "</td>
									<td width=\"30%\">" . $THEME_AUTHOR . "</td>
									<td width=\"10%\"><center><input name=\"theme\" type=\"radio\" value=\"$name\"" . testChecked($name, $tts_config['ftstts_theme']) . " /></center></td>
								</tr>";
								
			$x = ($x==2) ? 1 : 2;					
		}		
		$content .=	"	</table>
					<br />
					<center><input name=\"submit\" class=\"button\" type=\"submit\" value=\"" . $T_THEMES_UPDATE_BUTTON . "\" /></center>
				</form>";
	}
	$page->setTemplateVar('PageContent', $content);
?>
<?php
/***************************************************************************
 *                               functions.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
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
if ( !defined('IN_TTS') )
{
	die("You are attempting to access, or hack parts of my website, that you do not have permission to, please do not try this again.");
}

	
//==================================================
// Strips Dangerous tags out of input boxes 
//==================================================
function keepsafe($makesafe) {
	$makesafe=strip_tags($makesafe); // strip away any dangerous tags
	$makesafe=str_replace(" ","",$makesafe); // remove spaces from variables
	$makesafe=str_replace("%20","",$makesafe); // remove escaped spaces
	$makesafe=htmlspecialchars(trim($makesafe)); // remove html characters

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $makesafe = stripslashes($makesafe);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        $makesafe = mysql_real_escape_string($makesafe);
    }
    return $makesafe;
}

//==================================================
// Strips Dangerous tags out of textareas 
//==================================================
function keeptasafe($makesafe) {
	$makesafe=str_replace("%20","",$makesafe); // remove escaped spaces
	//$makesafe=addslashes($makesafe); // add slashes to stop hacking
	$makesafe=htmlspecialchars(trim($makesafe)); // remove html characters
	
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $makesafe = stripslashes($makesafe);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        $makesafe = mysql_real_escape_string($makesafe);
    }
    return $makesafe;
}

//==================================================
// Creates a date from a timestamp
//==================================================
function makeDate($time) {
	$date = @gmdate('l M d, Y h:i a', $time + (3600 * '-7.00')); // Makes date in the format of: Thursday July 5, 2006 3:30 pm
	return $date;
}

//==================================================
// Strips Dangerous tags out of get and post values
//==================================================
function parseurl($str) {
	$str=strip_tags($str); // strip away any dangerous tags
	$str=str_replace(" ","",$str); // remove spaces from variables
	$str=str_replace("%20","",$str); // remove escaped spaces
	$str=htmlspecialchars(trim($str)); // remove html characters

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        $str = mysql_real_escape_string($str);
    }
    return $str;
}

//===================================================
// This function is designed to get the name 
// of a problem category based on the id passed.
//
// USAGE:
// $catName = getProblemCatName(catID);
//
// This will return the cat's name from the DB.
//===================================================
function getProblemCatName($catID) {		
	global $DBTABLEPREFIX;
						
	$sql = "SELECT pcats_name FROM `" . $DBTABLEPREFIX . "problemcategories` WHERE pcats_id='$catID' ";
	$result = mysql_query($sql);
		
	if($result && mysql_num_rows($result) > 0) {
   		$row = mysql_fetch_array($result);
   		return $row['pcats_name'];
   	}
   	else {
   		return "N/A"; 
   	}
   	mysql_free_result($result);
}

//===================================================
// This function is designed to get the language 
// for the current user.
//
// USAGE:
// $language = get_language(username);
//
// This will return the user's language from the DB.
//===================================================
function get_language($currentuser) {
	function check_cookie() {
		$languagecookie = "trouble_ticket_system_language";
		if (isset($_COOKIE[$languagecookie])) {
			$language = $_COOKIE[$languagecookie];
						
			return $language;
		}
		else {
			return FALSE;
		}
	}
	
	
	global $tts_config, $DBTABLEPREFIX;
	
	if (isset($currentuser) && $currentuser != " ") {
						
		$sql = "SELECT users_language FROM `" . $DBTABLEPREFIX . "users` WHERE users_username='$currentuser' ";
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result) > 0) {
   			$row = mysql_fetch_array($result);
   			return "$row[users_language]";
   			
   		}
   		else {
   			$goodcookie = check_cookie();
   			$language = ($goodcookie == FALSE) ? $tts_config[language] : $goodcookie;
   			return $language; 
   		}
   		mysql_free_result($result);
	}
	else {
   		$goodcookie = check_cookie();
   		$language = ($goodcookie == FALSE) ? $tts_config[language] : $goodcookie;
   		return $language; 
	}
}

//===================================================
// This function creates a dropdown box with all 
// usuable langes, and selects the current one.
//
// USAGE:
// make_language_dropdown(en);
//
// This will echo out the box.
//===================================================
function make_language_dropdown($selection) {
	global $menuvar, $LANGUAGES;
	
	echo "\n						<form name=\"languagechange\" method=\"get\" action=\"$menuvar[SWITCHER]\">";
	echo "\n							<select name=\"languagechanger\" onchange=\"languagechange.submit();\">";
	
	ksort($LANGUAGES);
	
	foreach ($LANGUAGES as $abbrev => $long) {
		echo "\n								<option value=\"$abbrev\"" . isselected($selection, $abbrev) . ">$long</option>";
	}
	
	echo "\n							</select>";
	echo "\n						</form>";
}

//===================================================
// This function checks wether an item should be 
// selected or not.
//
// USAGE:
// isselected(1, 2);
//===================================================
function isselected($current, $testing) {
	$selected = ($current == $testing) ? " selected=\"selected\"" : "";
		
	return $selected;
}

//===================================================
// This function checks wether an item should be 
// checked or not.
//
// USAGE:
// ischecked(1, 2);
//===================================================
function ischecked($current, $testing) {
	$selected = ($current == $testing) ? " checked" : "";
		
	return $selected;
}

//==================================================
// This function will notify user of updates and
// other important information
//
// USAGE:
// version_functions();
// 
// Removal or hinderance is a direct violation of 
// the program license and is constituted as a 
// breach of contract as is punishable by law.
//
	// MODIFIED TO REMOVE CALLHOME AND VERSION CHECK
	//==================================================
	function version_functions($print_update_info) {
		include('_license.php');
		
		//=========================================================
		// Get all of the variables we need to pass to the 
		// call home script ready
		//=========================================================
		
			
		//=========================================================
		// Should we display advanced option?
		// Connection to the FTS server has to be made or the 
		// options will not be shown
		//=========================================================
		if ($print_update_info == "advancedOptions" || $print_update_info == "advancedOptionsText") {
			return true;
		}
			
		//=========================================================
		// Should we print out wether or not to update?
		//=========================================================
		if ($print_update_info == "yes") {
			//return "<div class=\"errorMessage\">Version check connection failed.</div>";
		}
	}

?>
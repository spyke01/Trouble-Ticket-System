<?PHP 
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

//==================================================
// Strips Dangerous tags out of input boxes 
//==================================================
function keepsafe($makesafe) {
	$makesafe=strip_tags($makesafe); // strip away any dangerous tags
	$makesafe=str_replace(" ","",$makesafe); // remove spaces from variables
	$makesafe=str_replace("%20","",$makesafe); // remove escaped spaces
	$makesafe = trim(preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', '"&#".ord($0).";"', $makesafe)); //encodes all ascii items above #127

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $makesafe = stripslashes($makesafe);
    }
    // Quote if not integer
    if (!is_numeric($makesafe)) {
        $makesafe = mysql_real_escape_string($makesafe);
    }
    return $makesafe;
}

//==================================================
// Strips Dangerous tags out of textareas 
//==================================================
function keeptasafe($makesafe) {
	$makesafe = trim(preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', '"&#".ord($0).";"', $makesafe)); //encodes all ascii items above #127
	
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $makesafe = stripslashes($makesafe);
    }
    // Quote if not integer
    if (!is_numeric($makesafe)) {
        $makesafe = mysql_real_escape_string($makesafe);
    }
    return $makesafe;
}

//==================================================
// Strips Dangerous tags out of get and post values
//==================================================
function parseurl($makesafe) {
	$makesafe=strip_tags($makesafe); // strip away any dangerous tags
	$makesafe=str_replace(" ","",$makesafe); // remove spaces from variables
	$makesafe=str_replace("%20","",$makesafe); // remove escaped spaces
	$makesafe = trim(preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', '"&#".ord($0).";"', $makesafe)); //encodes all ascii items above #127

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $makesafe = stripslashes($makesafe);
    }
    // Quote if not integer
    if (!is_numeric($makesafe)) {
        $makesafe = mysql_real_escape_string($makesafe);
    }
    return $makesafe;
}

//==================================================
// Creates a date from a timestamp
//==================================================
function makeDate($time) {
	$date = @gmdate('M d, Y', $time + (3600 * '-5.00')); // Makes date in the format of: Thursday July 5, 2006
	return $date;
}

function makeTime($time) {
	$date = @gmdate('g:i A', $time + (3600 * '-5.00')); // Makes date in the format of: 3:30 PM
	return $date;
}

function makeDateTime($time) {
	$date = @gmdate('M d, Y - g:i A', $time + (3600 * '-5.00')); // Makes date in the format of: Thursday July 5, 2006 - 3:30 pm
	return $date;
}

//==================================================
// Replacement for die()
// Used to display msgs without displaying the board
//==================================================
function message_die($msg_text = '', $msg_title = '') {
	echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
	include('includes/footer.php');
	exit;
}

//=================================================
// BBCode Functions Generated from: 
// http://bbcode.strefaphp.net/bbcode.php
// A gigantic thanks goes out to the 
// programmers there!!
// 
// Use the function like so: echo bbcode($string);
//=================================================
Function bbcode($str){
	// Makes < and > page friendly
	//$str=str_replace("&","&amp;",$str);
	$str=str_replace("<","&lt;",$str);
	$str=str_replace(">","&gt;",$str);
	
	// Link inside tags new window
	$str = preg_replace("#\[url\](.*?)?(.*?)\[/url\]#si", "<A HREF=\"\\1\\2\" TARGET=\"_blank\">\\1\\2</A>", $str);
	
	// Link inside first tag new window
	$str = preg_replace("#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si", "<A HREF=\"\\2\" TARGET=\"_blank\">\\3</A>", $str);
	
	// Link inside tags
	$str = preg_replace("#\[url2\](.*?)?(.*?)\[/url2\]#si", "<A HREF=\"\\1\\2\">\\1\\2</A>", $str);
	
	// Link inside first tag
	$str = preg_replace("#\[url2=(.*?)?(.*?)\](.*?)\[/url2\]#si", "<A HREF=\"\\2\">\\3</A>", $str);
	
	// Automatic links if no url tags used
	$str = preg_replace_callback("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "bbcode_autolink", $str);
	$str = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", " <a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str);
	$str = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"mailto: \\2@\\3\">\\2_(at)_\\3</a>", $str);
	
	// PHP Code
	$str = preg_replace("#\[php\](.*?)\[/php]#si", "<div class=\"codetop\"><u><strong>&lt?PHP:</strong></u></div><div class=\"codemain\">\\1</div>", $str);
	
	// Bold
	$str = preg_replace("#\[b\](.*?)\[/b\]#si", "<strong>\\1</strong>", $str);
	
	// Italics
	$str = preg_replace("#\[i\](.*?)\[/i\]#si", "<em>\\1</em>", $str);
	
	// Underline
	$str = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $str);
	
	// Alig text
	$str = preg_replace("#\[align=(left|center|right)\](.*?)\[/align\]#si", "<div align=\"\\1\">\\2</div>", $str); 
	
	// Font Color
	$str = preg_replace("#\[color=(.*?)\](.*?)\[/color\]#si", "<span style=\"color:\\1\">\\2</span>", $str);
	
	// Font Size
	$str = preg_replace("#\[size=(.*?)\](.*?)\[/size\]#si", "<span style=\"font-size:\\1\">\\2</span>", $str);
	
	// Image
	$str = preg_replace("#\[img\](.*?)\[/img\]#si", "<img src=\"\\1\" border=\"0\" alt=\"\" />", $str);
	
	// Uploaded image
	$str = preg_replace("#\[ftp_img\](.*?)\[/ftp_img\]#si", "<img src=\"img/\\1\" border=\"0\" alt=\"\" />", $str);
	
	// HR Line
	$str = preg_replace("#\[hr=(\d{1,2}|100)\]#si", "<hr class=\"linia\" width=\"\\1%\" />", $str);
	
	// Code
	$str = preg_replace("#\[code\](.*?)\[/code]#si", "<div class=\"codetop\"><u><strong>Code:</strong></u></div><div class=\"codemain\">\\1</div>", $str);
	
	// Code, Provide Author
	$str = preg_replace("#\[code=(.*?)\](.*?)\[/code]#si", "<div class=\"codetop\"><u><strong>Code \\1:</strong></u></div><div class=\"codemain\">\\2</div>", $str);
	
	// Quote
	$str = preg_replace("#\[quote\](.*?)\[/quote]#si", "<div class=\"quotetop\"><u><strong>Quote:</strong></u></div><div class=\"quotemain\">\\1</div>", $str);
	
	// Quote, Provide Author
	$str = preg_replace("#\[quote=(.*?)\](.*?)\[/quote]#si", "<div class=\"quotetop\"><u><strong>Quote \\1:</strong></u></div><div class=\"quotemain\">\\2</div>", $str);
	
	// Email
	$str = preg_replace("#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i", "<a href=\"mailto:\\1@\\2\">\\1@\\2</a>", $str);
	
	// Email, Provide Author
	$str = preg_replace("#\[email=([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)?(.*?)\](.*?)\[/email\]#i", "<a href=\"mailto:\\1@\\2\">\\5</a>", $str);
	
	// YouTube
	$str = preg_replace("#\[youtube\]http://(?:www\.)?youtube.com/v/([0-9A-Za-z-_]{11})[^[]*\[/youtube\]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str);
	$str = preg_replace("#\[youtube\]http://(?:www\.)?youtube.com/watch\?v=([0-9A-Za-z-_]{11})[^[]*\[/youtube\]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str);
	
	// Google Video
	$str = preg_replace("#\[gvideo\]http://video.google.[A-Za-z0-9.]{2,5}/videoplay\?docid=([0-9A-Za-z-_]*)[^[]*\[/gvideo\]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://video.google.com/googleplayer.swf\?docId=\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://video.google.com/googleplayer.swf\?docId=\\1\" type=\"application/x-shockwave-flash\" allowScriptAccess=\"sameDomain\" quality=\"best\" bgcolor=\"#ffffff\" scale=\"noScale\" salign=\"TL\"  FlashVars=\"playerMode=embedded\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str);
	
	// change \n to <br />
	$str = nl2br($str);
	
	// return bbdecoded string
	return $str;
}


function bbcode_autolink($str) {
	$lnk=$str[3];
	if(strlen($lnk)>30){
	if(substr($lnk,0,3)=='www'){$l=9;}else{$l=5;}
	$lnk=substr($lnk,0,$l).'(...)'.substr($lnk,strlen($lnk)-8);}
	return ' <a href="'.$str[2].'://'.$str[3].'" target="_blank">'.$str[2].'://'.$lnk.'</a>';
}
	
//=========================================================
// Create a dropdown without the need for repeating code
//=========================================================
function createDropdown($type, $inputName, $currentSelection = "", $onChange = "", $class = "") {
	global $DBTABLEPREFIX, $USERSDBTABLEPREFIX, $clms_config;
	
	$onChangeVar = ($onChange == "") ? "" : " onChange=\"" . $onChange . "\"";
	$classVar = ($class == "") ? "" : " class=\"" . $class . "\"";
	
	$dropdown = "<select name=\"" . $inputName . "\" id=\"" . $inputName . "\"" . $classVar . "" . $onChangeVar . ">
					<option value=\"\">--Select One--</option>";
	if ($type == "problemcategories") {
		$sql = "SELECT pcats_id, pcats_name FROM `" . $DBTABLEPREFIX . "problemcategories` ORDER BY pcats_name ASC";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$dropdown .= "<option value=\"" . $row['pcats_id'] . "\"" . testSelected($row['pcats_id'], $currentSelection) . ">" . $row['pcats_name'] . "</option>";
			}
			mysql_free_result($result);
		}
	}
	if ($type == "techs") {
		$sql = "SELECT users_id, users_username FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_tech='1' ORDER BY users_last_name";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$dropdown .= "<option value=\"" . $row['users_id'] . "\"" . testSelected($row['users_id'], $currentSelection) . ">" . $row['users_username'] . "</option>";
			}
			mysql_free_result($result);
		}
	}
	if ($type == "status") {
		$dropdown .= "
					<option value=\"" . TICKET_OPEN . "\"" . testSelected($currentSelection, TICKET_OPEN) . ">" . $T_OPEN . "</option>
					<option value=\"" . TICKET_CLOSED . "\"" . testSelected($currentSelection, TICKET_CLOSED) . ">" . $T_CLOSED . "</option>";
	}
	if ($type == "users") {
		$sql = "SELECT users_id, users_username FROM `" . $USERSDBTABLEPREFIX . "users` ORDER BY users_last_name";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$dropdown .= "<option value=\"" . $row['users_id'] . "\"" . testSelected($row['users_id'], $currentSelection) . ">" . $row['users_username'] . "</option>";
			}
			mysql_free_result($result);
		}
	}
	if ($type == "userlevel") {
		$dropdown .= "
					<option value=\"" . BANNED . "\"" . testSelected($currentSelection, BANNED) . ">Banned</option>
					<option value=\"" . USER . "\"" . testSelected($currentSelection, USER) . ">User</option>
					<option value=\"" . MOD . "\"" . testSelected($currentSelection, MOD) . ">Moderator</option>
					<option value=\"" . ADMIN . "\"" . testSelected($currentSelection, ADMIN) . ">Administrator</option>";
	}
	$dropdown .= "</select>";	
	
	return $dropdown;	
}

//===================================================
// This function is designed to print the tickets 
// table on the homepage.
//
// USAGE:
// $content = returnMyTicketsTable($_SESSION['userid']);
//
// This will return the ticket table.
//===================================================
function returnMyTicketsTable($userID) {		
	global $DBTABLEPREFIX, $tts_config, $menuvar;
	global $T_CURRENT_TICKETS, $T_TICKET_ID, $T_TITLE, $T_CATEGORY, $T_DATE_CREATED, $T_STATUS, $T_NO_TICKET_FOUND, $T_OPEN, $T_CLOSED, $T_PLEASE, $T_LOGIN;
	
	$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $userID : $_SESSION['userid'];
			
	$content .= "\n
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
						<tr>
							<td class=\"title1\" colspan=\"5\">" . $T_CURRENT_TICKETS . "</td>
						</tr>
						<tr>
							<td class=\"title2\">" . $T_TICKET_ID . "</td>
							<td class=\"title2\">" . $T_TITLE . "</td>
							<td class=\"title2\">" . $T_CATEGORY . "</td>
							<td class=\"title2\">" . $T_DATE_CREATED . "</td>
							<td class=\"title2\">" . $T_STATUS . "</td>			
						</tr>";	
			 
			if (isset($_SESSION['username']) && $_SESSION['user_level'] != BANNED) {
				$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_user_id='" . $userID . "' GROUP BY tickets_opened DESC ORDER BY tickets_status, tickets_opened DESC";
				$result = mysql_query($sql);	
				if (mysql_num_rows($result) == 0) {
					$content .= "\n	<tr>
			    					<td class=\"row1\" colspan=\"5\">" . $T_NO_TICKET_FOUND . "</td>
								</tr>";
				}	
				else {
					while ($row = mysql_fetch_array($result)) {
			    		$status = ($row['tickets_status'] == TICKET_OPEN) ? "open" : "closed";
			    		$ticketstatus = ($row['tickets_status'] == TICKET_OPEN) ? $T_OPEN : $T_CLOSED;
			    		$title = ($row['tickets_title'] == "") ? "None" : $row['tickets_title'];
				
						$content .= "\n	<tr>
										<td class=\"row1-$status\">" . $row['tickets_id']. "</td>	
				    					<td class=\"row1-$status\"><a href=\"" . $menuvar['VIEWTICKET'] . "&id=" . $row['tickets_id'] . "\">" . $title . "</a></td>
				    					<td class=\"row1-$status\">" . getProblemCatName($row['tickets_pcat_id']) . "</td>
				    					<td class=\"row1-$status\">" . makeDate($row['tickets_opened']) . "</td>
				    					<td class=\"row1-$status\">" . $ticketstatus . "</td>
									</tr>";
					}	
				}
			}
			else {
				$content .= "\n	<tr class=\"row2\">
			    				<td class=\"row1\" colspan=\"4\">" . $T_PLEASE . " <a href=\"" . $menuvar['LOGIN'] . "\">" . $T_LOGIN . "</a>.</td>
							</tr>";
			}
			$content .= "\n
						</table>";
					
	// Return our table
	return $content;
}

//===================================================
// This function is designed to print the tickets 
// table on the admin page.
//
// USAGE:
// $content = returnTicketsTable($sql, $limit1, $limit2);
//
// This will return the ticket table.
//===================================================
function returnTicketsTable($sql, $limit1 = "0", $limit2 = "100") {		
	global $DBTABLEPREFIX, $tts_config, $menuvar;
	global $T_TICKETS, $T_CHANGE, $T_TICKET_ID, $T_TITLE, $T_CATEGORY, $T_USER, $T_PROBLEM_CATEGORY, $T_TECHNICIAN, $T_DATE_CREATED, $T_STATUS, $T_NO_TICKET_FOUND, $T_OPEN, $T_CLOSED, $T_UPDATE;
	
	$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $userID : $_SESSION['userid'];
			
	$content .= "\n
					<form name=\"ticketsForm\" id=\"ticketsForm\" action=\"" . $menuvar['ADMIN'] . "\" method=\"post\">" . $T_DISPLAY  . "<input type=\"text\" size=\"4\" value=\"" . $limit2 . "\" name=\"limit2\" /> rows starting at row <input type=\"text\" size=\"4\" value=\"" . $limit1 . "\" name=\"limit1\" />
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"8\">" . $T_ALL . " " . $action . " " . $T_TICKETS . "</td>
							</tr>
							<tr>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_TICKET_ID . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_TITLE . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_USER . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_PROBLEM_CATEGORY . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_TECHNICIAN . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_DATE_CREATED . "</td>
								<td class=\"title2\" style=\"text-align: center;\">" . $T_STATUS . "</td>
								<td class=\"title2\" style=\"text-align: center;\">&nbsp;</td>
							</tr>";				
		
	$result = mysql_query($sql);	
	if (mysql_num_rows($result) == 0) {
		$content .= "\n
					<tr>
		    			<td class=\"row2\" colspan=\"8\">" . $T_NO_TICKET_FOUND . "</td>
					</tr>";
	}	
	else {
		while ($row = mysql_fetch_array($result)) {
			$status = ($row['tickets_status'] == TICKET_OPEN) ? "open" : "closed";
		   	$ticketstatus = ($row['tickets_status'] == TICKET_OPEN) ? $T_OPEN : $T_CLOSED;
	    	$title = ($row['tickets_title'] == "") ? "None" : $row['tickets_title'];
		   
			$content .= "\n<tr id=\"" . $row['tickets_id']. "_row\">
								<td class=\"row1-" . $status . "\">" . $row['tickets_id']. "</td>							
					    		<td class=\"row1-" . $status . "\"><a href=\"" . $menuvar['VIEWTICKET'] . "&id=" . $row['tickets_id']. "\">" . $title . "</a></td>
					    		<td class=\"row1-" . $status . "\">" . getUsername($row['tickets_user_id']) . "</td>
					    		<td class=\"row1-" . $status . "\" style=\"text-align: center;\">
									" . createDropdown("problemcategories", "pcat_id" . $row['tickets_id'], $row['tickets_pcat_id'], "ajaxUpdaterWithSpinner('pcatSpinner" . $row['tickets_id']. "', 'ajax.php?action=updateitem&table=tickets&item=pcat_id&value=' + this.options[this.selectedIndex].value + '&id=" . $row['tickets_id']. "');") . "
									<span id=\"pcatSpinner" . $row['tickets_id']. "\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
								</td>
					    		<td class=\"row1-" . $status . "\" style=\"text-align: center;\">
									" . createDropdown("techs", "tech_id" . $row['tickets_id'], $row['tickets_tech_id'], "ajaxUpdaterWithSpinner('techSpinner" . $row['tickets_id']. "', 'ajax.php?action=updateitem&table=tickets&item=tech_id&value=' + this.options[this.selectedIndex].value + '&id=" . $row['tickets_id']. "');") . "
									<span id=\"techSpinner" . $row['tickets_id']. "\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
								</td>
					    		<td class=\"row1-" . $status . "\" style=\"text-align:right;\">" . makeDate($row['tickets_opened']) . "</td>
					    		<td class=\"row1-" . $status . "\" style=\"text-align: center;\">
									" . createDropdown("status", "status" . $row['tickets_id'], $row['tickets_status'], "ajaxUpdaterWithSpinner('statusSpinner" . $row['tickets_id']. "', 'ajax.php?action=updateitem&table=tickets&item=status&value=' + this.options[this.selectedIndex].value + '&id=" . $row['tickets_id']. "');") . "
									<span id=\"statusSpinner" . $row['tickets_id']. "\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
								</td>
								<td class=\"row1-" . $status . "\"><a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['tickets_id'] . "OrdersSpinner', 'ajax.php?action=deleteitem&table=tickets&id=" . $row['tickets_id'] . "', 'ticket', '" . $row['tickets_id'] . "_row');\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/delete.png\" alt='Delete Ticket' /></a><span id=\"" . $row['tickets_id'] . "OrdersSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
							</tr>";
		}	
		mysql_free_result($result);
	}

	$content .= "
						</table>
						<br />
						<div class=\"center\"><input name=\"submit\" type=\"submit\" value=\"" . $T_UPDATE . "\" class=\"button\" /></div>
					</form>";
					
	// Return our table
	return $content;
}

//===================================================
// This function is designed to print the ticket 
// and all of its replies.
//
// USAGE:
// $content = returnTicketEntries($ticketID);
//
// This will return the ticket entries table.
//===================================================
function returnTicketEntries($ticketID) {		
	global $DBTABLEPREFIX, $tts_config, $menuvar;
	global $T_TICKETS, $T_CHANGE, $T_TICKET_ID, $T_TITLE, $T_CATEGORY, $T_USER, $T_PROBLEM_CATEGORY, $T_TECHNICIAN, $T_DATE_CREATED, $T_STATUS, $T_TICKET_ENTRIES, $T_NO_TICKET_FOUND, $T_OPEN, $T_CLOSED, $T_UPDATE;
	
			
	$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_id='" . $ticketID . "' LIMIT 1";
	$result = mysql_query($sql);	
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$status = ($row['tickets_status'] == TICKET_OPEN) ? "open" : "closed";
		   	$ticketstatus = ($row['tickets_status'] == TICKET_OPEN) ? $T_OPEN : $T_CLOSED;
		   	$problemCategory = getProblemCatName($row['tickets_pcat_id']);
			
			// Allow admins and Mods to change the ticket status
			if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
				$ticketstatus = createDropdown("status", "status" . $row['tickets_id'], $row['tickets_status'], "ajaxUpdaterWithSpinner('statusSpinner" . $row['tickets_id']. "', 'ajax.php?action=updateitem&table=tickets&item=status&value=' + this.options[this.selectedIndex].value + '&id=" . $row['tickets_id']. "');") 
					. "	<span id=\"statusSpinner" . $row['tickets_id']. "\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>";
				
				$problemCategory = createDropdown("problemcategories", "pcat_id" . $row['tickets_id'], $row['tickets_pcat_id'], "ajaxUpdaterWithSpinner('pcatSpinner" . $row['tickets_id']. "', 'ajax.php?action=updateitem&table=tickets&item=pcat_id&value=' + this.options[this.selectedIndex].value + '&id=" . $row['tickets_id']. "');")
					. "	<span id=\"pcatSpinner" . $row['tickets_id']. "\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>";
			}
			
	    	$title = ($row['tickets_title'] == "") ? "None" : $row['tickets_title'];
			
			$content .= "\n
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">" . $title . "</td>
							</tr>
							<tr>
								<td class=\"title2\" style=\"width: 200px;\">" . $T_TICKET_ID . "</td><td class=\"row1\">" . $row['tickets_id'] . "</td>
							</tr>
							<tr>
								<td class=\"title2\">" . $T_USER . "</td><td class=\"row1\">" . getUsername($row['tickets_user_id']) . "</td>
							</tr>
							<tr>
								<td class=\"title2\">" . $T_PROBLEM_CATEGORY . "</td><td class=\"row1\">" . $problemCategory . "</td>
							</tr>
							<tr>
								<td class=\"title2\">" . $T_TECHNICIAN . "</td><td class=\"row1\">" . getUsername($row['tickets_tech_id']) . "</td>
							</tr>
							<tr>
								<td class=\"title2\">" . $T_DATE_CREATED . "</td><td class=\"row1\">" . makeDate($row['tickets_opened']) . "</td>
							</tr>
							<tr>
								<td class=\"title2\">" . $T_STATUS . "</td><td class=\"row1\">" . $ticketstatus . "</td>
							</tr>
							<tr>
								<td class=\"title1\" colspan=\"2\">" . $T_TICKET_ENTRIES . "</td>
							</tr>";				   
			
			// Pull the entries fro this ticket	
			$sql2 = "SELECT * FROM `" . $DBTABLEPREFIX . "entries` WHERE entries_ticket_id='" . $ticketID . "' ORDER BY entries_date ASC";
			$result2 = mysql_query($sql2);
			$x = 1;
			while ($row2 = mysql_fetch_array($result2)) {
				$content .= "\n
							<tr>
								<td class=\"title2\">" . getUsername($row2['entries_user_id']) . "</td>
								<td class=\"row" . $x . "\">" . nl2br($row2['entries_text']) . "</td>
							</tr>";
				$x = ($x == 1) ? 2 : 1;
			}
			mysql_free_result($result2); 
		}	
		mysql_free_result($result);
	}

	$content .= "\n</table>";
					
	// Return our table
	return $content;
}

//===================================================
// This function is designed to print the problem 
// categories table.
//
// USAGE:
// $content = returnTicketEntries($ticketID);
//
// This will return the ticket entries table.
//===================================================
function returnPCatsTable() {		
	global $DBTABLEPREFIX, $tts_config, $menuvar;
	global $T_PROBLEM_CATEGORIES, $T_PROBLEM_CATEGORY_NAME, $T_PROBLEM_CATEGORY, $T_EDIT_PCAT, $T_DELETE_PCAT;
	
			
	$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "problemcategories` ORDER BY pcats_name ASC";
	$result = mysql_query($sql);
		
	$x = 1; //reset the variable we use for our row colors			
	$content .= "\n<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
					<tr>
						<td class=\"title1\" colspan=\"3\">
							<div style=\"float: right;\">
								<form name=\"newCatForm\" action=\"" . $PHP_SELF . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
									<input type=\"text\" name=\"newcatname\" />
									<input type=\"image\" src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/add.png\" />
								</form>
							</div>
							" . $T_PROBLEM_CATEGORIES . "
						</td>
					</tr>
					<tr>
						<td class=\"title2\"><strong>" . $T_PROBLEM_CATEGORY_NAME . "</strong></td><td class=\"title2\">&nbsp;</td>
					</tr>";
							
	while ($row = mysql_fetch_array($result)) {
		$content .= "\n	<tr id=\"" . $row['pcats_id'] . "_row\">
							<td width=\"80%\" class=\"row" . $x . "\"><span id=\"" . $row['pcats_id'] . "_text\">" . $row['pcats_name'] . "</span></td>
							<td width=\"20%\" class=\"row" . $x . "\">
								<center><a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['pcats_id'] . "PCatSpinner', 'ajax.php?action=deleteitem&table=problemcategories&id=" . $row['pcats_id'] . "', '" . $T_PROBLEM_CATEGORY . "', '" . $row['pcats_id'] . "_row');\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/delete.png\" alt=\"" . $T_DELETE_PCAT . "\" /></a><span id=\"" . $row['pcats_id'] . "PCatSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
							</td>
						</tr>";
		$catids[$row['pcats_id']] = $row['pcats_name'];		
		$x = ($x==2) ? 1 : 2;
	}
	mysql_free_result($result);
		
	$content .= "\n</table>
						<script type=\"text/javascript\">";
	
	$x = 1; //reset the variable we use for our highlight colors
	foreach($catids as $key => $value) {
		$content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=problemcategories&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=problemcategories&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=problemcategories&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=problemcategories&item=name&id=" . $key . "'});";
		$x = ($x==2) ? 1 : 2;
	}
	
	$content .= "\n						
		
							function ValidateForm(theForm){
								var name=document.newCatForm.newcatname
			
								if ((name.value==null)||(name.value==\"\")){
									alert(\"Please enter the new categories name.\")
									name.focus()
									return false
								}
								new Ajax.Updater('updateMe', 'ajax.php?action=showIndicator', {asynchronous:true, evalScripts:true});
								new Ajax.Updater('updateMe', 'ajax.php?action=postPCat', {asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true});	
								return false;
							 }
						</script>";
					
	// Return our table
	return $content;
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
						
	$sql = "SELECT pcats_name FROM `" . $DBTABLEPREFIX . "problemcategories` WHERE pcats_id='" . $catID . "' LIMIT 1";
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
// This function is designed to get the name 
// of a user based on the id passed.
//
// USAGE:
// $userName = getUsername(userID);
//
// This will return the user's username from the DB.
//===================================================
function getUsername($userID) {		
	global $USERSDBTABLEPREFIX;
						
	$sql = "SELECT users_username, users_full_name FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id='" . $userID . "' LIMIT 1";
	$result = mysql_query($sql);
		
	if($result && mysql_num_rows($result) > 0) {
   		$row = mysql_fetch_array($result);
		$returnVal = (trim($row['users_username']) != "") ? $row['users_username'] : "N/A";
		$returnVal .= (trim($row['users_full_name']) != "") ? " (" . $row['users_full_name'] . ")" : "";
   		return $returnVal;
   	}
   	else {
   		return "N/A"; 
   	}
   	mysql_free_result($result);
}

//===================================================
// This function is designed to get the full name 
// of a user based on the id passed.
//
// USAGE:
// $userName = getUsersFullName(userID);
//
// This will return the user's full name from the DB.
//===================================================
function getUsersFullName($userID) {		
	global $USERSDBTABLEPREFIX;
						
	$sql = "SELECT users_full_name, username FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id='" . $userID . "' LIMIT 1";
	$result = mysql_query($sql);
		
	if($result && mysql_num_rows($result) > 0) {
   		$row = mysql_fetch_array($result);
		$returnVal = (trim($row['users_full_name']) != "") ? $row['users_full_name'] : "N/A";
		$returnVal .= (trim($row['users_username']) != "") ? " (" . $row['users_username'] . ")" : "";
   		return $returnVal;
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
// $language = get_language(userid);
//
// This will return the user's language from the DB.
//===================================================
function get_language($currentuser) {	
	global $tts_config, $USERSDBTABLEPREFIX;
	
	if (isset($currentuser) && $currentuser != " ") {						
		$sql = "SELECT users_language FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id='" . $currentuser . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result) > 0) {
   			$row = mysql_fetch_array($result);
   			return $row['users_language'];   			
   		}
		else { return $tts_config['ftstts_language']; }
	}
	else { return $tts_config['ftstts_language']; }
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
	
	$content = "\n						<form name=\"languagechange\" method=\"get\" action=\"" . $menuvar['SWITCHER'] . "\">
											<select name=\"languagechanger\" onChange=\"ajaxChangeLanguage('languageSpinner', 'ajax.php?action=updateitem&table=users&item=language&value=' + this.options[this.selectedIndex].value + '&id=" . $_SESSION['userid']. "', 'http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['HOME'] . "');\">
												<option>--Select One</option>";
	
	ksort($LANGUAGES);
	
	foreach ($LANGUAGES as $abbrev => $long) {
		$content .= "\n								<option value=\"$abbrev\"" . testSelected($selection, $abbrev) . ">$long</option>";
	}
	
	$content .= "\n							</select><span id=\"languageSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
									</form>";
									
				
									
	return $content;
}

//=========================================================
// Sends an email out
//=========================================================
function emailCreatedMessage($ticketID, $userID) {
	global $DBTABLEPREFIX, $tts_config, $menuvar;
	
	// To send HTML mail, the Content-type header must be set
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		
	// Additional headers
	$headers .= "To: " . $tts_config['ftstts_admin_email'] . "\r\n";
	$headers .= "From: " . $tts_config['ftstts_admin_email'] . "\r\n";
	
	// Subject
	$subject = "Trouble Ticket #" . $ticketID . " Has Been Submitted";
	
	// Message
	$message = "This email is to let you know that " . getUsername($userID) . " has submitted Ticket #" . $ticketID . ". You can view this ticket by logging into the Ticket System at http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['HOME'] . ".<br /><br />
				Please do not reply to this message.";
	
	// Mail it
	$emailResult = mail($emailAddress, $subject, $message, $headers);
	
	if ($emailResult) {
		return 1;
	}
	else {
		return 0;
	}
}

//=========================================================
// Sends an email out
//=========================================================
function emailUpdateMessage($ticketID, $userID) {
	global $DBTABLEPREFIX, $USERSDBTABLEPREFIX, $tts_config, $menuvar;
	$emailAddress = $ticketUserID = "";
	
	// To send HTML mail, the Content-type header must be set
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		
	// Additional headers
	$sql = "SELECT u.users_email_address, t.tickets_user_id FROM `" . $USERSDBTABLEPREFIX . "users` u, `" . $DBTABLEPREFIX . "tickets` t WHERE u.users_id=t.tickets_user_id AND t.tickets_id = '" . $ticketID . "';";
	$result = mysql_query($sql);
	
	if (mysql_num_rows($result) > 0 ) {
		while ($row = mysql_fetch_array($result)) { 
			$ticketUserID = $row['tickets_user_id'];
			$emailAddress = $row['users_email_address']; 
		}
	}
	mysql_free_result($result);
	
	$headers .= "To: " . $emailAddress . "\r\n";
	$headers .= "From: " . $tts_config['ftstts_admin_email'] . "\r\n";
	
	// Subject
	$subject = "Trouble Ticket #" . $ticketID . " Has Been Updated";
	
	// Message
	$message = "This email is to let you know that " . getUsername($userID) . " has updated Ticket #" . $ticketID . ". You can view your ticket by logging into the Ticket System http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['HOME'] . ".<br /><br />
				Please do not reply to this message.";
	
	// Mail it
	if ($ticketUserID != $userID) {
		$emailResult = mail($emailAddress, $subject, $message, $headers);
		if ($emailResult) {
			return 1;
		}
		else {
			return 0;
		}
	}
}

//=========================================================
// Sends an email out
//=========================================================
function emailMessage($emailAddress, $subject, $message) {
	global $DBTABLEPREFIX, $tss_config;
	
	// To send HTML mail, the Content-type header must be set
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		
	// Additional headers
	$headers .= "To: " . $emailAddress . "\r\n";
	$headers .= "From: " . ADMIN_EMAIL . "\r\n";
	
	// Mail it
	$emailResult = mail($emailAddress, $subject, $message, $headers);
	
	if ($emailResult) {
		return 1;
	}
	else {
		return 0;
	}
}

//=========================================================
// Check if this item should be selected
//=========================================================
function testSelected($testFor, $testAgainst) {
	if ($testFor == $testAgainst) { return " selected=\"selected\""; }
}

//=========================================================
// Check if this item should be selected
//=========================================================
function testChecked($testFor, $testAgainst) {
	if ($testFor == $testAgainst) { return " checked"; }
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
<?
/***************************************************************************
 *                               login.php
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
$current_time = time();

//========================================
// Login Function for registering session
//========================================
if (isset($_POST['password'])) {
	// Convert to simple variables
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(isset($username) && isset($password)) {	
		//Include these files because were not including the header file
		define('IN_TTS', true);
		include '_db.php';
		include_once ('includes/functions.php');
		session_start();
		
		// strip away any dangerous tags
		$username = keepsafe($username);
		$password = keepsafe($password);
		
		// Convert password to md5 hash
		$password = md5($password);
		
		// check if the user info validates the db
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_username='$username' AND users_password='$password'";
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_array($result)){
				extract($row);
				
				if (isset($_POST['autologin'])) {
					$cookiename = "trouble_ticket_system";
					setcookie($cookiename, $users_user_id . "-" . $users_password, time()+2592000 ); //set cookie for 1 month
				}
										
				// Register some session variables!
				$_SESSION['STATUS'] = "true";
				$_SESSION['userid'] = $users_user_id;
				$_SESSION['username'] = $users_username;
				$_SESSION['epassword'] = $users_password;
				$_SESSION['user_level'] = $users_user_level;
				
				include 'includes/header.php';			
				echo "\n$T_NOW_LOGGED_IN_MSG $_SESSION[username], $T_AND_BEING_REDIRECTED_MAIN";
				echo "\n<br /><center><a href=\"$menuvar[LOGOUT]\">$T_LOGOUT</a></center>";
				echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[HOME]'>"; 
			}
	
		} 
		else {
			include 'includes/header.php';
			echo "$T_COULD_NOT_LOGIN<br />	$T_TRY_AGAIN<br />";
			echo "<a href='$menuvar[HOME]'>$T_HOME</a>.";
		}		
	}	
	else {
		include 'includes/header.php';
		echo "$T_ENTER_ALL_INFO<br />";
		echo "<a href='$menuvar[HOME]'>$T_HOME</a>.";
	}
}


//========================================
// Logout Function
//========================================
elseif ($action == 'logout') {
	define('IN_TTS', true);
	include '_db.php';
	include_once ('includes/menu.php');
	
	//Destroy Session Cookie
	$cookiename = "trouble_ticket_system";
	setcookie($cookiename, false, time()-2592000); //set cookie to delete back for 1 month
	
	//Destroy Session
	session_destroy();
	if(!session_is_registered('first_name')){
		header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar[LOGIN]);	
		exit();
	}
}

//========================================
// If we got here check and see if they 
// are logged in, if not print login page
//========================================
else{
	include 'includes/header.php';
	if (isset($_SESSION['username'])) {
		echo "\n$T_NOW_LOGGED_IN_MSG $_SESSION[username], $T_AND_BEING_REDIRECTED_MAIN";
		echo "\n<br /><center><a href=\"$menuvar[LOGOUT]\">$T_LOGOUT</a></center>";
		echo "\n<meta http-equiv='refresh' content='1;url=$menuvar[HOME]'>";
 
	}
	else { 
		echo "\n	<center>";
		echo "\n	<form action=\"$menuvar[LOGIN]\" method=\"post\">";
		echo "\n		<table border=\"0\" Cellpadding=\"0\" cellspacing=\"0\" class=\"data-table-small\">";
		echo "\n			<tr><td colspan=\"2\" class=\"datatable-header1\">$T_LOGIN</td></tr>";
		echo "\n			<tr>";
		echo "\n				<td width=\"32%\" class=\"datatable-row2\">$T_USERNAME: </td>";
		echo "\n				<td width=\"68%\" class=\"datatable-row1\"><input type=\"text\" name=\"username\" size=\"20\" maxlength=\"40\" /></td>";
		echo "\n			</tr>";
		echo "\n			<tr>";
		echo "\n				<td width=\"32%\" " . $vasr . "class=\"datatable-row2\">$T_PASSWORD: </td>";
		echo "\n				<td width=\"68%\" class=\"datatable-row1\"><input type=\"password\" name=\"password\" size=\"20\" maxlength=\"25\" /></td>";
		echo "\n			</tr>";
		echo "\n			<tr>";
		echo "\n				<td width=\"100%\" colspan=\"2\" class=\"datatable-row2\">&nbsp;</td>";
		echo "\n			</tr>";
		echo "\n			<tr>";
		echo "\n				<td width=\"100%\" colspan=\"2\" class=\"datatable-row2\"><center><input type=\"submit\" class=\"button\" name=\"login\" value=\"$T_LOGIN\" /><input type=\"checkbox\" name=\"autologin\" border=\"0\" value=\"ON\" checked=\"checked\"/> $T_STAY_LOGGED_IN</center></td>";
		echo "\n			</tr>";
		echo "\n			<tr>";
		echo "\n				<td width=\"100%\" colspan=\"2\" class=\"datatable-row2\"><center><a href=\"$menuvar[JOIN]\">$T_REGISTER</a></center></td>";
		echo "\n			</tr>";
		echo "\n		</table>";
		echo "\n	</form>";
		echo "\n	</center>";
	}
}
unset($_POST['password']); //weve finished registering the session variables le them pass so they dont get reregistered

include 'includes/footer.php'; 
?>
<?php
/***************************************************************************
 *                               switcher.php
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
	define('IN_TTS', true);
	include '_db.php';
	session_start();
	include ('includes/functions.php');

	//============================================
	// Set the users language
	//============================================
	$currentuser = $_SESSION['username']; // Get the current username
	$language = $_GET['languagechanger']; // Get the language user is changing to
	$language = parseurl($language); // no hacking

	if (isset($currentuser)) {
		/*Change it in the database because they're logged in*/
		$sql = "UPDATE `" . $DBTABLEPREFIX . "users` SET users_language='$language' WHERE users_username='$currentuser'";
		echo $sql;
		mysql_query($sql) or die('Error, update query failed'); 	
	}	
	else {
		/*Use a cookie because they aren't logged in*/
		$languagecookie = "trouble_ticket_system_language";
		setcookie($languagecookie, $language, time()+2592000 ); // set cookie for 1 month
	}
					
	//============================================
	// Print the page
	//============================================
	include('includes/header.php');
	
	echo "Your language has been changed, and you are being redirected to the homepage."; 
	echo "<meta http-equiv='refresh' content='1;url=" . $menuvar[HOME] . "'>";

	include('includes/footer.php');
?>
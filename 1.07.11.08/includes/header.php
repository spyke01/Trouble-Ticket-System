<?PHP 
/***************************************************************************
 *                               header.php
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
ini_set('arg_separator.output','&amp;');
define('IN_TTS', true);
include '_db.php';
session_start();
include_once ('ttsconfig.php');
include_once ('includes/menu.php');
include_once ('includes/functions.php');
include_once ('includes/constants.php');

//=====================================================
// Cookie login script by: Demio from:  
// Changed some items and set it to expire in 1 month
//=====================================================
$cookiename = "trouble_ticket_system";
if (isset($_COOKIE[$cookiename]) && $_SESSION[STATUS] != "true" && !defined("IN_LOGIN")) {
                $data = explode("-", $_COOKIE[$cookiename]);
                
                $cookie_query = mysql_query("SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_userid='" . keepsafe($data[0]) . "' AND users_password='" . keepsafe($data[1]) . "'");
                $cookie_results = mysql_fetch_array($cookie_query);
                if (mysql_num_rows($cookie_query) == 1) {
                
                                $_SESSION[STATUS] = "true";
                                $_SESSION['userid'] = $cookie_results[users_userid];
                                $_SESSION['username'] = $cookie_results[users_username];
                                $_SESSION['epassword'] = $cookie_results[users_password];
								$_SESSION['user_level'] = $cookie_results[users_user_level];
                }
}

// Do the language dance ^^
$languageidentifier = get_language($_SESSION[username]);
include_once ('includes/constants_' . $languageidentifier . '.php'); //gets the current language
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><HTML>
<HEAD>
	<title>Fast Track Sites Trouble Ticket System</title>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<META http-equiv=Content-Language content=en-us>

	<!--Stylesheets Begin-->
	<link rel="stylesheet" type="text/css" href="stylesheets/main.css">
	<!--[if lte IE 6]>
		<LINK rel="stylesheet" type="text/css" href="stylesheets/iehacks.css">
	<![endif]-->
	<!--Stylesheets End-->
	<!--Javascripts Begin-->
		<script type="text/javascript" src="javascripts/prototype.js"></script>
		<script type="text/javascript" src="javascripts/validation.js"></script>
	<!--[if lt IE 7]>
		<script src="javascripts/pngfix.js" defer type="text/javascript"></script>
	<![endif]-->	
	<!--Javascripts End-->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
					<div style="float: right;">
						<? make_language_dropdown($languageidentifier); ?>
					</div>
					<img src="images/fts_logo_green.jpg" alt="Fast Track Sites Logo" />
				</div>
				<div id="top-nav">
					<ul id="nav">
						<li><a href="<? echo $menuvar[HOME]; ?>"><?= $T_MY_TICKETS; ?></a></li>
					<? 
						if ($_SESSION[user_level] != USER) {
							echo "						<li><a href=\"$menuvar[ADMIN]\">$T_ADMIN_PANEL</a></li>";
							echo "						<li><a href=\"$menuvar[USERS]\">$T_USER_ADMINISTRATION</a></li>";
							echo "						<li><a href=\"$menuvar[CONFIG]\">$T_CONFIGURATION</a></li>";
						}
					?>						
					</ul>
				</div>
				<div id="user-options">
					<div style="float:right;">
					<? 
						if ($_SESSION[username]) {
							echo "<a href='$menuvar[LOGOUT]'>Logout</a>";
						}
						else {
							echo "\n<a href='$menuvar[LOGIN]'>$T_LOGIN</a> &nbsp;|&nbsp; <a href='$menuvar[JOIN]'>$T_REGISTER</a>";
						}
					?>
					</div>
					<? 
						if ($_SESSION[username]) {
							echo "\n$T_WELCOME_BACK $_SESSION[username]!";
						}
						else {
							echo "\n$T_WELCOME_GUEST, $T_PLEASE <a href='$menuvar[LOGIN]'>$T_LOGIN</a>.";
						}
					?>					
				</div>
				<div id="content">
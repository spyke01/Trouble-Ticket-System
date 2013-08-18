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
//error_reporting(E_ALL); 
//ini_set('display_errors', '1');
ini_set('display_errors', '0');

include '_db.php';
session_start();
include_once ('includes/menu.php');
include_once ('includes/functions.php');
include_once ('includes/constants.php');

if (substr(phpversion(), 0, 1) == 5) { include_once ('includes/php5/pageclass.php'); }
else { include_once ('includes/php4/pageclass.php'); }

include_once ('config.php');

global $tts_config;
$page = new pageClass; //initialize our page

// Do the language dance ^^
$languageidentifier = get_language($_SESSION['userid']);
include ('includes/languages/constants_' . $languageidentifier . '.php'); //gets the current language

//=====================================================
// Cookie login script by: Demio from:  
// Changed some items and set it to expire in 1 month
//=====================================================
$cookiename = $tts_config['ftstts_cookie_name'];
if (isset($_COOKIE[$cookiename]) && $_SESSION['STATUS'] != "true" && !defined("IN_LOGIN")) {
	$data = explode("-", $_COOKIE[$cookiename]);

	$cookie_query = mysql_query("SELECT * FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_id='" . keepsafe($data[0]) . "' AND users_password='" . keepsafe($data[1]) . "'");
	$cookie_results = mysql_fetch_array($cookie_query);
	if (mysql_num_rows($cookie_query) == 1) {                
		$_SESSION['STATUS'] = "true";
		$_SESSION['userid'] = $cookie_results['users_id'];
		$_SESSION['username'] = $cookie_results['users_username'];
		$_SESSION['epassword'] = $cookie_results['users_password'];
		$_SESSION['email_address'] = $cookie_results['users_email_address'];
		$_SESSION['full_name'] = $cookie_results['users_full_name'];
		$_SESSION['website'] = $cookie_results['users_website'];
		$_SESSION['user_level'] = $cookie_results['users_user_level'];
		$_SESSION['script_locale'] = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	}
}
?>

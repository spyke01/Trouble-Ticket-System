<?php 
/***************************************************************************
 *                               index.php
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
// If the db connection file is missing we should redirect the user to install page
if (!file_exists('_db.php')) {
	header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/install.php");	
	exit();
}

include 'includes/header.php';

$requested_page_id = $_GET['p'];
$requested_section = $_GET['s'];
$requested_id = $_GET['id'];
$requested_action = $_GET['action'];

$actual_page_id = ($requested_page_id == "" || !isset($requested_page_id)) ? 1 : $requested_page_id;
$actual_page_id = parseurl($actual_page_id);
$actual_section = parseurl($requested_section);
$actual_id = parseurl($requested_id);
$actual_language_changer = parseurl($requested_language_changer);
$actual_action = parseurl($requested_action);
$page_content = "";

// Warn the user if the install.php script is present
if (file_exists('install.php')) {
	$page_content = "<strong style=\"color: red;\">Warning: install.php is present, please remove this file for security reasons.</strong><br /><br />";
}

//========================================
// Logout Function
//========================================
// Prevent spanning between apps to avoid a user getting more acces that they are allowed
if ($_SESSION['script_locale'] != rtrim(dirname($_SERVER['PHP_SELF']), '/\\') && session_is_registered('userid')) {
	session_destroy();
}

if ($actual_page_id == "logout") {
	define('IN_FTSTTS', true);
	include '_db.php';
	include_once ('includes/menu.php');
	include_once ('config.php');
	global $tts_config;
	
	//Destroy Session Cookie
	$cookiename = $tts_config['ftstts_cookie_name'];
	setcookie($cookiename, false, time()-2592000); //set cookie to delete back for 1 month
	
	//Destroy Session
	session_destroy();
	if(!session_is_registered('first_name')){
		header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/index.php");	
		exit();
	}
}

//Check to see if advanced options are allowed or not
if (version_functions("advancedOptions") == true) {
	// If the system is locked, then only a moderator or admin should be able to view it
	if ($_SESSION['user_level'] != ADMIN && $_SESSION['user_level'] != MOD && $tts_config['ftstts_active'] != ACTIVE) {
		if ($actual_page_id == "login") {
			include 'login.php';
		}
		else {	
			$page->setTemplateVar("PageTitle", $T_DISABLED);
			$page->setTemplateVar("PageContent", bbcode($tts_config[ftstts_inactive_msg]));
		}
		// Top Menus
		$page->makeMenuItem("top", "<img src=\"images/logo.gif\" alt=\"Fast Track Sites Logo\" />", "", "logo");
		$page->makeMenuItem("top", $T_HOME, $menuvar['HOME'], "");
	
		if (!isset($_SESSION['username'])) {
			$page->makeMenuItem("top", $T_LOGIN, $menuvar['LOGIN'], "");
			$page->makeMenuItem("top", $T_REGISTER, $menuvar['REGISTER'], "");
		}
		else {
			$page->makeMenuItem("top", $T_LOGOUT, $menuvar['LOGOUT'], "");
		}
	}
	else {
		//========================================
		// Admin panel options
		//========================================
		if ($actual_page_id == "admin") {
			if (!$_SESSION['username']) { include 'login.php'; }
			else {
				if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
					if ($actual_section == "" || !isset($actual_section)) {
						include 'admin.php'; 
						$page->setTemplateVar("PageTitle", $T_ADMIN_PANEL);
					}
					elseif ($actual_section == "settings") {
						include 'settings.php';				
						$page->setTemplateVar("PageTitle", $T_CONFIGURATION);
					}
					elseif ($actual_section == "themes") {
						include 'themes.php';			
						$page->setTemplateVar("PageTitle", $T_THEMES);	
					}
					elseif ($actual_section == "users") {
						include 'users.php';			
						$page->setTemplateVar("PageTitle", $T_USER_ADMINISTRATION);	
					}
				}
				else { setTemplateVar("PageContent", "You are not authorized to access the admin panel."); }
			}
		}
		elseif ($actual_page_id == "login") {
			include 'login.php';
			$page->setTemplateVar("PageTitle", $T_LOGIN);	
		}
		elseif ($actual_page_id == "register") {
			include 'register.php';
			$page->setTemplateVar("PageTitle", $T_REGISTER);	
		}
		elseif ($actual_page_id == "viewticket" && $actual_id != "") {
			include 'viewticket.php';			
			$page->setTemplateVar("PageTitle", $T_VIEW_TICKET);	
		}
		else {
			//========================================
			// Print the Main Page
			//========================================			
			if (isset($_SESSION['username']) && $_SESSION['user_level'] != BANNED) {
				$page_content .= "
					<div id=\"updateMe\">" . returnMyTicketsTable($_SESSION['userid']) . "</div>
					<br /><br />			
					<div id=\"response\"></div>
					<form name=\"newTicketForm\" id=\"newTicketForm\" action=\"" . $menuvar['POST'] . "?action=newticket\" method=\"post\" onSubmit=\"return false;\">
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">" . $T_NEW_TROUBLE_TICKET . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_NAME . ": </strong></td><td class=\"row1\">" . $_SESSION['username'] . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_TICKET_TITLE . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"ticketTitle\" id=\"ticketTitle\" size=\"73\" class=\"required\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_PHONE_NUMBER . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"phoneNumber\" size=\"73\" class=\"required\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_MODEL_NAME . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"modelName\" size=\"73\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_SERIAL_NUMBER . ": </strong></td><td class=\"row1\"><input type=\"text\" name=\"serialNumber\" size=\"73\" /></td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>" . $T_PROBLEM_CATEGORY . ": </strong></td>
							  	<td class=\"row1\">
									" . createDropdown("problemcategories", "pCatID", "") . "
							  	</td>
							</tr>
							<tr>
							  <td class=\"title2\" colspan=\"2\"><div class=\"center\"><textarea cols=\"70\" rows=\"8\" name=\"message\" wrap=\"virtual\" class=\"required\"></textarea><br /></div></td>
							</tr>		
						</table>	
						<br />
						<div class=\"center\"><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"" . $T_CREATE_TICKET . "\" class=\"button\" /> <span id=\"submitSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></div>	
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('newTicketForm', {immediate : true, useTitles:true, onFormValidate : ValidateForm});
				
						function ValidateForm(result, formRef) {
							if (result == true) {
								submitButton = fetchItem('submit');
								submitButton.disabled = true;
								sqr_show_hide('submitSpinner');
								new Ajax.Updater('updateMe', 'ajax.php?action=showIndicator', {asynchronous:true, evalScripts:true});
								new Ajax.Updater('response', 'ajax.php?action=postTicket', {onComplete:function(){ sqr_show_hide('submitSpinner'); submitButton.disabled = false; new Ajax.Updater('updateMe', 'ajax.php?action=printMyTicketsTable', {asynchronous:true, evalScripts:true}); },asynchronous:true, parameters:Form.serialize(document.newTicketForm), evalScripts:true});								
							}
							return false;
			 			}
					</script>";
			}
			else { $page_content .= $T_WELCOME_MESSAGE; }
				
			$page->setTemplateVar("PageTitle", "Home");
			$page->setTemplateVar("PageContent", $page_content);
		}
	
		//================================================
		// Get Menus
		//================================================		
		// Top Menus
		$page->makeMenuItem("top", "<img src=\"images/logo.gif\" alt=\"Fast Track Sites Logo\" />", "", "logo");
		$page->makeMenuItem("top", $T_HOME, $menuvar['HOME'], "");
		
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			$page->makeMenuItem("top", $T_ADMIN_PANEL, $menuvar['ADMIN'], "");
			$page->makeMenuItem("top", $T_CONFIGURATION, $menuvar['SETTINGS'], "");
			$page->makeMenuItem("top", $T_THEMES, $menuvar['THEMES'], "");
			$page->makeMenuItem("top", $T_USER_ADMINISTRATION, $menuvar['USERS'], "");
		}
	
		if (!isset($_SESSION['username'])) {
			$page->makeMenuItem("top", $T_LOGIN, $menuvar['LOGIN'], "");
			$page->makeMenuItem("top", $T_REGISTER, $menuvar['REGISTER'], "");
		}
		else {
			$page->makeMenuItem("top", $T_LOGOUT, $menuvar['LOGOUT'], "");
			$page->makeMenuItem("top", make_language_dropdown($languageidentifier), "", "");
		}
	}
}
else { $page->setTemplateVar("PageContent", version_functions("advancedOptionsText")); }

version_functions("no");
include "themes/" . $tts_config['ftstts_theme'] . "/template.php";
?>
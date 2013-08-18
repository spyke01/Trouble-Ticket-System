<? 
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
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
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
$requested_action2 = $_GET['action2'];
$requested_style = $_GET['style'];
$requested_report = $_REQUEST['report'];

$actual_page_id = ($requested_page_id == "" || !isset($requested_page_id)) ? 1 : $requested_page_id;
$actual_page_id = parseurl($actual_page_id);
$actual_section = parseurl($requested_section);
$actual_id = parseurl($requested_id);
$actual_action = parseurl($requested_action);
$actual_action2 = parseurl($requested_action2);
$actual_style = parseurl($requested_style);
$actual_report = parseurl($requested_report);
$page_content = "";

// Warn the user if the install.php script is present
if (file_exists('install.php')) {
	$page_content = "<div class=\"errorMessage\">Warning: install.php is present, please remove this file for security reasons.</div>";
}

// We want to show all of our menus by default
$page->setTemplateVar("uOLm_active", ACTIVE);
$page->setTemplateVar("aOLm_active", ACTIVE);

//========================================
// Logout Function
//========================================
// Prevent spanning between apps to avoid a user getting more acces that they are allowed
if ($_SESSION['script_locale'] != rtrim(dirname($_SERVER['PHP_SELF']), '/\\') && session_is_registered('userid')) {
	session_destroy();
}

if ($actual_page_id == "logout") {	
	//Destroy Session Cookie
	$cookiename = $tts_config['ftstts_cookie_name'];
	setcookie($cookiename, false, time()-2592000); //set cookie to delete back for 1 month
	
	//Destroy Session
	session_destroy();
	if(!session_is_registered('userid')){
		header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/index.php");	
		exit();
	}
}

// Top Menus
$page->makeMenuItem("top", "<img src=\"images/ftsLogo.png\" alt=\"Fast Track Sites Logo\" />", "", "logo");
$page->makeMenuItem("top", $LANG['HOME'], "index.php", "");
		
//Check to see if advanced options are allowed or not
if (version_functions("advancedOptions") == true) {
	// If the system is locked, then only a moderator or admin should be able to view it
	if ($_SESSION['user_level'] != SYSTEM_ADMIN && $_SESSION['user_level'] != TICKET_ADMIN && $tts_config['ftstts_active'] != ACTIVE) {
		if ($actual_page_id == "admin") {
			include 'login.php';
		}
		else {	
			$page->setTemplateVar("PageTitle", $LANG['DISABLED']);
			$page->setTemplateVar("PageContent", bbcode($tts_config['ftstts_inactive_msg']));
		
			// Let us login so we can access the system during a shutdown
			$page->setTemplateVar("PageTitle", $LANG['LOGIN']);	
			$page->addBreadCrumb($LANG['LOGIN'], $menuvar['LOGIN']);
			include 'login.php';
					
			// We want to show all of our menus by default
			$page->setTemplateVar("aOLm_active", INACTIVE);
			$page->makeMenuItem("userOptionsLeft", $LANG['LOGIN'], "index.php?p=login", "");
		}
	}
	else {
		//========================================
		// Admin panel options
		//========================================
		if ($actual_page_id == "admin") {
			// Add breadcrumb pointing home
			$page->addBreadCrumb($LANG['ADMIN'], $menuvar['SYSTEM_ADMIN']);
			
			if (!$_SESSION['username']) { include 'login.php'; }
			else {
				if ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) {
					if ($actual_section == "" || !isset($actual_section)) {
						$page->setTemplateVar("PageTitle", $LANG['ADMIN_PANEL']);
						include 'admin.php'; 
					}
					elseif ($actual_section == "categories") {
						$page->setTemplateVar("PageTitle", $LANG['PROBLEM_CATEGORIES']);	
						$page->addBreadCrumb($LANG['PROBLEM_CATEGORIES'], $menuvar['CATEGORIES']);
						include 'categories.php';
					}
					elseif ($actual_section == "graphs") {
						$page->setTemplateVar("PageTitle", $LANG['GRAPHS']);		
						$page->addBreadCrumb($LANG['GRAPHS'], $menuvar['GRAPHS']);
						include 'graphs.php';
					}
					elseif ($actual_section == "reports") {
						$page->setTemplateVar("PageTitle", $LANG['REPORTS']);		
						$page->addBreadCrumb($LANG['REPORTS'], $menuvar['REPORTS']);
						include 'reports.php';
					}
					elseif ($actual_section == "settings") {
						$page->setTemplateVar("PageTitle", $LANG['SETTINGS']);
						$page->addBreadCrumb($LANG['SETTINGS'], $menuvar['SETTINGS']);
						include 'settings.php';
					}
					elseif ($actual_section == "themes") {
						$page->setTemplateVar("PageTitle", $LANG['THEMES']);
						$page->addBreadCrumb($LANG['THEMES'], $menuvar['THEMES']);
						include 'themes.php';
					}
					elseif ($actual_section == "tickets") {
						$page->setTemplateVar("PageTitle", $LANG['TICKETS']);		
						$page->addBreadCrumb($LANG['TICKETS'], $menuvar['TICKETS']);
						include 'tickets.php';
					}
					elseif ($actual_section == "users") {
						$page->setTemplateVar("PageTitle", $LANG['USER_ADMINISTRATION']);	
						$page->addBreadCrumb($LANG['USER_ADMINISTRATION'], $menuvar['USERS']);
						include 'users.php';
					}
				}
				else { $page->setTemplateVar("PageContent", $LANG['ERROR_NOT_AUTHORIZED']); }
			}
		}
		elseif ($actual_page_id == $LANG['LOGIN']) {
			$page->setTemplateVar("PageTitle", $LANG['LOGIN']);	
			$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
			$page->addBreadCrumb($LANG['LOGIN'], $menuvar['LOGIN']);
			include 'login.php';
		}
		elseif ($actual_page_id == "mytickets") {
			$page->setTemplateVar("PageTitle", $LANG['MY_TICKETS']);	
			$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
			$page->addBreadCrumb($LANG['MY_TICKETS'], $menuvar['MYTICKETS']);
			include 'mytickets.php';
		}
		elseif ($actual_page_id == "register") {
			$page->setTemplateVar("PageTitle", $LANG['CREATE_ACCOUNT']);	
			$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
			$page->addBreadCrumb($LANG['CREATE_ACCOUNT'], $menuvar['REGISTER']);
			include 'register.php';
		}
		elseif ($actual_page_id == "viewticket") {
			$page->setTemplateVar("PageTitle", $LANG['VIEW_TICKET']);	
			$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
			$page->addBreadCrumb($LANG['VIEW_TICKET'], $menuvar['VIEWTICKET']);
			include 'viewticket.php';
		}
		elseif ($actual_page_id == "version") {
			$page->setTemplateVar("PageTitle", "Version Information");	
			$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
			$page->addBreadCrumb("Version Information", "");
			
			include('_license.php');
		
			$page_content .= "
				<div class=\"roundedBox\">
					<h1>Version Information</h1>
					<strong>Application:</strong> " . A_NAME . "<br />
					<strong>Version:</strong> " . A_VERSION . "<br />
					<strong>Registered to:</strong> " . $A_Licensed_To . "<br />
					<strong>Serial:</strong> " . $A_License . "
				</div>";
			
			$page->setTemplateVar("PageContent", $page_content);	
		}
		else {			
			// Only let Admins and Mods view the admin homepage
			if ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) {
				$page->setTemplateVar("PageTitle", $LANG['ADMIN_PANEL']);
				$page->addBreadCrumb($LANG['ADMIN'], $menuvar['SYSTEM_ADMIN']);
				include 'admin.php'; 
			}
			else {
				$page->setTemplateVar("PageTitle", $LANG['HOME']);	
				$page->addBreadCrumb($LANG['HOME'], $menuvar['HOME']);
				
				if (!isset($_SESSION['username'])) {
					$page->setTemplateVar("PageTitle", $LANG['LOGIN']);	
					$page->addBreadCrumb($LANG['LOGIN'], $menuvar['LOGIN']);
					include 'login.php';
				}
				else {
					$page_content .= "
						<h1>" . $LANG['INDEX_WELCOME_MESSAGE_TITLE'] . "</h1>
						" . $LANG['INDEX_WELCOME_MESSAGE_TEXT'];
					
					$page->setTemplateVar("PageContent", $page_content);	
				}
			}
		}
	
		//================================================
		// Get Menus
		//================================================		
		// Admin Options Menu
		if ($_SESSION['user_level'] == TICKET_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) {	
			$page->makeMenuItem("adminOptionsLeft", $LANG['TICKETS'], $menuvar['TICKETS'], "");
			$page->makeMenuItem("adminOptionsLeft", $LANG['PROBLEM_CATEGORIES'], $menuvar['CATEGORIES'], "");
		}
		if ($_SESSION['user_level'] == SYSTEM_ADMIN) {
			$page->makeMenuItem("top", $LANG['SETTINGS'], $menuvar['SETTINGS'], "");
		
			$page->makeMenuItem("adminOptionsLeft", $LANG['REPORTS'], $menuvar['REPORTS'], "");
			$page->makeMenuItem("adminOptionsLeft", $LANG['GRAPHS'], $menuvar['GRAPHS'], "");
			$page->makeMenuItem("adminOptionsLeft", $LANG['THEMES'], $menuvar['THEMES'], "");
			$page->makeMenuItem("adminOptionsLeft", $LANG['USER_ADMINISTRATION'], $menuvar['USERS'], "");
		}
		
		// User Options Menu
		if (!isset($_SESSION['username'])) {
			$page->makeMenuItem("userOptionsLeft", $LANG['LOGIN'], $menuvar['LOGIN'], "");
			$page->makeMenuItem("userOptionsLeft", $LANG['CREATE_ACCOUNT'], $menuvar['REGISTER'], "");
		}
		else {
			$page->makeMenuItem("userOptionsLeft", $LANG['MY_TICKETS'], $menuvar['MYTICKETS'], "");
			$page->makeMenuItem("userOptionsLeft", $LANG['LOGOUT'], $menuvar['LOGOUT'], "");
		}
	}
}
else { $page->setTemplateVar("PageContent", version_functions("advancedOptionsText")); }

version_functions("no");
if (isset($actual_style) && $actual_style == "printerFriendly") { include "themes/" . $tts_config['ftstts_theme'] . "/printerFriendlyTemplate.php"; }
else { include "themes/" . $tts_config['ftstts_theme'] . "/template.php"; }
?>
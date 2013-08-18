<?php 
/***************************************************************************
 *                               users.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
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
 
	//=========================================================
	// Gets a language from a userid
	//=========================================================
	function getUsersLanguageFromID($userID) {
		global $tts_config;
		$language = "";
		
		if (is_numeric($userID)) {
			$sql = "SELECT language FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "'";
			$result = mysql_query($sql);
			
			if ($result && mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_array($result)) {
					$language = $row['language'];
				}
				mysql_free_result($result);
			}
		}
		
		$language = ($language == "") ? $tts_config['ftstts_language'] : $language;
		
		return $language;
	}
 
	//=========================================================
	// Gets a username from a userid
	//=========================================================
	function getUsernameFromID($userID) {
		$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['username'];
			}	
			mysql_free_result($result);
		}
	}
	
	//=========================================================
	// Gets a user's userlevel from a userid
	//=========================================================
	function getUserlevelFromID($userID) {
		global $LANG;
		$level = "";
		
		$sql = "SELECT user_level FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$level = ($row['user_level'] == SYSTEM_ADMIN) ? $LANG['SYSTEM_ADMINISTRATOR'] : $LANG['TICKET_ADMINISTRATOR'];
				$level = ($row['user_level'] == USER) ? $LANG['USER'] : $level;
				$level = ($row['user_level'] == BANNED) ? $LANG['BANNED'] : $level;
			}	
			mysql_free_result($result);
		}
		
		return $level;
	}
	
	//=========================================================
	// Gets an email address from a userid
	//=========================================================
	function getEmailAddressFromID($userID) {
		$sql = "SELECT email_address FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['email_address'];
			}	
			mysql_free_result($result);
		}
	}
	
 	//=================================================
	// Print the Users Table
	//=================================================
	function printSearchUsersTable($postVars) {
		global $menuvar, $tts_config, $LANG;
			
		$x = 1; //reset the variable we use for our row colors	
		
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_SEARCH_USERS'], "colspan" => "2")), "", "title1");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_SEARCH_HOW_TO_MESSAGE'], "colspan" => "2")
			), "", "title2"
		);
							
		// Add our data
		$fieldArray = array(
			'search_username' => $LANG['TABLEHEADERS_USERNAME'] . ":",
			'search_email_address' => $LANG['TABLEHEADERS_EMAIL_ADDRESS'] . ":",
			'search_first_name' => $LANG['TABLEHEADERS_FIRST_NAME'] . ":",
			'search_last_name' => $LANG['TABLEHEADERS_LAST_NAME'] . ":"
		);
		
		foreach ($fieldArray as $fieldName => $title) {
			$table->addNewRow(
				array(
					array("data" => $title),
					array("data" => "<input type=\"text\" name=\"" . $fieldName . "\" size=\"40\" value=\"" . keeptasafe($postVars[$fieldName]) . "\" />")
				), "", "row" . $x
			);
			
			$x = ($x==2) ? 1 : 2;
		}
		
		// Return the table's HTML
		$content = "
				<form name=\"searchUsersForm\" id=\"searchUsersForm\" action=\"" . $menuvar['USERS'] . "\" method=\"post\" onsubmit=\"return false;\">
					" . $table->returnTableHTML() . "
					<input type=\"submit\" name=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_SEARCH'] . "\" />
				</form>";
		
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new order form
	//=================================================
	function returnSearchUsersTableJQuery() {			
		$JQueryReadyScripts = "
			var v = jQuery(\"#searchUsersForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {	
					jQuery.get('ajax.php?action=searchUsers', $('#searchUsersForm').serialize(), function(data) {
						// Update the proper div with the returned data
						$('#updateMeUsers').html(data);
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}
	
 	//=================================================
	// Print the Users Table
	//=================================================
	function printUsersTable($postVars = array()) {
		global $menuvar, $tts_config, $LANG;
		
		$currentTimestamp = time();
		$todayTimestamp = strtotime(gmdate('Y-m-d', $currentTimestamp + (3600 * '-7.00')));
		$tomorrowTimestamp = strtotime(gmdate('Y-m-d', strtotime("+1 day") + (3600 * '-7.00')));
		
		$extraSQL = " WHERE 1";
		$extraSQL .= (isset($postVars['search_username']) && $postVars['search_username'] != "") ? " AND username LIKE '%" . keepsafe($postVars['search_username']) . "%'" : "";
		$extraSQL .= (isset($postVars['search_email_address']) && $postVars['search_email_address'] != "") ? " AND email_address LIKE '%" . keepsafe($postVars['search_email_address']) . "%'" : "";
		$extraSQL .= (isset($postVars['search_first_name']) && $postVars['search_first_name'] != "") ? " AND first_name LIKE '%" . keeptasafe($postVars['search_first_name']) . "%'" : "";
		$extraSQL .= (isset($postVars['search_last_name']) && $postVars['search_last_name'] != "") ? " AND last_name LIKE '%" . keeptasafe($postVars['search_last_name']) . "%'" : "";
		
		$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users`" . $extraSQL . " ORDER BY signup_date DESC";
		$result = mysql_query($sql);
		
		// Create our new table
		$table = new tableClass(1, 1, 1, "contentBox tablesorter", "usersTable");
		
		// Create table title
		$table->addNewRow(array(array("data" => $LANG['TABLETITLES_CURRENT_USERS'] . " (" . mysql_num_rows($result) . ")", "colspan" => "6")), "", "title1", "thead");
		
		// Create column headers
		$table->addNewRow(
			array(
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USERNAME']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_EMAIL_ADDRESS']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_FULL_NAME']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_SIGNUP_DATE']),
				array("type" => "th", "data" => $LANG['TABLEHEADERS_USER_LEVEL']),
				array("type" => "th", "data" => "")
			), "", "title2", "thead"
		);
							
		// Add our data
		if (!$result || mysql_num_rows($result) == 0) {
			$table->addNewRow(array(array("data" => $LANG['ERROR_NO_USERS'], "colspan" => "6")), "usersTableDefaultRow", "greenRow");
		}
		else {
			while ($row = mysql_fetch_array($result)) {				
				$table->addNewRow(
					array(
						array("data" => $row['username']),
						array("data" => $row['email_address']),
						array("data" => $row['first_name'] . " " . $row['last_name']),
						array("data" => makeDate($row['signup_date'])),
						array("data" => getUserlevelFromID($row['id'])),
						array("data" => "<a href=\"" . $menuvar['USERS'] . "&amp;action=edituser&amp;id=" . $row['id'] . "\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/check.png\" alt=\"Edit User Details\" /></a> " . createDeleteLinkWithImage($row['id'], $row['id'] . "_row", "users", "user"), "class" => "center")
					), $row['id'] . "_row", ""
				);
			}
			mysql_free_result($result);
		}
		
		// Return the table's HTML
		return $table->returnTableHTML() . "
				<div id=\"usersTableUpdateNotice\"></div>";
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// users table
	//=================================================
	function returnUsersTableJQuery() {							
		$JQueryReadyScripts = "
				$('#usersTable').tablesorter({ widgets: ['zebra'], headers: { 5: { sorter: false } } });";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Create a form to add new orders
	//
	// Used so that we can display it in many places
	//=================================================
	function printNewUserForm() {
		global $menuvar, $tts_config, $LANG;

		$content .= "
				<div id=\"newUserResponse\">
				</div>
				<form name=\"newUserForm\" id=\"newUserForm\" action=\"" . $menuvar['USERS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_NEW_USER'] . "</legend>
						<div><label for=\"first_name\">" . $LANG['FORMITEMS_FIRST_NAME'] . " <span>- Required</span></label> <input name=\"first_name\" id=\"first_name\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"last_name\">" . $LANG['FORMITEMS_LAST_NAME'] . " <span>- Required</span></label> <input name=\"last_name\" id=\"last_name\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"email_address\">" . $LANG['FORMITEMS_EMAIL_ADDRESS'] . " <span>- Required</span></label> <input name=\"email_address\" id=\"email_address\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"username\">" . $LANG['FORMITEMS_USERNAME'] . " <span>- Required</span></label> <input name=\"username\" id=\"username\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"password\">" . $LANG['FORMITEMS_PASSWORD'] . " <span>- Required</span></label> <input name=\"password\" id=\"password\" type=\"password\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"password2\">" . $LANG['FORMITEMS_CONFIRM_PASSWORD'] . " <span>- Required</span></label> <input name=\"password2\" id=\"password2\" type=\"password\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"company\">" . $LANG['FORMITEMS_COMPANY'] . " </label> <input name=\"company\" id=\"company\" type=\"text\" size=\"60\" /></div>
						<div><label for=\"website\">" . $LANG['FORMITEMS_WEBSITE'] . " </label> <input name=\"website\" id=\"website\" type=\"text\" size=\"60\" /></div>
						<div><label for=\"userlevel\">" . $LANG['FORMITEMS_USER_LEVEL'] . " <span>- Required</span></label> " . createDropdown("userlevel", "userlevel", "", "", "required") . "</div>
						<div><label for=\"language\">" . $LANG['FORMITEMS_LANGUAGE'] . " <span>- Required</span></label> " . createDropdown("languages", "language", "", "", "required") . "</div>
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_CREATE_USER'] . "\" /></div>
					</fieldset>
				</form>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new order form
	//=================================================
	function returnNewUserFormJQuery($reprintTable = 0, $allowModification = 1) {		
		$extraJQuery = ($reprintTable == 0) ? "
  						// Update the proper div with the returned data
						$('#newUserResponse').html('" . progressSpinnerHTML() . "');
						$('#newUserResponse').html(data);
						$('#newUserResponse').effect('highlight',{},500);" 
						: "
						// Clear the default row
						$('#usersTableDefaultRow').remove();
  						// Update the table with the new row
						$('#usersTable > tbody:last').append(data);
						$('#usersTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
						// Show a success message
						$('#newUserResponse').html('" . progressSpinnerHTML() . "');
						$('#newUserResponse').html(returnSuccessMessage('user'));";
							
		$JQueryReadyScripts = "
			var v = jQuery(\"#newUserForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				rules: {
					password2: {
						equalTo: '#password'
					}
				},
				submitHandler: function(form) {			
					jQuery.get('ajax.php?action=createUser&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification . "', $('#newUserForm').serialize(), function(data) {
  						" . $extraJQuery . "
						// Clear the form
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}
	
	//=================================================
	// Create a form to edit orders
	//
	// Used so that we can display it in many places
	//=================================================
	function printEditUserForm($userID) {
		global $menuvar, $tts_config, $LANG;
		
		$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users` WHERE id = '" . $userID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) == 0) {
			$page_content = "<span class=\"center\">" . $LANG['ERROR_EDIT_USER'] . " " . $LANG['WARNINGS_REDIRECT_TO_MAIN_PAGE'] . "</span>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";	
		}
		else {
			$row = mysql_fetch_array($result);
			
			$content .= "
				<div id=\"editUserResponse\">
				</div>
				<form name=\"editUserForm\" id=\"editUserForm\" action=\"" . $menuvar['USERS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_EDIT_USER'] . "</legend>
						<div><label for=\"first_name\">" . $LANG['FORMITEMS_FIRST_NAME'] . " <span>- Required</span></label> <input name=\"first_name\" id=\"first_name\" type=\"text\" size=\"60\" value=\"" . $row['first_name'] . "\" /></div>
						<div><label for=\"last_name\">" . $LANG['FORMITEMS_LAST_NAME'] . " <span>- Required</span></label> <input name=\"last_name\" id=\"last_name\" type=\"text\" size=\"60\" value=\"" . $row['last_name'] . "\" /></div>
						<div><label for=\"email_address\">" . $LANG['FORMITEMS_EMAIL_ADDRESS'] . " <span>- Required</span></label> <input name=\"email_address\" id=\"email_address\" type=\"text\" size=\"60\" value=\"" . $row['email_address'] . "\" /></div>
						<div><label for=\"username\">" . $LANG['FORMITEMS_USERNAME'] . " <span>- Required</span></label> <input name=\"username\" id=\"username\" type=\"text\" size=\"60\" value=\"" . $row['username'] . "\" /></div>
						<div><label for=\"password\">" . $LANG['FORMITEMS_PASSWORD'] . " <span>- Required</span></label> <input name=\"password\" id=\"password\" type=\"password\" size=\"60\" /></div>
						<div><label for=\"password2\">" . $LANG['FORMITEMS_CONFIRM_PASSWORD'] . " <span>- Required</span></label> <input name=\"password2\" id=\"password2\" type=\"password\" size=\"60\" /></div>
						<div><label for=\"company\">" . $LANG['FORMITEMS_COMPANY'] . " </label> <input name=\"company\" id=\"company\" type=\"text\" size=\"60\" value=\"" . $row['company'] . "\" /></div>
						<div><label for=\"website\">" . $LANG['FORMITEMS_WEBSITE'] . " </label> <input name=\"website\" id=\"website\" type=\"text\" size=\"60\" value=\"" . $row['website'] . "\" /></div>
						<div><label for=\"userlevel\">" . $LANG['FORMITEMS_USER_LEVEL'] . " <span>- Required</span></label> " . createDropdown("userlevel", "userlevel", $row['user_level'], "") . "</div>
						<div><label for=\"language\">" . $LANG['FORMITEMS_LANGUAGE'] . " <span>- Required</span></label> " . createDropdown("languages", "language", $row['language'], "", "required") . "</div>
						<div><input type=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_UPDATE_USER'] . "\" /></div>
					</fieldset>
				</form>";
				
			mysql_free_result($result);
		}
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// edit order form
	//=================================================
	function returnEditUserFormJQuery($userID) {		
		$JQueryReadyScripts = "
			var v = jQuery(\"#editUserForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {			
					jQuery.get('ajax.php?action=editUser&id=' + " . $userID . ", $('#editUserForm').serialize(), function(data) {
  						// Update the proper div with the returned data
						$('#editUserResponse').html(data);
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}
	
	
	//=================================================
	// Create a form to allow a user to create a new account
	//
	// Used so that we can display it in many places
	//=================================================
	function printCreateAccountForm() {
		global $menuvar, $tts_config, $LANG;

		$content .= "
				<div id=\"createAccountResponse\">
				</div>
				<form name=\"createAccountForm\" id=\"createAccountForm\" action=\"" . $menuvar['REGISTER'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_CREATE_ACCOUNT'] . "</legend>
						<div><label for=\"first_name\">" . $LANG['FORMITEMS_FIRST_NAME'] . " <span>- Required</span></label> <input name=\"first_name\" id=\"first_name\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"last_name\">" . $LANG['FORMITEMS_LAST_NAME'] . " <span>- Required</span></label> <input name=\"last_name\" id=\"last_name\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"email_address\">" . $LANG['FORMITEMS_EMAIL_ADDRESS'] . " <span>- Required</span></label> <input name=\"email_address\" id=\"email_address\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"username\">" . $LANG['FORMITEMS_USERNAME'] . " <span>- Required</span></label> <input name=\"username\" id=\"username\" type=\"text\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"password\">" . $LANG['FORMITEMS_PASSWORD'] . " <span>- Required</span></label> <input name=\"password\" id=\"password\" type=\"password\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"password2\">" . $LANG['FORMITEMS_CONFIRM_PASSWORD'] . " <span>- Required</span></label> <input name=\"password2\" id=\"password2\" type=\"password\" size=\"60\" class=\"required\" /></div>
						<div><label for=\"language\">" . $LANG['FORMITEMS_LANGUAGE'] . " <span>- Required</span></label> " . createDropdown("languages", "language", "", "", "required") . "</div>
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_CREATE_ACCOUNT'] . "\" /></div>
					</fieldset>
				</form>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// create account form
	//=================================================
	function returnCreateAccountFormJQuery() {							
		$JQueryReadyScripts = "
			var v = jQuery(\"#createAccountForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				rules: {
					password2: {
						equalTo: '#password'
					}
				},
				submitHandler: function(form) {			
					jQuery.get('ajax.php?action=createAccount', $('#createAccountForm').serialize(), function(data) {
  						// Update the proper div with the returned data
						$('#createAccountResponse').html('" . progressSpinnerHTML() . "');
						$('#createAccountResponse').html(data);
						$('#createAccountResponse').effect('highlight',{},500);
						// Clear the form
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}

?>
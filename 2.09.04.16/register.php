<?php
/***************************************************************************
 *                               register.php
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

if ($actual_action == "newUser") {
	//=====================================================
	// Define variables from post data
	//=====================================================	
	$postfull_name = $_POST['full_name'];
	$postemail_address = $_POST['email_address'];
	$postusername = $_POST['username'];
	$postpassword = $_POST['password1'];
	$current_time = time();	
	
	//=====================================================
	// Strip dangerous tags
	//=====================================================	
	$postusername = keepsafe($postusername);
	$postpassword = keepsafe($postpassword);
	$postfull_name = keepsafe($postfull_name);
	$postemail_address = keepsafe($postemail_address);
	 
	$sql_email_check = mysql_query("SELECT users_email_address FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_email_address='" . $postemail_address . "'");
	$sql_username_check = mysql_query("SELECT users_username FROM `" . $USERSDBTABLEPREFIX . "users` WHERE users_username='" . $postusername . "'");
	 
	$email_check = mysql_num_rows($sql_email_check);
	$username_check = mysql_num_rows($sql_username_check);
	 
	if(($email_check > 0) || ($username_check > 0)){
		$content .= $T_FIX_ERRORS . "<br />";
		if($email_check > 0){
			$content .= $T_TAKEN_EMAIL_ADDRESS . "<br />";
			unset($postemail_address);
		}
		if($username_check > 0){
			$content .= $T_TAKEN_USERNAME . "<br />";
			unset($postusername);
		}
	}
	else {
		//=====================================================
		// Everything has passed both error checks that we 
		// have done. It's time to create the account!
		//=====================================================
	
		$db_password = md5($postpassword);
		$activationCode = md5($postpassword . $current_time);
		
		// generate SQL.
		$sql = "INSERT INTO `" . $USERSDBTABLEPREFIX . "users` (users_full_name, users_email_address, users_username, users_password, users_signup_date) VALUES('" . $postfull_name . "', '" . $postemail_address . "', '" . $postusername . "', '" . $db_password . "', '" . $current_time . "')";
		$result = mysql_query($sql);
		
		if(!$result){
			$content .= $T_CREATION_ERROR;
		}
		else {
			$content .= $T_WELCOME . " " . $postfull_name . ",<br />
							" . $T_THANK_YOU_FOR_REGISTERING . "<br />
							<br />
							" . $T_LOGIN_WITH_FOLLOWING . "<br />
							<br />
							" . $T_USERNAME . ": " . $postusername . "<br />
							" . $T_PASSWORD . ": " . $postpassword . "<br />
	
							<style>
								#formHolder {
									display: none;
								}
							</style>";
		}
	}
	unset($_POST['submit']);
}
	$content .= " 
	<div id=\"formHolder\">
		<form id=\"newUserForm\" name=\"newUserForm\" method=\"post\" action=\"" . $menuvar['REGISTER'] . "&action=newUser\">
			<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\">
				<tr class=\"title1\">
					<td colspan=\"2\">" . $T_New_Account. "</td>
				</tr>
				<tr> 
					<td class=\"title2\">" . $T_Desired_Username . "</td>
					<td class=\"row1\"><div id=\"usernameCheckerHolder\" style=\"float: right;\"><a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.getElementById(username).value, {asynchronous:true});\">[Check]</a></div><input name=\"username\" type=\"text\" size=\"60\" id=\"username\" class=\"required validate-alphanum\" title=\"" . $T_REQUIRED_USERNAME . "\" value=\"" . keeptasafe($_POST['username']) . "\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">" . $T_PASSWORD . "</td>
					<td class=\"row1\"><input name=\"password1\" type=\"password\" size=\"60\" id=\"password1\" class=\"required validate-password\" value=\"\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">" . $T_CONFIRM_PASSWORD . "</td>
					<td class=\"row1\"><input name=\"password2\" type=\"password\" size=\"60\" id=\"password2\" class=\"required validate-password-confirm\" value=\"\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">" . $T_EMAIL_ADDRESS . "</td>
					<td class=\"row1\"><div id=\"emailaddressCheckerHolder\" style=\"float: right;\"><a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.getElementById(email_address).value, {asynchronous:true});\">[Check]</a></div><input name=\"email_address\" type=\"text\" size=\"60\" id=\"email_address\" class=\"required validate-email\" value=\"" . keeptasafe($_POST['email_address']) . "\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">" . $T_FULL_NAME . "</td>
					<td class=\"row1\"><input name=\"full_name\" type=\"text\" size=\"60\" id=\"full_name\" class=\"required\" value=\"" . keeptasafe($_POST['full_name']) . "\" /></td>
				</tr>
			</table>
			<div class=\"center\"><input type=\"submit\" class=\"button\" name=\"submit\" value=\"" . $T_CREATE_ACCOUNT . "\" /></div>
		</form>
	</div>
	<script type=\"text/javascript\">
		var valid = new Validation('newUserForm', {immediate : true, useTitles:true});
						Validation.addAllThese([
							['validate-password', '" . $T_PASSWORD_POLICY . "', {
								minLength : 7,
								notOneOf : ['password','PASSWORD','1234567','0123456'],
								notEqualToField : 'username'
							}],
							['validate-password-confirm', '" . $T_PASSWORDS_DONT_MATCH . "', {
								equalToField : 'password1'
							}]
						]);
	</script>";	

$page->setTemplateVar("PageContent", $content);
?>
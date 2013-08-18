<?
/***************************************************************************
 *                               join.php
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
include 'includes/header.php';
if (isset($_POST[submit])) {
	// Define REQUEST fields into simple variables
	$username = $_POST['username'];
	$password = $_POST['pass1'];
	$password2 = $_POST['pass2'];
	$email_address = $_POST['email'];
	
	// strip away any dangerous tags
	$username = keepsafe($username);
	$password = keepsafe($password);
	$password2 = keepsafe($password2);
	$email_address = keepsafe($email_address);
	$current_time = time();
	
	
	/* Do some error checking on the form REQUESTed fields */	
	if(!$username){
		echo "$T_REQUIRED_USERNAME<br />";
		exit(); // if the error checking has failed, we'll exit the script!
	}
	
	$sql_username_check = mysql_query("SELECT users_username FROM `" . $DBTABLEPREFIX . "users` WHERE users_username='$username'");
	$username_check = mysql_num_rows($sql_username_check);
	 
	if($username_check > 0){
		echo "$T_TAKEN_USERNAM<br />";
		unset($username);
	 	exit();  // exit the script so that we do not create this account!
	 }
	 
	/* Everything has passed both error checks that we have done.
	It's time to create the account! */
	
	$db_password = md5($password);
	
	// Enter info into the Database.
	$sql = mysql_query("INSERT INTO `" . $DBTABLEPREFIX . "users` (users_username, users_password, users_email_address, users_signup_date)	VALUES('$username', '$db_password', '$email_address', '$current_time')") or die (mysql_error());
	
	if(!$sql){
		echo $T_CREATION_ERROR;
	} 
	else {
		$users_user_id = mysql_insert_id();
		$message = "
			$T_WELCOME $username,
			$T_THANK_YOU_FOR_REGISTERING
			
			$T_LOGIN_WITH_FOLLOWING
			
			$T_USERNAME: $username
			$T_PASSWORD: $password";
	
		echo '<pre>';
		echo $message;
		echo '</pre>';
	}
}
else {
	echo "\n<center>";
	echo "\n<form name=\"frmJoin\" method=\"post\" action=\"$menuvar[JOIN]\" onSubmit=\"return ValidateForm()\">";
	echo "\n		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"data-table-small\">";
	echo "\n			<tr>";
	echo "\n				<td  colspan=\"2\" class=\"datatable-header1\">User Info</td>";
	echo "\n			</tr>";
	echo "\n			<tr>";
	echo "\n				<td class=\"datatable-header2\" colspan=\"2\">This info is mandatory, once submitted, you will be given a password. Please fill out all fields honestly.</td>";
	echo "\n			</tr>";
	echo "\n			<tr>"; 
	echo "\n				<td class=\"datatable-row2\">$T_USERNAME:</td>";
	echo "\n				<td class=\"datatable-row1\"><input name=\"username\" type=\"text\" id=\"username\" value=\"\"></td>";
	echo "\n			</tr>";
	echo "\n			<tr>";
	echo "\n				<td class=\"datatable-row2\">$T_PASSWORD:</td>";
	echo "\n				<td class=\"datatable-row1\"><input name=\"pass1\" type=\"password\" id=\"pass1\" value=\"\"></td>";
	echo "\n			</tr>";
	echo "\n			<tr>";
	echo "\n				<td class=\"datatable-row2\">$T_CONFIRM_PASSWORD:</td>";
	echo "\n				<td class=\"datatable-row1\"><input name=\"pass2\" type=\"password\" id=\"pass2\" value=\"\"></td>";
	echo "\n			</tr>";
	echo "\n			<tr>";
	echo "\n				<td class=\"datatable-row2\">$T_EMAIL_ADDRESS:</td>";
	echo "\n				<td class=\"datatable-row1\"><input name=\"email\" type=\"text\" id=\"email\" value=\"\"></td>";
	echo "\n			</tr>";
	echo "\n		</table>";
	echo "\n		<br />";
	echo "\n		<input class=\"button\" type=\"submit\" name=\"submit\" value=\"$T_CREATE_ACCOUNT\">";
	echo "\n</form>";
	echo "\n</center>";
}
?>
<script language = "Javascript" type="text/javascript">
	function ValidateForm(){
		var pass1=document.frmJoin.pass1
		var pass2=document.frmJoin.pass2
		
		if ((pass1.value==null) || (pass2.value=="") || (pass1.value != pass2.value)){
			alert("<?= $T_PASSWORDS_DONT_MATCH; ?>")
			pass2.value = ""
			pass1.value = ""
			pass1.focus()
			return false
		}
		return true
	 }
</script>

<?
include 'includes/footer.php';
?>
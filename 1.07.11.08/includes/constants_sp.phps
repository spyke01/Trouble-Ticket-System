<?php
/***************************************************************************
 *                               constants_sp.php
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

if ( !defined('IN_TTS') )
{
	die("Hacking attempt");
}

//============================
// Text values in Spanish
//============================
/*Globally Used Values*/
	/*~~Navigation~~*/
		$T_HOME = "Home";
		$T_REGISTER = "Register";
		$T_LOGIN = "Login";
		$T_LOGOUT = "Logout";
		$T_MY_TICKETS = "My Tickets";
		$T_ADMIN_PANEL = "Admin Panel";
		$T_USER_ADMINISTRATION = "User Administration";
		$T_CALL_HOME = "Call Home";
		$T_CONFIGURATION = "Configuration";
	
	/*~~General~~*/
		$T_PLEASE = "Please";
		$T_ALL = "All";
		$T_TICKETS = "Tickets";
		$T_DISPLAY = "Display";
	
	/*~~Logins, Joining, and User Administration~~*/
		$T_USERNAME = "Username";
		$T_PASSWORD = "Password";
		$T_CONFIRM_PASSWORD = "Confirm Password";
		$T_NEW_PASSWORD = "New Password";
		$T_CONFIRM_NEW_PASSWORD = "Confirm New Password";
		$T_EMAIL_ADDRESS = "Email Address";
		$T_USER_LEVEL = "Access Level";
		$T_ADD_USER = "Add a new user";
		$T_EDIT_USER = "Edit a current user";
		$T_DELETE_USER = "Delete a current user";
	
	/*~~Ticket Tables~~*/
		$T_OPEN = "Open";
		$T_CLOSED = "Closed";
		$T_NEW_TROUBLE_TICKET = "New Trouble Ticket";
		$T_CHANGE = "Chg";
		$T_DELETE = "Del";
		$T_TECHNICIAN = "Tech";
		$T_USER = "User";
		$T_STATUS = "Status";
		$T_TITLE = "Title";
		$T_CATEGORY = "Category";
		$T_DATE_CREATED = "Date Created";
	
	/*~~Ticket Forms~~*/
		$T_NAME = "Name";
		$T_TICKET_TITLE = "Ticket Title";
		$T_PHONE_NUMBER = "Phone Number";
		$T_MODEL_NAME = "Model Name";
		$T_SERIAL_NUMBER = "Serial Number";
		$T_PROBLEM_CATEGORY = "Problem Category";
		$T_CREATE_TICKET = "Create Ticket";
	
	/*~~Errors~~*/
		$T_NOT_AUTHORIZED = "You are not Authorized to View This Section.";
		$T_ENTER_USERNAME = "Please Enter the User's Name For whom You Are Creating the Ticket.";
		$T_ENTER_TITLE = "Please Enter a Title for the Ticket You Are Creating.";
		$T_ENTER_PHONE_NUMBER = "Please Enter your Phone Number.";
		$T_ENTER_MODEL_NAME = "Please Enter Your Model Name.";
		$T_ENTER_SERIAL_NUMBER = "Please Enter The Serial Number Found on the Bottom of Your Product.";
		$T_ENTER_PROBLEM = "Please Describe Your Problem in Detail Inside the Box Provided.";
		$T_NO_TICKET_FOUND = "No tickets were found. Please create one and try again";
		$T_REQUIRED_USERNAME = "Desired Username is a required field. Please try again.";
		$T_TAKEN_USERNAME = "The username you have selected has already been used by another member in our database. Please choose a different Username!";
		$T_CREATION_ERROR = "There has been an error creating your account. Please contact the webmaster.";
		$T_PASSWORDS_DONT_MATCH = "Your passwords do not match, please re-enter them.";
		$T_ENTER_ALL_INFO = "Please enter ALL of the required information! <br />";
		$T_COULD_NOT_LOGIN = "You could not be logged in! Either the username and password do not match or you have not validated your membership!";
		$T_TRY_AGAIN = "Please try again!";
		$T_ADD_USER_ERROR = "There was an error while creating your new user. You are being redirected to the main page.";
		$T_EDIT_USER_ERROR = "There was an error while accessing the user's details you are trying to update. You are being redirected to the main page.";
		$T_ADD_PCAT_ERROR = "There was an error while creating your new problem category. You are being redirected to the main page.";
		$T_EDIT_PCAT_ERROR = "There was an error while accessing the problem category you are trying to update. You are being redirected to the main page.";
		$T_INSERTION_ERROR = "ERROR: SQL query to insert has failed";
		$T_DELETION_ERROR = "ERROR: SQL query to delete has failed";
		$T_UPDATE_ERROR = "ERROR: SQL query to update has failed";

/*Values for admin.php*/
	$T_UPDATE = "UPDATE";
	$T_SEARCH = "Search Tickets";
	$T_TICKET_NUMBER = "Ticket #";
	$T_SEARCH_WARNING = "Search the database by Username, Ticket Number, <strong>OR</strong> Tech Name Not All Three!";
	$T_SEARCH = "Search";
	$T_VIEW_ALL_TICKETS = "View All Tickets";
	$T_VIEW_ALL_OPEN_TICKETS = "View Open Tickets";
	$T_VIEW_ALL_CLOSED_TICKETS = "View Closed Tickets";

/*Values for config.php*/
	$T_ADD_PCAT_SUCCESS = "Your new problem category has been added, and you are being redirected to the main page.";
	$T_EDIT_PCAT_SUCCESS = "Your problem category have been updated, and you are being redirected to the main page.";
	$T_ADD_PCAT_BUTTON = "Create Problem Category";
	$T_EDIT_PCAT_BUTTON = "Change It!";
	$T_PROBLEM_CATEGORIES = "Problem Categories";
	$T_PROBLEM_CATEGORY_NAME = "Category Name";
	$T_TTS_SETTINGS = "Trouble Ticket System Settigns";
	$T_TTS_DEFAULT_LANGUAGE = "Default Language";
	$T_CHANGE_LANGUAGE = "Change Language";
	
/*Values for footer.php*/
	$T_POWERED_BY = "Powered By";
	$T_FTSTTS = "Fast Track Sites Trouble Ticket System";
	$T_COPYRIGHT = "Copyright";
	$T_FTS = "Fast Track Sites";
	
/*Values for header.php*/
	$T_WELCOME_BACK = "Welcome Back";
	$T_WELCOME_GUEST = "Welcome Guest";
	
/*Values for index.php*/
	$T_CURRENT_TICKETS = "Current Tickets";
	
/*Values for join.php*/
	$T_WELCOME = "Welcome";
	$T_THANK_YOU_FOR_REGISTERING = "Thank you for registering!";
	$T_LOGIN_WITH_FOLLOWING = "You can now login with the following information:";
	$T_CREATE_ACCOUNT = "Create Account";

/*Values for login.php*/
	$T_NOW_LOGGED_IN_MSG = "You are now logged in as";
	$T_AND_BEING_REDIRECTED_MAIN = "and are being redirected to the main page.";
	$T_STAY_LOGGED_IN = "Stay logged in";
	
/*Values for post.php*/
	$T_REPLY_ADDED = "Your reply has been posted, and you are being redirected to the ticket.";
	$T_TICKET_CREATED = "Your ticket has been created, and you are being redirected to it.";
	$T_STATUS_CHANGED_OPEN = "The ticket has been reopened, and you are being redirected to it.";
	$T_STATUS_CHANGED_CLOSED = "The ticket has been closed, and you are being redirected to it.";
	
/*Values for users.php*/
	$T_ADD_USER_SUCCESS = "Your new user has been added, and you are being redirected to the main page.";
	$T_EDIT_USER_SUCCESS = "Your user's details have been updated, and you are being redirected to the main page.";
	$T_ADD_USER_BUTTON = "Create User";
	$T_EDIT_USER_BUTTON = "Change It!";
	$T_CURRENT_USERS = "Current Users";

/*Values for viewticket.php*/
	$T_NO_TICKET_FOUND_BY_ID = "No tickets were found by this id. Please try again";
	$T_NO_TICKET_DATA_FOUND = "There was an error while attempting to get the contents of your ticket, please contact an administrator at sales@fasttracksites.com";
	$T_UPDATE_TICKET = "Update Ticket";
	$T_REPLY = "Reply";
	$T_SUBMIT_REPLY = "Submit Reply";

?>
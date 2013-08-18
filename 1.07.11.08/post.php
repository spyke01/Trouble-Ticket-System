<? 
/***************************************************************************
 *                               post.php
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

//get variables from posted form
$action = $_GET['action'];
$tid = $_GET['tid'];
$pid = $_GET['pid'];
$usersname = $_POST['usersname'];
$tickettitle = $_POST['tickettitle'];
$phonenumber = $_POST['phonenumber'];
$modelname = $_POST['modelname'];
$serialnumber = $_POST['serialnumber'];
$typeofproblem = $_POST['typeofproblem'];
$problem = trim($_POST['problem']);
$status = trim($_POST['status']);

//Make safe in case user tried hacking board
$action = parseurl($action);
$tid = parseurl($tid);
$pid = parseurl($pid);
$usersname = parseurl($usersname);
$tickettitle = keeptasafe($tickettitle);
$phonenumber = keeptasafe($phonenumber);
$modelname = keeptasafe($modelname);
$serialnumber = keeptasafe($serialnumber);
$typeofproblem = keeptasafe($typeofproblem);
$problem = keeptasafe($problem);
$status = keeptasafe($status);

$current_time = time();


//reply to ticket function
if ($action == 'reply' && $_SESSION[userid]) {

	$user = $_SESSION['username'];

    $sql = "INSERT INTO `" . $DBTABLEPREFIX . "entries` (entries_ticket_id, entries_reply_to, entries_date, entries_user, entries_text)".
          "VALUES('$tid', '$pid', '$current_time', '$user', '$problem')";
    mysql_query($sql) or die($T_INSERTION_ERROR . $sql);
	
	//confirm
 	echo $T_REPLY_ADDED; 
    echo "<meta http-equiv='refresh' content='1;url=view_ticket.php?id=$tid'>";
}

//newticket function
if ($action == 'newticket' && $_SESSION[userid]) {

	if(isset($usersname) && $_SESSION[user_level] != USER && $usersname != '') {
		$user = $usersname;
	}
	else { $user = $_SESSION['username']; }
	
	
    $sql = "INSERT INTO `" . $DBTABLEPREFIX . "tickets` (tickets_title, tickets_opened, tickets_user, tickets_phone_number, tickets_system, tickets_serial, tickets_problem_type)".
          "VALUES('$tickettitle', '$current_time', '$user', '$modelname', '$phonenumber', '$serialnumber', '$typeofproblem')";
    mysql_query($sql) or die($T_INSERTION_ERROR . $sql);
    
    $tickets_id = mysql_insert_id();
    
    $sql = "INSERT INTO `" . $DBTABLEPREFIX . "entries` (entries_ticket_id, entries_reply_to, entries_date, entries_user, entries_text)".
          "VALUES('$tickets_id', '0', '$current_time', '$user', '$problem')";
    mysql_query($sql) or die($T_INSERTION_ERROR . $sql);
    
	// Send Email
	$to  = ADMIN_TICKET_EMAILER; 
		
	// subject
	$subject = 'FTSTTS: New Ticket Submitted';
		
	// message
	$message = "A new ticket has been submitted, information as follows: <br />\n<br /><br />\n\n";
	$message .= "<strong>$T_USERNAME:</strong> $user<br />\n";
	$message .= "<strong>$T_PROBLEM_CATEGORY:</strong> " . getProblemCatName($typeofproblem) . "<br />\n";
	$message .= "<strong>$T_PHONE_NUMBER:</strong> $phonenumber<br />\n";
	$message .= "<strong>$T_MODEL_NAME:</strong> $modelname<br />\n";
	$message .= "<strong>$T_SERIAL_NUMBER:</strong> $serialnumber<br />\n<br />\n";
	$message .= nl2br($problem) . "<br />\n";
		
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
	// Additional headers
	$headers .= 'To: ' . $to . "\r\n";
	$headers .= 'From: noreply@fasttracksites.com' . "\r\n";
	
	// Mail it
	mail($to, $subject, $message, $headers);
    
    //confirm
    echo $T_TICKET_CREATED;
    echo "<meta http-equiv='refresh' content='1;url=view_ticket.php?id=$tickets_id'>";
}
//changestatus function
if ($action == 'changestatus' && $_SESSION[user_level] != USER) {
	if ($status == TICKET_CLOSED) {
		/*close ticket*/
		$sql = "UPDATE `" . $DBTABLEPREFIX . "tickets` SET tickets_status='" . TICKET_CLOSED . "' WHERE tickets_id = '$tid' ";
		mysql_query($sql) or die($T_UPDATE_ERROR . $sql);
		    	    
		echo $T_STATUS_CHANGED_CLOSED;
		echo "<meta http-equiv='refresh' content='1;url=view_ticket.php?id=$tid'>";
	}
	else {
		/*open ticket*/
		$sql = "UPDATE `" . $DBTABLEPREFIX . "tickets` SET tickets_status='" . TICKET_OPEN . "' WHERE tickets_id = '$tid' ";
		mysql_query($sql) or die($T_UPDATE_ERROR . $sql);
		    	    
		echo $T_STATUS_CHANGED_OPEN;
		echo "<meta http-equiv='refresh' content='1;url=view_ticket.php?id=$tid'>";
 	}
}

include 'includes/footer.php';
?>
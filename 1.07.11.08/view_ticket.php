<?
/***************************************************************************
 *                               view_ticket.php
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
	include('includes/header.php');
	$ticket_id = $_GET[id];
	$shownTicketVars = 0;
	
	if ($_SESSION[user_level] != USER) {

		echo "\n<form method='POST' action='$menuvar[POST]?action=changestatus&tid=$ticket_id' name='adminform'>";
	}	
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		
				if ($_SESSION[username]) {
					$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_id='$ticket_id' LIMIT 1";
					$result = mysql_query($sql);	
					if (mysql_num_rows($result) == 0) {
						echo "\n	<tr>";
				    	echo "\n		<td class=\"datatable-row1\" colspan=\"4\">$T_NO_TICKET_FOUND_BY_ID</td>";
						echo "\n	</tr>";
					}	
					else {
						while ($row = mysql_fetch_array($result)) {
							extract($row);
							
							echo "\n	<tr>";
							echo "\n		<td class=\"datatable-header1\" colspan='2'>$tickets_title</td>";
							echo "\n	</tr>";
							echo "\n	<tr>";
							echo "\n   	 <td class=\"datatable-header2\"><div style=\"float: right;\">";
							if ($_SESSION[user_level] != USER) {
								echo "\n<select name=\"status\">
			    							<option value=\"0\"" . isselected($tickets_status, TICKET_OPEN) . ">$T_OPEN</option>
							    			<option value=\"1\"" . isselected($tickets_status, TICKET_CLOSED) . ">$T_CLOSED</option>
							   			</select>";
							}		
							else {
							   	if ($tickets_status == TICKET_OPEN) {
								 	echo "<span color='green'><strong>$T_OPEN</strong></span>";
								 }
								 else {
								 	echo "<span color='red'><strong>$T_CLOSED</strong></span>";
								}								
							}
							echo "\n 	   </div><strong>$T_STATUS:</strong></td>";
							echo "\n	</tr>";
						}	
					}
					mysql_free_result($result);
				
					$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "entries` WHERE entries_ticket_id='$ticket_id' ORDER BY entries_reply_to ASC";
					$result = mysql_query($sql);	
					if (mysql_num_rows($result) == 0) {
						echo "\n	<tr>";
				    	echo "\n		<td class=\"datatable-row1\" colspan='3'>$T_NO_TICKET_DATA_FOUND</td>";
						echo "\n	</tr>";
					}	
					else {
						while ($row = mysql_fetch_array($result)) {
							extract($row);

							echo "\n	<tr>";
							echo "\n		<td class=\"datatable-row2\" colspan=\"2\">";
							echo "\n			<div style=\"float: right;\">" . makeDate($entries_date) . "</div>";
							echo "\n			$entries_user";
							if ($shownTicketVars == 0) { 
								echo "\n			<br /><br /><strong>$T_PROBLEM_CATEGORY:</strong> " . getProblemCatName($tickets_problem_type) . "<br /><strong>$T_PHONE_NUMBER:</strong> $tickets_phone_number<br /><strong>$T_MODEL_NAME:</strong> $tickets_model_name<br /><strong>$T_SERIAL_NUMBER:</strong> $tickets_serial"; 
								$shownTicketVars = 1;
							}
							echo "\n		</td>";
							echo "\n	</tr>";
							echo "\n	<tr>";
							echo "\n		<td class=\"datatable-row1\" colspan='2'>";
									echo nl2br($entries_text);
							echo "\n		</td>";
							echo "\n	</tr>";
							$pid = $entries_id;
						}	
					}
					mysql_free_result($result);
				}
				else {
					echo "\n	<tr>";
				    echo "\n		<td class=\"datatable-row1\" colspan='3'>$T_PLEASE <a href='$menuvar[LOGIN]'>$T_LOGIN</a>.</td>";
					echo "\n	</tr>";
				}
		echo "\n	<tr>";
		echo "\n	    <td colspan='2'></td>";
		echo "\n	</tr>";
		echo "\n</table>";
			if ($_SESSION['user_level'] != USER) {
				echo "\n<br /><center><input class='button' type='submit' name='updateform' value='$T_UPDATE_TICKET'></center>";
				echo "\n</form>";
			}
		echo "\n<br />";
		echo "\n<form method='POST' action='$menuvar[POST]?action=reply&tid=$ticket_id&pid=$pid' name='frmReplyTicket'>";
		echo "\n	<table border='0' cellpadding='0' cellspacing='0' class=\"data-table\">";
		echo "\n		<tr>";
		echo "\n	    	<td class=\"datatable-header1\">$T_REPLY</td>";
		echo "\n	  	</tr>";
		echo "\n	  	<tr>";
		echo "\n	  		<td class=\"datatable-row2\"><center><textarea cols='70' rows='8' name='problem' wrap='virtual'></textarea><br /></center></td>";
		echo "\n		</tr>";
		echo "\n	</table>";
		echo "\n	<br />";
		echo "\n	<center><input type='submit' name='submit' value='$T_SUBMIT_REPLY' class='button'></center>";
		echo "\n</form>";
	include('includes/footer.php');
?>

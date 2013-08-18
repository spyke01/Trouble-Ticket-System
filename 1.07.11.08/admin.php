<?
/***************************************************************************
 *                               admin.php
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
	
	if ($_SESSION[user_level] == ADMIN) {
		$action = $_GET['action'];
		$action = parseurl($action);
		$postusername = $_POST['username'];
		$ticketid = $_POST['ticketid'];
		$tech = $_POST['tech'];
		$limit1 = '0';
		$limit2 = '100';
		
		if(isset($_POST["limit1"])) $limit1 = $_POST["limit1"];
		if(isset($_POST["limit2"])) $limit2 = $_POST["limit2"];
			
		unset($_POST["limit1"]);
		unset($_POST["limit2"]);
			
		//=======================================
		// See if were searching the db, odering 
		// it, or just checking them all
		//=======================================
		if ($action == 'search') {
			$msg = "\n<strong><u>Search Results:</u></strong><br />";
			if (isset($_POST['username']) && $_POST['username'] != '') {
				$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_user='$postusername'";
				unset($_POST['username']);
			}
			elseif (isset($_POST['ticketid']) && $_POST['ticketid'] != '') {
				$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_id='$serialnumber'";
				unset($_POST['ticketid']);
			}
			elseif (isset($_POST['tech']) && $_POST['tech'] != '') {
				$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_tech='$tech'";
				unset($_POST['tech']);
			}
			else {
				$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT $limit1, $limit2";
			}		
		}
	
		// ************** POSSIBLE ACTION: Delete a part 
		if(isset($_POST["DelPart"]))
		{
			foreach($_POST["DelPart"] as $num => $todelete)
			{
				$num = str_replace("\\", "",str_replace("\"", "", $num));
				$sql = "DELETE FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_id = '$num' LIMIT 1";
				$result = mysql_query($sql);			
			}
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT $limit1, $limit2";
			unset($_POST['DelPart']);
		}
		// ************** POSSIBLE ACTION: Change a part 
		if(isset($_POST["ChgTicket"]))
		{
			foreach($_POST["ChgTicket"] as $itemid => $qty)
			{
				if($qty == 1)
				{
					$ticketid = $itemid;
					$status = $_POST["status"][$ticketid];
					$tech = $_POST["tech"][$ticketid];
					
					$ticketid = str_replace("\\", "",str_replace("'", "", $ticketid));
					$sql = "UPDATE `" . $DBTABLEPREFIX . "tickets` SET tickets_status = '$status', tickets_tech_id = '$tech' WHERE tickets_id = '$ticketid' LIMIT 1";
					//echo $sql;
					$result = mysql_query($sql);
				}
			}
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` LIMIT $limit1, $limit2";
			unset($_POST['ChgTicket']);
		}
	
		if ($action == 'open') { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_status='" . TICKET_OPEN . "' ORDER BY `tickets_opened` DESC LIMIT $limit1, $limit2"; }
		elseif ($action == 'closed') { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` WHERE tickets_status='" . TICKET_CLOSED . "' ORDER BY `tickets_opened` DESC LIMIT $limit1, $limit2"; }
		elseif ($action != 'search') { $sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` ORDER BY `tickets_opened` DESC LIMIT $limit1, $limit2"; }
		
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n	<tr>";
	
		if ($_SESSION[username] && $_SESSION[user_level] == ADMIN) {
			echo "\n		<td class='datatable-header2' style='text-align: center;'><a href='$PHP_SELF'>$T_VIEW_ALL_TICKETS</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=open'>$T_VIEW_ALL_OPEN_TICKETS</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=closed'>$T_VIEW_ALL_CLOSED_TICKETS</a></td>";
		}
		else {
			echo "\n		<td class='datatable-header2' style='text-align: center;'>$T_NOT_AUTHORIZED</td>";
		}
		
		echo "\n	</tr>";
		echo "\n</table>";
		echo "\n<br />";
	
		version_functions(yes);
		
		//=======================================
		// Show our search box
		//=======================================
		echo "\n<br />
				<center>
				<form name='search' action='$PHP_SELF?action=search' method='POST'>
					<table cellpadding='0' cellspacing='0' class='data-table-small'>
						<tr>
							<td class='datatable-header1' colspan='2'>$T_SEARCH</td>
						</tr>
						<tr>
							<td class='datatable-header2' colspan='2'>$T_SEARCH_WARNING</td>
						</tr>
						<tr>
							<td class='datatable-row2'><strong>$T_USER: </strong></td>
							<td class='datatable-row2'><input name='username' type='text' /></td>
						</tr>
						<tr>
							<td class='datatable-row2'><strong>$T_TICKET_NUMBER: </strong></td>
							<td class='datatable-row2'><input type='text' name='ticketid' /></td>
						</tr>
						<tr>
							<td class='datatable-row2'><strong>$T_TECHNICIAN: </strong></td>
							<td class='datatable-row2'><input type='text' name='tech' /></td>
						</tr>
					</table>
				<br />
				<input type='submit' value='$T_SEARCH' class='button' />
				</form>
				</center>";
			
		echo "\n<br />";
		echo $msg . "\n<form name='list' action='$PHP_SELF' method='POST'>$T_DISPLAY <input type=text size=4 value='$limit2' name=limit2 /> rows " .
			"starting at row <input type=text size=4 value='$limit1' name=limit1 /> <br />";
				
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>
					<tr>
						<td class='datatable-header1' colspan='7'>$T_ALL $action $T_TICKETS</td>
					</tr>
					<tr>
						<td class='datatable-header2' style='text-align: center;'>$T_CHANGE</td>
						<td class='datatable-header2' style='text-align: center;'>$T_DELETE</td>
						<td class='datatable-header2' style='text-align: center;'>$T_TITLE</td>
						<td class='datatable-header2' style='text-align: center;'>$T_USER</td>
						<td class='datatable-header2' style='text-align: center;'>$T_TECHNICIAN</td>
						<td class='datatable-header2' style='text-align: center;'>$T_DATE_CREATED</td>
						<td class='datatable-header2' style='text-align: center;'>$T_STATUS</td>
					</tr>";
				
		if ($_SESSION[username] && $_SESSION[user_level] == ADMIN) {
			$result = mysql_query($sql);	
			if (mysql_num_rows($result) == 0) {
				echo "\n<tr>";
		    	echo "\n	<td class='datatable-row2' colspan='7'>$T_NO_TICKET_FOUND</td>";
				echo "\n</tr>";
			}	
			else {
				while ($row = mysql_fetch_array($result)) {
					extract($row);
					$status = ($tickets_status == TICKET_OPEN) ? "open" : "closed";
		   			//$ticketstatus = ($tickets_status == TICKET_OPEN) ? $T_OPEN : $T_CLOSED;
	    			$title = ($tickets_title == "") ? "None" : $tickets_title;
		   
					echo "\n<tr>
								<td class='datatable-row1-$status'><input type=checkbox name=\"ChgTicket['$tickets_id']\" id=\"ChgTicket['$tickets_id']\" value=\"1\" /></td>
								<td class='datatable-row1-$status'><input type=checkbox name=\"DelPart['$tickets_id']\" id=\"DelPart['$tickets_id']\" value=\"1\" /></td>							
					    		<td class='datatable-row1-$status'><a href='$menuvar[VIEWTICKET]?id=$tickets_id'>$title</a></td>
					    		<td class='datatable-row1-$status'>$tickets_user</td>
					    		<td class='datatable-row1-$status' style='text-align: center;'>
									<select name=\"tech['$tickets_id']\" onChange=\"checkChgTicket($tickets_id);\">
										<option value=\"\">--Select One</option>";
				  		
			$sql2 = "SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_tech='1' ORDER BY users_username";
			$result2 = mysql_query($sql2);
			
			while ($row2 = mysql_fetch_array($result2)) {
				echo "\n	  				<option value=\"$row2[users_user_id]\"" . isselected($tickets_tech_id, $row2['users_user_id']) . ">$row2[users_username]</option>";
			}
			mysql_free_result($result2); 
			
			echo "\n	  			</select>
								</td>
					    		<td class='datatable-row1-$status' style='text-align:right;'>" . makeDate($tickets_opened) . "</td>
					    		<td class='datatable-row1-$status' style='text-align: center;'>
									<select name=\"status['$tickets_id']\" onChange=\"checkChgTicket($tickets_id);\">
			    						<option value=\"0\"" . isselected($tickets_status, TICKET_OPEN) . ">$T_OPEN</option>
							    		<option value=\"1\"" . isselected($tickets_status, TICKET_CLOSED) . ">$T_CLOSED</option>
							   		</select>
								</td>
							</tr>";
				}	
			}
		}
		else {
			echo "\n<tr>
		    			<td class='datatable-row2' colspan='7'>$T_NOT_AUTHORIZED</td>
					</tr>";
		}
		echo "\n</table>
				<br />
				<center><input name='submit' type='submit' value='$T_UPDATE' class='button' /></center>
				</form>";
	
		if ($_SESSION[user_level] == ADMIN || $_SESSION[user_level] == MOD) {
			echo "\n<br />
					<form name=\"frmNewTicket\" id=\"frmNewTicket\" action=\"$menuvar[POST]?action=newticket\" method=\"post\">
					<table border='0' cellpadding='0' cellspacing='0' class='data-table'>
						<tr>
							<td class=\"datatable-header1\" colspan='2'>$T_NEW_TROUBLE_TICKET</td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_NAME: </strong></td><td class=\"datatable-row1\"><input type='text' name='usersname' size='73' /></td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_TICKET_TITLE: </strong></td><td class=\"datatable-row1\"><input type='text' id=\"tickettitle\" name=\"tickettitle\" size='73' class=\"required\" /></td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_PHONE_NUMBER: </strong></td><td class=\"datatable-row1\"><input type='text' name='phonenumber' size='73' class=\"required\" /></td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_MODEL_NAME: </strong></td><td class=\"datatable-row1\"><input type='text' name='modelname' size='73' /></td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_SERIAL_NUMBER: </strong></td><td class=\"datatable-row1\"><input type='text' name='serialnumber' size='73' /></td>
						</tr>
						<tr>
							<td class=\"datatable-row2\"><strong>$T_PROBLEM_CATEGORY: </strong></td>
						  	<td class=\"datatable-row1\">
						  		<select name='typeofproblem' class=\"validate-selection\">";
				  		
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "problemcategories` ORDER BY pcats_name";
			$result= mysql_query($sql);
			
			while ($row = mysql_fetch_array($result)) {
				echo "\n	  			<option value='$row[pcats_id]'>$row[pcats_name]</option>";
			}
			
			echo "\n	  		</select>
			  				</td>
						</tr>
						<tr>
						  <td class=\"datatable-row2\" colspan='2'><center><textarea cols='70' rows='8' name='problem' wrap='virtual' class=\"required\"></textarea><br /></center></td>
						</tr>		
					</table>	
					<br />
					<center><input type='submit' name='submit' value='$T_CREATE_TICKET' class='button' /></center>	
					</form>";
?>	
			<script type="text/javascript">
				var valid = new Validation('frmNewTicket', {immediate : true, useTitles:true});
				
				function checkChgTicket(num) {
			    	 if(document.all) {
			    	    eval("document.all[\"ChgTicket['" + num + "']\"].checked = 1;");
			    	 }
				 	else if(document.getElementById) {
				        eval("document.getElementById(\"ChgTicket['" + num + "']\").checked = 1;");
				     }
				}
			</script>
<?
		}
	}
	else {
		echo $T_NOT_AUTHORIZED;
	}
	include('includes/footer.php');
?>


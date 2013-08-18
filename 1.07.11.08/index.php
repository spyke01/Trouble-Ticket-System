<?
/***************************************************************************
 *                               index.php
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
	echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
	echo "\n	<tr>";
	echo "\n		<td class=\"datatable-header1\" colspan=\"4\">$T_CURRENT_TICKETS</td>";
	echo "\n	</tr>";
	echo "\n	<tr>";
	echo "\n		<td class=\"datatable-header2\" width=\"250\">$T_TITLE</td>";
	echo "\n		<td class=\"datatable-header2\" width=\"250\">$T_CATEGORY</td>";
	echo "\n		<td class=\"datatable-header2\" width=\"250\">$T_DATE_CREATED</td>";
	echo "\n		<td class=\"datatable-header2\" width=\"48\">$T_STATUS</td>";			
	echo "\n	</tr>";	
	 
	if ($_SESSION[username]) {
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "tickets` t, `" . $DBTABLEPREFIX . "problemcategories` p WHERE t.tickets_user='$_SESSION[username]' AND t.tickets_problem_type = p.pcats_id GROUP BY t.tickets_opened DESC ORDER BY t.tickets_status";
		$result = mysql_query($sql);	
		if (mysql_num_rows($result) == 0) {
			echo "\n	<tr>";
	    	echo "\n		<td class=\"datatable-row1\" colspan=\"4\">$T_NO_TICKET_FOUND</td>";
			echo "\n	</tr>";
		}	
		else {
			while ($row = mysql_fetch_array($result)) {
				extract($row);
	    		$status = ($tickets_status == TICKET_OPEN) ? "open" : "closed";
	    		$ticketstatus = ($tickets_status == TICKET_OPEN) ? $T_OPEN : $T_CLOSED;
	    		$title = ($tickets_title == "") ? "None" : $tickets_title;
		
				echo "\n	<tr>";
		    	echo "\n		<td class=\"datatable-row1-$status\"><a href='$menuvar[VIEWTICKET]?id=$tickets_id'>$title</a></td>";
		    	echo "\n		<td class=\"datatable-row1-$status\">$pcats_name</td>";
		    	echo "\n		<td class=\"datatable-row1-$status\">" . makeDate($tickets_opened) . "</td>";
		    	echo "\n		<td class=\"datatable-row1-$status\">$ticketstatus</td>";
				echo "\n	</tr>";
			}	
		}
	}
	else {
		echo "\n	<tr class='colour2'>";
	    echo "\n		<td class=\"datatable-row1\" colspan=\"4\">$T_PLEASE <a href='$menuvar[LOGIN]'>$T_LOGIN</a>.</td>";
		echo "\n	</tr>";
	}
	echo "\n</table>";
	echo "\n<br />";
	
	if ($_SESSION[username]) {
		echo "\n<form name=\"frmNewTicket\" id=\"frmNewTicket\" action=\"$menuvar[POST]?action=newticket\" method=\"post\">";
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-header1\" colspan='2'>$T_NEW_TROUBLE_TICKET</td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_NAME: </strong></td><td class=\"datatable-row1\">$_SESSION[username]</td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_TICKET_TITLE: </strong></td><td class=\"datatable-row1\"><input type='text' id=\"tickettitle\" name=\"tickettitle\" size='73' class=\"required\" /></td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_PHONE_NUMBER: </strong></td><td class=\"datatable-row1\"><input type='text' name='phonenumber' size='73' class=\"required\" /></td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_MODEL_NAME: </strong></td><td class=\"datatable-row1\"><input type='text' name='modelname' size='73' /></td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_SERIAL_NUMBER: </strong></td><td class=\"datatable-row1\"><input type='text' name='serialnumber' size='73' /></td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n		<td class=\"datatable-row2\"><strong>$T_PROBLEM_CATEGORY: </strong></td>";
		echo "\n	  	<td class=\"datatable-row1\">";
		echo "\n	  		<select name='typeofproblem' class=\"validate-selection\">";
			  		
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "problemcategories` ORDER BY pcats_name";
		$result= mysql_query($sql);
		
		while ($row = mysql_fetch_array($result)) {
			echo "\n	  			<option value='$row[pcats_id]'>$row[pcats_name]</option>";
		}
		
		echo "\n	  		</select>";
		echo "\n	  	</td>";
		echo "\n	</tr>";
		echo "\n	<tr>";
		echo "\n	  <td class=\"datatable-row2\" colspan='2'><center><textarea cols='70' rows='8' name='problem' wrap='virtual' class=\"required\"></textarea><br /></center></td>";
		echo "\n	</tr>";		
		echo "\n</table>";	
		echo "\n<br />";
		echo "\n<center><input type='submit' name='submit' value='$T_CREATE_TICKET' class='button' /></center>	";
		echo "\n</form>";
	}
?>	
<script type="text/javascript">
	var valid = new Validation('frmNewTicket', {immediate : true, useTitles:true});
</script>
<?
	include('includes/footer.php');
?>


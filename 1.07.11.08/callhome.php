<?
/***************************************************************************
 *                               callhome.php
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
	
	if ($_SESSION[username] && $_SESSION[user_level] == ADMIN) {		
		//======================================================
		// Connect to the callhome database
		//======================================================
		mysql_close(); // We have to disconnect from the normal db to access the call home db, this is a security measure
		$server = "db394.perfora.net";
		$dbuser = "dbo167578165";
		$dbpass = "wNssX8X4";
		$dbname = "db167578165";
		
		$connect = mysql_connect($server,$dbuser,$dbpass);
		
		//display error if connection fails
		if ($connect==FALSE) {
		   print 'Unable to connect to database: '.mysql_error();
		   exit;
		}
		
		mysql_select_db($dbname); // select database
		
		//======================================================
		// ************** POSSIBLE ACTION: Delete an entry 
		//======================================================
		if(isset($_POST["DelEntry"]) && $_SESSION[user_level] == ADMIN)	{
			foreach($_POST["DelEntry"] as $num => $todelete) {
				$num = substr($num,2,-2);
				$sql = "DELETE FROM `callhome` WHERE callhome_id = '$num' LIMIT 1";
				$result = mysql_query($sql);			
			}
			unset($_POST['DelEntry']);
		}
			
		//======================================================
		// Get the list of call home info
		//======================================================	
		echo "\n<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n	<tr>";
		echo "\n		<td class='datatable-header2' style='text-align: center;'><a href='$PHP_SELF?action=all'>View All</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=fts'>FTS Entries</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=nonfts'>NonFTS Entries</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=free'>View Free</a> &nbsp;|&nbsp; <a href='$PHP_SELF?action=pro'>View Pro</a></td>";
		echo "\n	</tr>";
		echo "\n</table>";
		echo "\n<br />";	
		echo "\n					<center>";	
		echo "\n					<form action='$PHP_SELF' method='POST'>";
		echo "\n						<table border='0' cellpadding='0' cellspacing='0' class='data-table'>";
		echo "\n							<tr class='datatable-header1'>";
		echo "\n								<td style='text-align: left;' colspan='6'><strong>Current Apllications in the Wild</strong></td>";
		echo "\n							</tr>";
		echo "\n							<tr class='datatable-header2'>";
		echo "\n							   <td style='text-align: left;'>Delete</td><td style='text-align: left;'>Application</td><td style='text-align: left;'>Version</td><td style='text-align: left;'>License No.</td><td style='text-align: left;'>Domain</td><td style='text-align: left;'>Script Location</td>";
		echo "\n							</tr>";
		
		if ($_GET['action'] == "fts") {
			$extraSQL = "WHERE instr( callhome_website, 'fasttracksites.com' ) ORDER BY callhome_application";
		}
		elseif ($_GET['action'] == "all") {
			$extraSQL = "ORDER BY callhome_id";
		}
		elseif ($_GET['action'] == "free") {
			$extraSQL = "WHERE instr( callhome_version, 'FREE' ) OR instr( callhome_license, 'Free' ) ORDER BY callhome_id";
		}
		elseif ($_GET['action'] == "pro") {
			$extraSQL = "WHERE !instr( callhome_version, 'FREE' ) AND !instr( callhome_license, 'Free' ) ORDER BY callhome_application";
		}
		else {
			// Prints nonfts items only
			$extraSQL = "WHERE !instr( callhome_website, 'fasttracksites.com' )ORDER BY callhome_id";
		}

		$sql = "SELECT * FROM `callhome` " . $extraSQL;
		$result = mysql_query($sql);
		
		if (mysql_num_rows($result) == 0) {
			echo "\n							<tr class='datatable-row2'>";
			echo "\n							   <td style='text-align: center;' colspan='6'>There are currently no scripts in the wild.</td>";
			echo "\n							</tr>";	
		}
		else {
			$x = 1;
			while ($row = mysql_fetch_array($result)) {
				extract($row);
				echo "\n							<tr class='datatable-row" . $x . "'>";
				echo "\n							   <td style='text-align: center;'><input type=checkbox name='DelEntry[\"$callhome_id\"]' id='DelEntry[\"$callhome_id\"]' value='1' /></td><td style='text-align: left;'>$callhome_application</td><td style='text-align: left;'>$callhome_version</td><td style='text-align: left;'>$callhome_license</td><td style='text-align: left;'><a href='$callhome_website' target='blank'>$callhome_website</a></td><td style='text-align: left;'><a href='$callhome_location' target='blank'>$callhome_location</a></td>";
				echo "\n							</tr>";	
				$x = ($x == 1) ? 2 : 1;		
			}
		}
		echo "\n						</table>";
		echo "\n						<br /><input type='submit' value='Delete Selected' class='button' />";
		echo "\n					</form>";
		echo "\n					</center>";		
	}
	else {
		echo "You are not authorized to access this section of the site, and if you attempt to do so again, you may be punishable by law.<br />";
	}

	include('includes/footer.php');
?>

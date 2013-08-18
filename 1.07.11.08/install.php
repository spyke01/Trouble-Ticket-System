<? 
/***************************************************************************
 *                               install.php
 *                            -------------------
 *   begin                : Wednesday Nov 23, 2005
 *   copyright          : (C) 2005 Paden Clayton - Fast Track Sites
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
	ini_set('arg_separator.output','&amp;');
	define('IN_TTS', true);
	include 'includes/constants.php';
	include 'includes/constants_en.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><HTML>
<HEAD>
	<title>Fast Track Sites Trouble Ticket System</title>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<META http-equiv=Content-Language content=en-us>

	<!--Stylesheets Begin-->
	<link rel="stylesheet" type="text/css" href="stylesheets/main.css">
	<!--[if lte IE 6]>
		<LINK rel="stylesheet" type="text/css" href="stylesheets/iehacks.css">
	<![endif]-->
	<!--Stylesheets End-->
	<!--Javascripts Begin-->
	<!--[if lt IE 7]>
		<script src="javascripts/pngfix.js" defer type="text/javascript"></script>
	<![endif]-->	
	<!--Javascripts End-->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
					<img src="images/fts_logo_green.jpg" alt="Fast Track Sites Logo" />
				</div>
				<div id="content">

<?		

	function checkresult($result, $sql, $table) {
		global $failed;
		global $failedsql;
		global $totalfailure;
		
		if (!$result || $result == "") {
			$failed[$table] = "failed";
			$failedsql[$table] = $sql;
			$totalfailure = 1;
		}  
		else {
			$failed[$table] = "succeeded";
			$failedsql[$table] = $sql;
		}	
	}
	
	if (isset($_POST[submit])) {
		$failed = 0;
		$totalfailure = 0;
		$failed = array();
		$failedsql = array();
		
		$pass = $_POST[password]; 
		$password = md5($pass);
		$currenttime = time();
		$postusername = $_POST[username];

		$str = "<?PHP\n\n// Connect to the database\n\n\$server = \"" . $_POST[dbserver] . "\";\n\$dbuser = \"" . $_POST[dbusername] . "\";\n\$dbpass = \"" . $_POST[dbpassword] . "\";\n\$dbname = \"" . $_POST[dbname] . "\";\n\$DBTABLEPREFIX = \"" . $_POST[dbtableprefix] . "\";\n\n\$connect = mysql_connect(\$server,\$dbuser,\$dbpass);\n\n//display error if connection fails\nif (\$connect==FALSE) {\n   print 'Unable to connect to database: '.mysql_error();\n   exit;\n}\n\nmysql_select_db(\$dbname); // select database\n\n?>";
		
		$fp=fopen("_db.php","w");
		$result = fwrite($fp,$str);
		fclose($fp);		
		checkresult($result, "The installation program failed to create a connection file to your database, you will manually need to do this. Please see the readme file for more information.", "dbconnection");
	
  		include '_db.php';

    $sql = "CREATE TABLE `" . $DBTABLEPREFIX . "config` (
		  `config_name` varchar(50) NOT NULL default '',
		  `config_value` varchar(50) NOT NULL default ''
		) TYPE=MyISAM;";
    $result = mysql_query($sql);
    checkresult($result, $sql, "config");
  
    $sql = "CREATE TABLE `" . $DBTABLEPREFIX . "entries` (
			  entries_id mediumint(8) NOT NULL auto_increment,
			  entries_ticket_id mediumint(8) NOT NULL default '0',
			  entries_user_id mediumint(8) NOT NULL default '',
			  entries_date int(10) NOT NULL default '0',
			  entries_text text NOT NULL,
			  PRIMARY KEY  (entries_id)
			) TYPE=MyISAM;";
    $result = mysql_query($sql);
    checkresult($result, $sql, "entries");

    $sql = "CREATE TABLE `" . $DBTABLEPREFIX . "problemcategories` (
			  `pcats_id` mediumint(8) NOT NULL auto_increment,
			  `pcats_name` varchar(250) NOT NULL default '',
			  PRIMARY KEY  (`pcats_id`)
			) TYPE=MyISAM AUTO_INCREMENT=1;";
    $result = mysql_query($sql);
    checkresult($result, $sql, "problemcategories");

    $sql = "CREATE TABLE `" . $DBTABLEPREFIX . "tickets` (
			  tickets_id mediumint(8) NOT NULL auto_increment,
			  tickets_status int(1) NOT NULL default '0',
			  tickets_title char(50) NOT NULL default '',
			  tickets_opened int(11) NOT NULL default '0',
			  tickets_user char(50) NOT NULL default '',
			  tickets_tech_id mediumint(8) NOT NULL default '',
			  tickets_phone_number char(50) NOT NULL default '',
			  tickets_system char(50) NOT NULL default '',
			  tickets_serial char(50) NOT NULL default '',
			  tickets_problem_type char(50) NOT NULL default '',
			  PRIMARY KEY  (tickets_id)
			) TYPE=MyISAM;";
    $result = mysql_query($sql);
    checkresult($result, $sql, "tickets");

    $sql = "CREATE TABLE `" . $DBTABLEPREFIX . "users` (
			  users_user_id bigint(20) NOT NULL auto_increment,
			  users_username varchar(255) NOT NULL default '',
			  users_password varchar(255) NOT NULL default '',
			  users_email_address varchar(255) NOT NULL default '',
			  users_signup_date int(11) default NULL,
			  users_notes text NOT NULL,
			  users_tech tinyint(1) NOT NULL default '0',
			  users_user_level tinyint(1) NOT NULL default '0',
			  users_language varchar(5) NOT NULL default 'en',
			  PRIMARY KEY  (users_user_id)
			) TYPE=MyISAM AUTO_INCREMENT=2 ;";
    $result = mysql_query($sql);
    checkresult($result, $sql, "users"); 

	$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('language', 'en');";
    $result = mysql_query($sql);
    checkresult($result, $sql, "defaultlanguage");
    
	$sql = "INSERT INTO `" . $DBTABLEPREFIX . "problemcategories` VALUES (1, 'Test Category');";
    $result = mysql_query($sql);
    checkresult($result, $sql, "testcategory");

    
    $sql = "INSERT INTO `" . $DBTABLEPREFIX . "users` (`users_username`, `users_password`, `users_signup_date`, `users_notes`, `users_user_level`) VALUES ('$postusername', '$password', '', '', '1');";
    $result = mysql_query($sql);
    checkresult($result, $sql, "adminuser");
    

		if ($totalfailure == 0) { echo "\n<br /><span color='green'>Installation Completed successfully, <strong><u>Please Delete This File</u></strong>.</span><br />"; }
		else { 
			echo "\nInstallation failed, please see the explanations above"; 
			
			foreach ($failed as $table => $status) {
				echo "\nQuery for $table has ";
				if ($status == "failed") {
					echo "<span color='red'>$status</span> $failedsql[$table].<br />";
					$totalfailure = 1;
				}
				else {
					echo "<span color='green'>$status</span>.<br />";				
				}
			}					
		}
	}
	else {
		echo "\n<form action='$PHP_SELF' method='POST'>";
		echo "\n<center><table class='data-table' cellspacing='1' cellpadding='0'>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-header1' colspan='2'>";
		echo "\n				Fast Track Sites Trouble Ticket System Install";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-header2' colspan='2'>";
		echo "\n				Database Configuration";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<strong>Database Server:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<input type='text' name='dbserver' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-row2'>";
		echo "\n				<strong>Database Name:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='datatable-row2'>";
		echo "\n				<input type='text' name='dbname' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<strong>Database Username:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<input type='text' name='dbusername' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-row2'>";
		echo "\n				<strong>Database Password:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='datatable-row2'>";
		echo "\n				<input type='text' name='dbpassword' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<strong>Table Prefix:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='datatable-row1'>";
		echo "\n				<input type='text' name='dbtableprefix' value=\"TTS_\" />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='datatable-header2'>";
		echo "\n			<td colspan='2'>";
		echo "\n				General Configuration";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='datatable-row1'>";
		echo "\n			<td>";
		echo "\n				<strong>Admin Username:</strong>";
		echo "\n			</td>";
		echo "\n			<td>";
		echo "\n				<input type='text' name='username' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='datatable-row2'>";
		echo "\n			<td>";
		echo "\n				<strong>Admin Password:</strong>";
		echo "\n			</td>";
		echo "\n			<td>";
		echo "\n				<input type='text' name='password' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='datatable-header2'>";
		echo "\n			<td colspan='2'>";
		echo "\n				<center><input type='submit' name='submit' class='button' value='Submit' /></center>";			
		echo "\n			</td>";
		echo "\n	</table></center>";
		echo "\n</form>";	
	}
echo "\n 					<br />";
echo "\n				</div>";
echo "\n				<div id=\"footer\">";
echo "\n					<div style=\"float:right;\">";
echo "\n						$T_POWERED_BY: <a href=\"http://www.fasttracksites.com\">$T_FTSTTS</a>";
echo "\n					</div>";
echo "\n					$T_COPYRIGHT &copy;2005-2006 <a href=\"http://www.fasttracksites.com\">$T_FTS</a>";
echo "\n				</div>";
echo "\n			</div>";
echo "\n		</div>";
echo "\n	</body>";
echo "\n</html>";
?>
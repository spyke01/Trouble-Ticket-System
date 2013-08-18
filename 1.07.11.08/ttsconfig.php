<? 
/***************************************************************************
 *                               config.php
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
include '_db.php';

$board_config = array();

$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "config`";
$result = mysql_query($sql);


while ( $row = mysql_fetch_array($result) )
{
	$tts_config[$row['config_name']] = $row['config_value'];
}



?>

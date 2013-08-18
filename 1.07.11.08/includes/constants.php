<?php
/***************************************************************************
 *                               constants.php
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
// Email for new tickets
//============================
define('ADMIN_TICKET_EMAILER', 'sales@fasttracksites.com');

//============================
// User Levels
//============================
define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);
define('BANNED', 3);

//============================
// Ticket state
//============================
define('TICKET_OPEN', 0);
define('TICKET_CLOSED', 1);

//============================
// Languages
//============================
$LANGUAGES = array();
$LANGUAGES[en] = "English";
$LANGUAGES[it] = "Italian";
$LANGUAGES[sp] = "Spanish";

//=====================================================
// Application
//=====================================================
$A_Name = "fts_tts";
$A_Version = "1.07.11.08";
$A_License = "Pro Edition";
$A_Licensed_To = "Fast Track Sites";
?>
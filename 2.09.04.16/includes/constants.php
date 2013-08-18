<?PHP 
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is also licensed under a program license located inside 
 * the license.txt file included with this program. This license overrides
 * all license statements made in the previous license reveference. 
 ***************************************************************************/

//=====================================================
// Debug Level
//=====================================================
//define('DEBUG', 1); // Debugging on
define('DEBUG', 0); // Debugging off

//=====================================================
// Global state
//=====================================================
define('ACTIVE', 1);
define('INACTIVE', 0);

//============================
// Ticket state
//============================
define('TICKET_OPEN', 0);
define('TICKET_CLOSED', 1);

//============================
// Languages
//============================
$LANGUAGES = array();
$LANGUAGES['en'] = "English";
$LANGUAGES['it'] = "Italian";
//$LANGUAGES['sp'] = "Spanish";

//=====================================================
// User Levels <- Do not change these values!!
//=====================================================
define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);
define('BANNED', 3);

//=====================================================
// Application
//=====================================================
$A_Name = "fts_tts";
$A_Version = "2.09.04.16";
?>
<?php
/***************************************************************************
 *                               menu.php
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
	die("You are attempting to access, or hack parts of my website, that you do not have permission to, please do not try this again.");
}

session_start();  // Start Session

function append_vars($url)
{
	return $url;
}

function append_vars_new_menu($url, $menu2)
{
	return $url;
}



$menuvar = array(

/*###############################
#       Menu Variables          #
###############################*/
'ADMIN' => append_vars('admin.php'),
'CONFIG' => append_vars('config.php'),
'CALLHOME' => append_vars('callhome.php'),
'HOME' => append_vars('index.php'),
'JOIN' => append_vars('join.php'),
'LOGIN' => append_vars('login.php'),
'LOGOUT' => 'login.php?action=logout',
'POST' => append_vars('post.php'),
'REGISTER' => append_vars('register.php'),
'SWITCHER' => append_vars('switcher.php'),
'USERS' => append_vars('users.php'),
'VIEWTICKET' => append_vars('view_ticket.php'),


/*###############################
#       Dead Variable          #
###############################*/
'dead' => append_vars('dead.php')
);
?>
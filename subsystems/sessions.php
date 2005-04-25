<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

if (!defined('PATHOS')) exit('');

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Sessions
 */
define('SYS_SESSIONS',1);

// session key may be overridden
if (!defined('SYS_SESSION_KEY')) {
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	define('SYS_SESSION_KEY',PATH_RELATIVE);
}

// Name of session cookie may also be overridden
if (!defined('SYS_SESSION_COOKIE')) {
	define('SYS_SESSION_COOKIE','PHPSESSID');
}

/* exdoc
 * Runs necessary code to initialize sessions for use.
 * This sends the session cookie header (via the session_start
 * PHP function) and sets up session variables needed by the
 * rest of the system and this subsystem.
 * @node Subsystems:Sessions
 */
function pathos_sessions_initialize() {	
	$sessid  = '';
	if (isset($_GET['expid'])) $sessid = $_GET['expid'];
	else if (isset($_POST['expid'])) $sessid =  $_POST['expid'];
	else if (!isset($_COOKIE[SYS_SESSION_COOKIE])) $sessid = md5(uniqid(rand(), true));
	else $sessid = $_COOKIE[SYS_SESSION_COOKIE];
	session_name(SYS_SESSION_COOKIE);
	session_id($sessid);
	$_COOKIE['PHPSESSID'] = $sessid;
	session_set_cookie_params(SESSION_TIMEOUT*2); // Full cookie lasts twice as long as the login session.
	
	session_start();
	if (!isset($_SESSION[SYS_SESSION_KEY])) $_SESSION[SYS_SESSION_KEY] = array();
	if (!isset($_SESSION[SYS_SESSION_KEY]['vars'])) $_SESSION[SYS_SESSION_KEY]['vars'] = array();
	if (isset($_SESSION[SYS_SESSION_KEY]['vars']['display_theme'])) define('DISPLAY_THEME',$_SESSION[SYS_SESSION_KEY]['vars']['display_theme']);
}

/* exdoc
 * Validates the stored session ticket against the database.  This is used
 * to force refreshes and force logouts.  It also updates activity time.
 * @node Subsystems:Sessions
 */
function pathos_sessions_validate() {
	global $db;
	if (pathos_sessions_loggedIn()) {
		$ticket = $db->selectObject('sessionticket',"ticket='".$_SESSION[SYS_SESSION_KEY]['ticket']."'");
		$timeoutval = SESSION_TIMEOUT;
		if ($timeoutval < 300) $timeoutval = 300;
		if ($ticket == null || $ticket->last_active < time() - $timeoutval) {
			pathos_sessions_logout();
			define('SITE_403_HTML',SESSION_TIMEOUT_HTML);
			return;
		}
		
		global $user;
		$user = $_SESSION[SYS_SESSION_KEY]['user'];
		if ($ticket->refresh == 1) {
			pathos_permissions_load($user);
			$db->updateObject($ticket,'sessionticket',"ticket='" . $ticket->ticket . "'");
		}
		$ticket->refresh = 0;
		
		$ticket->last_active = time();
		$db->updateObject($ticket,'sessionticket',"ticket='" . $ticket->ticket . "'");
	}
	define('SITE_403_HTML',SITE_403_REAL_HTML);
}

/* exdoc
 * Creates and stores a session ticket for the given user,
 * so that sessions can be tracked and permissions can be
 * refreshed as needed.
 *
 * @param User $user The user object of the newly logged-in user.
 * @node Subsystems:Sessions
 */
function pathos_sessions_login($user) {
	$ticket = null;
	$ticket->uid = $user->id;
	$ticket->ticket = uniqid("",true);
	$ticket->last_active = time();
	$ticket->start_time = time();
	$ticket->browser = $_SERVER['HTTP_USER_AGENT'];
	$ticket->ip_address = $_SERVER['REMOTE_ADDR'];
	
	global $db;
	$db->insertObject($ticket,'sessionticket');
	
	$_SESSION[SYS_SESSION_KEY]['ticket'] = $ticket->ticket;
	$_SESSION[SYS_SESSION_KEY]['user'] = $user;
	
	pathos_permissions_load($user);
}

/* exdoc
 * Clears the session of all user data, used when a user logs out.
 * This gets rid of stale session tickets, and resets the session
 * to a blank state.
 * @node Subsystems:Sessions
 */
function pathos_sessions_logout() {
	global $db;
	if (isset($_SESSION['ticket'])) $db->delete('sessionticket',"ticket='" . $_SESSION[SYS_SESSION_KEY]['ticket'] . "'");
	
	unset($_SESSION[SYS_SESSION_KEY]['ticket']);
	unset($_SESSION[SYS_SESSION_KEY]['user']);
	unset($_SESSION[SYS_SESSION_KEY]['vars']['display_theme']);
	
	pathos_permissions_clear();
}

/* exdpc
 * Looks at the session data to see if the current session is
 * that of a logged in user. Returns true if the viewer is logged
 * in, and false if it is not
 * @node Subsystems:Sessions
 */
function pathos_sessions_loggedIn() {
	return (isset($_SESSION[SYS_SESSION_KEY]['ticket']));
}

function pathos_sessions_getTicketString() {
	if (isset($_SESSION[SYS_SESSION_KEY]['ticket'])) {
		return $_SESSION[SYS_SESSION_KEY]['ticket'];
	} else return "";
}

/* exdoc
 * Checks to see if the session holds a set variable of the given name.
 *
 * Note that some session variables (like the user object and the ticket)
 * cannot be changed using this call (for security / sanity reason)
 * @node Subsystems:Sessions
 */
function pathos_sessions_isset($var) {
	return isset($_SESSION[SYS_SESSION_KEY]['vars'][$var]);
}

/* 
 * Sets a variable in the session data, for use on subsequent page calls.
 *
 * Note that some session variables (like the user object and the ticket)
 * cannot be changed using this call (for security / sanity reason)
 *
 * @param string $var The name of the variable, for later reference
 * @param mixed $val The value to store
 * @node Subsystems:Sessions
 */
function pathos_sessions_set($var, $val) {
	$_SESSION[SYS_SESSION_KEY]['vars'][$var] = $val;
}

/* exdoc
 * Removes a variable from the session.
 *
 * Note that some session variables (like the user object and the ticket)
 * cannot be changed using this call (for security / sanity reason)
 *
 * @param string $var The name of the variable to unset.
 * @node Subsystems:Sessions
 */
function pathos_sessions_unset($var) {
	unset($_SESSION[SYS_SESSION_KEY]['vars'][$var]);
}

/* exdoc
 * This retrieves the value of a persistent session variable. Returns
 * null if the variable is not set in the session, or the value of the stored variable.
 *
 * Note that some session variables (like the user object and the ticket)
 * cannot be changed using this call (for security / sanity reason)
 *
 * @param string $var The name of the variable to retrieve.
 * @node Subsystems:Sessions
 */
function pathos_sessions_get($var) {
	if (isset($_SESSION[SYS_SESSION_KEY]['vars'][$var])) {
		return $_SESSION[SYS_SESSION_KEY]['vars'][$var];
	} else return null;
}

?>
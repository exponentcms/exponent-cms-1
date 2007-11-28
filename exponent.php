<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

function __realpath($path) {
	$path = str_replace('\\','/',realpath($path));
	if ($path{1} == ':') {
		// We can't just check for C:/, because windows users may have the IIS webroot on X: or F:, etc.
		$path = substr($path,2);
	}
	return $path;
}

// Bootstrap, which will clean the _POST, _GET and _REQUEST arrays, and include 
// necessary setup files (exponent_setup.php, exponent_variables.php) as well as initialize
// the compatibility layer.
// This was moved into its own file from this file so that 'lighter' scripts could bootstrap.
include_once(dirname(__realpath(__FILE__)).'/exponent_bootstrap.php');

// After config config setup:
// Put session stuff first.
$user = null;

// Initialize the AutoLoader Subsystem
require_once(BASE.'subsystems/autoloader.php');

// Initialize the Sessions Subsystem
require_once(BASE.'subsystems/sessions.php');
// Initializes the session.  This will populate the $user variable
exponent_sessions_initialize();

if (!isset($_SERVER['QUERY_STRING'])) {
	$_SERVER['QUERY_STRING'] = '';
}

// Create a REQUEST_URI for people who don't have one.
// FIXME: Move this code (and other similar platform stuff) into a platform compat layer.
// FIXME:
$_SERVER['REQUEST_URI'] = SCRIPT_RELATIVE.SCRIPT_FILENAME . '?' . $_SERVER['QUERY_STRING'];

/*
if (isset($_REQUEST['section'])) {
	exponent_sessions_set('last_section', intval($_REQUEST['section']));
} else {
	if (!isset($_REQUEST['action']) && !isset($_REQUEST['module'])) exponent_sessions_set('last_section', SITE_DEFAULT_SECTION);
}
*/

if (!defined('DISPLAY_THEME')) {
	/* exdoc
	 * The directory and class name of the current active theme.  This may be different
	 * than the configure theme (DISPLAY_THEME_REAL) due to previewing.
	 */
	define('DISPLAY_THEME',DISPLAY_THEME_REAL);
}
if (!defined('THEME_ABSOLUTE')) {
	/* exdoc
	 * The absolute path to the current active theme's files.  This is similar to the BASE constant
	 */
	define('THEME_ABSOLUTE',BASE.'themes/'.DISPLAY_THEME.'/'); // This is the recommended way
}
if (!defined('THEME_RELATIVE')) {
	/* exdoc
	 * The relative web path to the current active theme.  This is similar to the PATH_RELATIVE consant.
	 */
	define('THEME_RELATIVE',PATH_RELATIVE.'themes/'.DISPLAY_THEME.'/');
}

// iconset base
if (!defined('ICON_RELATIVE')) {
	//DEPRECATED: old directory, inconsitent naming
	if (is_readable(THEME_ABSOLUTE . 'icons/')) {
		/* exdoc
		 * The relative web path to the current icon set.  If an icons/ directory exists directly
		 * underneath the theme's directory, that is used.  Otherwise, the system falls back to
		 * the iconset directory in the root of the Exponent directory.
		 */
		define('ICON_RELATIVE', THEME_RELATIVE . 'icons/');
	} else if(is_readable(THEME_ABSOLUTE . "images/icons/")){
		define('ICON_RELATIVE',THEME_RELATIVE . 'images/icons/');
	} else {
		define('ICON_RELATIVE', PATH_RELATIVE . 'themes/common/images/icons/');
	}
}
if (!defined('MIMEICON_RELATIVE')) {
	//DEPRECATED: old directory, inconsitent naming
	if (is_readable(THEME_ABSOLUTE . 'mimetypes/')) {
		/* exdoc
		 * The relative web path to the current MIME icon set.  If a mimetypes/ directory
		 * exists directly underneath the theme's directory, then that is used.  Otherwise, the
		 * system falls back to the iconset/mimetypes/ directory in the root of the Exponent directory.
		 */
		define('MIMEICON_RELATIVE', THEME_RELATIVE . 'mimetypes/');
	} else if(is_readable(THEME_ABSOLUTE . "images/icons/mimetypes" )){
		define('MIMEICON_RELATIVE', THEME_RELATIVE . "images/icons/mimetypes/");
	} else {
		define('MIMEICON_RELATIVE', PATH_RELATIVE . 'themes/common/images/icons/mimetypes/');
	}
}

// Initialize the language subsystem
require_once(BASE.'subsystems/lang.php');
exponent_lang_initialize();


// Initialize the Core Subsystem
require_once(BASE.'subsystems/core.php');

// Initialize the Database Subsystem
require_once(BASE.'subsystems/database.php');
$db = exponent_database_connect(DB_USER,DB_PASS,DB_HOST.':'.DB_PORT,DB_NAME);

// Initialize the Modules Subsystem.
require_once(BASE.'subsystems/modules.php');
exponent_modules_initialize();

// Initialize the Template Subsystem.
require_once(BASE.'subsystems/template.php');
// Initialize the Permissions Subsystem.
require_once(BASE.'subsystems/permissions.php');
// Initialize the Flow Subsystem.
if (!defined('SYS_FLOW')) require_once(BASE.'subsystems/flow.php');
// Initialize the User Subsystem.
require_once(BASE.'subsystems/users.php');

// Validate session
exponent_sessions_validate();
// Initialize permissions variables
exponent_permissions_initialize();

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// ACORN CODE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
////////////////////////////////////////////////////////////////////////////////
// Initialize the Exp Framework Subsystem & Set the routing information
require_once(BASE.'framework/expFramework.php');
//$template = null;
// initialize the router
$router = new router();
// if the user has turned on sef_urls then we need to route the request, otherwise we can just 
// skip it and default back to the old way of doing things.
$router->routeRequest();

//eDebug($router);
// MOVED FROM ABOVE FOR TESTING - WE NEED TO REMOVE ONE THESE
if (isset($_REQUEST['section'])) {
        exponent_sessions_set('last_section', intval($_REQUEST['section']));
} else {
        //exponent_sessions_unset('last_section');
}

//Initialize the navigation heirarchy
$sections = exponent_core_initializeNavigation();

//Check to see if we are executing an ajax action.
if (isset($_REQUEST['ajax_action']) ) { 
	define('IN_AJAX_ACTION', 1);
} else {
	define('IN_AJAX_ACTION', 0);
}

// Check if this was a printer friendly link request
define('PRINTER_FRIENDLY', isset($_REQUEST['printerfriendly']) ? 1 : 0);

#$section = (exponent_sessions_isset('last_section') ? exponent_sessions_get('last_section') : SITE_DEFAULT_SECTION);
if (isset($_REQUEST['action']) && isset($_REQUEST['module'])) {
	$section = (exponent_sessions_isset('last_section') ? exponent_sessions_get('last_section') : SITE_DEFAULT_SECTION);
} else {
	$section = (isset($_REQUEST['section']) ? $_REQUEST['section'] : SITE_DEFAULT_SECTION);
}
$sectionObj = $db->selectObject('section','id='. intval($section));

if (!navigationmodule::canView($sectionObj)) {
	define('AUTHORIZED_SECTION',0);
} else {
	define('AUTHORIZED_SECTION',1);
}
if (!navigationmodule::isPublic($sectionObj)) {
	define('PUBLIC_SECTION',0);
} else {
	define('PUBLIC_SECTION',1);
}

function eDebug($var){
	if (DEVELOPMENT) {
		echo "<xmp>";
		print_r($var);
		echo "</xmp>";
	}
}

function eLog($var, $type='', $path='', $minlevel='0') {
        if($type == '') { $type = "INFO"; }
        if($path == '') { $path = BASE . 'tmp/exponent.log'; }
        if (DEVELOPMENT >= $minlevel) {
                if (is_writable ($path) || !file_exists($path)) {
                        if (!$log = fopen ($path, "ab")) {
                                eDebug("Error opening log file for writing.");
                        } else {
                                if (fwrite ($log, $type . ": " . $var . "\r\n") === FALSE) {
                                        eDebug("Error writing to log file ($log).");
                                }
                                fclose ($log);
                        }
                } else {
                        eDebug ("Log file ($log) not writable.");
                }
        }
}
?>

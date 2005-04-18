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

function __realpath($path) {
	$path = str_replace('\\','/',realpath($path));
	if ($path{1} == ':') {
		// We can't just check for C:/, because windows users may have the IIS webroot on X: or F:, etc.
		$path = substr($path,2);
	}
	return $path;
}

// Bootstrap, which will clean the _POST, _GET and _REQUEST arrays, and include 
// necessary setup files (pathos_setup.php, pathos_variables.php) as well as initialize
// the compatibility layer.
// This was moved into its own file from this file so that 'lighter' scripts could bootstrap.
include_once(dirname(__realpath(__FILE__)).'/pathos_bootstrap.php');


// Load the site configuration (without initializing the config subsystem)
include_once(BASE.'subsystems/config/load.php');

// After config config setup:
// Put session stuff first.
$user = null;
// Initialize the Sessions Subsystem
include_once(BASE.'subsystems/sessions.php');
pathos_sessions_initialize();

if (!isset($_SERVER['QUERY_STRING'])) {
	$_SERVER['QUERY_STRING'] = '';
}

// Create a REQUEST_URI for people who don't have one.
// FIXME: Move this code (and other similar platform stuff) into a platform compat layer.
// FIXME:
$_SERVER['REQUEST_URI'] = SCRIPT_RELATIVE.SCRIPT_FILENAME . '?' . $_SERVER['QUERY_STRING'];

if (isset($_REQUEST['section'])) {
	pathos_sessions_set('last_section',$_REQUEST['section']);
}

if (!defined('DISPLAY_THEME')) {
	/* exdoc
	 * The directory and class name of the current active theme.  This may be different
	 * than the configure theme (DISPLAY_THEME_REAL) due to previewing.
	 */
	define('DISPLAY_THEME',DISPLAY_THEME_REAL);
}
if (!defined('THEME_BASE')) {
	/* exdoc
	 * The absolute path to the current active theme's files.  This is similar to the BASE constant.
	 * This is deprecated beginning with 0.96 -- please use THEME_ABSOLUTE instead.
	 * @state deprecated
	 */
	define('THEME_BASE',BASE.'themes/'.DISPLAY_THEME.'/');
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
	if (is_readable(THEME_ABSOLUTE.'icons/')) {
		/* exdoc
		 * The relative web path to the current icon set.  If an icons/ directory exists directly
		 * underneath the theme's directory, that is used.  Otherwise, the system falls back to
		 * the iconset directory in the root of the Exponent directory.
		 */
		define('ICON_RELATIVE',THEME_RELATIVE.'icons/');
	} else {
		define('ICON_RELATIVE',PATH_RELATIVE.'iconset/');
	}
}
if (!defined('MIMEICON_RELATIVE')) {
	if (is_readable(THEME_ABSOLUTE.'mimetypes/')) {
		/* exdoc
		 * The relative web path to the current MIME icon set.  If a mimetypes/ directory
		 * exists directly underneath the theme's directory, then that is used.  Otherwise, the
		 * system falls back to the iconset/mimetypes/ directory in the root of the Exponent directory.
		 */
		define('MIMEICON_RELATIVE',THEME_RELATIVE.'mimetypes/');
	} else {
		define('MIMEICON_RELATIVE',PATH_RELATIVE.'iconset/mimetypes/');
	}
}

// Initialize the language subsystem
include_once(BASE.'subsystems/lang.php');
pathos_lang_initialize();

// Initialize the AutoLoader Subsystem
include_once(BASE.'subsystems/autoloader.php');
// Initialize the Core Subsystem
include_once(BASE.'subsystems/core.php');

// Initialize the Database Subsystem
include_once(BASE.'subsystems/database.php');
$db = pathos_database_connect(DB_USER,DB_PASS,DB_HOST.':'.DB_PORT,DB_NAME);

// Initialize the Modules Subsystem.
include_once(BASE.'subsystems/modules.php');
pathos_modules_initialize();

// Initialize the Template Subsystem.
include_once(BASE.'subsystems/template.php');
// Initialize the Permissions Subsystem.
include_once(BASE.'subsystems/permissions.php');
// Initialize the Flow Subsystem.
if (!defined('SYS_FLOW')) include_once(BASE.'subsystems/flow.php');

// Validate session
pathos_sessions_validate();
// Initialize permissions variables
pathos_permissions_initialize();

$section = (pathos_sessions_isset('last_section') ? pathos_sessions_get('last_section') : SITE_DEFAULT_SECTION);
$section = $db->selectObject('section','id='.$section);
if (!navigationmodule::canView($section)) {
	define('AUTHORIZED_SECTION',0);
} else {
	define('AUTHORIZED_SECTION',1);
}
if (!navigationmodule::isPublic($section)) {
	define('PUBLIC_SECTION',0);
} else {
	define('PUBLIC_SECTION',1);
}

?>
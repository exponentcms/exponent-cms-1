<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

# Following code taken from http://us4.php.net/manual/en/function.get-magic-quotes-gpc.php
#   - it allows magic_quotes to be on without screwing stuff up. 
if (get_magic_quotes_gpc()) {
   function stripslashes_deep($value)
   {
       $value = is_array($value) ?
                   array_map('stripslashes_deep', $value) :
                   stripslashes($value);

       return $value;
   }

   $_POST = array_map('stripslashes_deep', $_POST);
   $_GET = array_map('stripslashes_deep', $_GET);
   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function __realpath($path) {
	return str_replace("\\","/",realpath($path));
}

include_once("overrides.php");

include_once("pathos_variables.php");

// Include compatibility layer
include(BASE."compat.php");

// Put session stuff first.
$user = null;
include_once(BASE."subsystems/sessions.php");
// Initialize session
pathos_sessions_initialize();
include_once(BASE."subsystems/config/load.php");

// After config config setup:

// SEF URLs
if (SEF_URLS == 1) {
	$tokens = str_replace(PATH_RELATIVE.basename($_SERVER['SCRIPT_FILENAME']),"",$_SERVER['REQUEST_URI']);
	if ($tokens != "") {
		$tokens = substr($tokens,1); // strip leading '/'
		$tokens = split("/",$tokens);
		$_GET = array();
		for ($i = 0; $i < count($tokens); $i+=2) {
			$_GET[$tokens[$i]] = urldecode($tokens[$i+1]);
			if (!isset($_REQUEST[$tokens[$i]])) $_REQUEST[$tokens[$i]] = $_GET[$tokens[$i]];
		}
	}
} else {
	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];
}

if (isset($_REQUEST['section'])) pathos_sessions_set("last_section",$_REQUEST['section']);

if (!defined("DISPLAY_THEME")) define("DISPLAY_THEME",DISPLAY_THEME_REAL);
if (!defined("THEME_BASE")) define("THEME_BASE",BASE."themes/".DISPLAY_THEME."/");
if (!defined("THEME_ABSOLUTE")) define("THEME_ABSOLUTE",BASE."themes/".DISPLAY_THEME."/"); // This is the recommended way
if (!defined("THEME_RELATIVE")) define("THEME_RELATIVE",PATH_RELATIVE."themes/".DISPLAY_THEME."/");

// iconset base
if (!defined("ICON_RELATIVE")) {
	if (is_readable(THEME_ABSOLUTE."icons/")) define("ICON_RELATIVE",THEME_RELATIVE."icons/");
	else define("ICON_RELATIVE",PATH_RELATIVE."iconset/");
}
if (!defined("MIMEICON_RELATIVE")) {
	if (is_readable(THEME_ABSOLUTE."mimetypes/")) define("MIMEICON_RELATIVE",THEME_RELATIVE."mimetypes/");
	else define("MIMEICON_RELATIVE",PATH_RELATIVE."iconset/mimetypes/");
}
// Bring up the Essential Subsystems:
include_once(BASE."subsystems/autoloader.php");
include_once(BASE."subsystems/core.php");


include_once(BASE."subsystems/database.php");
$db = pathos_database_connect(DB_USER,DB_PASS,DB_HOST.":".DB_PORT,DB_NAME);

include_once(BASE."subsystems/modules.php");
pathos_modules_initialize();


include_once(BASE."subsystems/template.php");
include_once(BASE."subsystems/permissions.php");
if (!defined("SYS_FLOW")) include_once(BASE."subsystems/flow.php");

// Validate session
pathos_sessions_validate();
// Initialize permissions variables
pathos_permissions_initialize();

?>
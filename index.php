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
//GREP:HARDCODEDTEXT
/**
 * Exponent Entry Point page
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Exponent
 */

ob_start();
$microtime_str = explode(" ",microtime());
$i_start = $microtime_str[0] + $microtime_str[1];

/**
 * Initialize the Pathos Framework
 */
include_once("pathos.php");

/**
 * @ignore
 */
define("SCRIPT_RELATIVE",PATH_RELATIVE);
/**
 * @ignore
 */
define("SCRIPT_ABSOLUTE",BASE);
/**
 * @ignore
 */
define("SCRIPT_FILENAME","index.php");

/**
 * Initialize the theme subsystem
 */
if (!defined("SYS_THEME")) include_once(BASE."subsystems/theme.php");

if (!DEVELOPMENT && is_readable(BASE."install")) {
	/**
	 * In case we are not running in developer mode, and the install/ directory is readable,
	 * drop the user into the 'Not Yet Configured' warning page.
	 */
	include(BASE."install_warn/install_warn.html");
	exit();
}

$section = (pathos_sessions_isset("last_section") ? pathos_sessions_get("last_section") : SITE_DEFAULT_SECTION);
$section = $db->selectObject("section","id=".$section);

// Handle sub themes
$page = ($section && $section->subtheme != "" && is_readable("themes/".DISPLAY_THEME."/subthemes/".$section->subtheme.".php") ?
	"themes/".DISPLAY_THEME."/subthemes/".$section->subtheme.".php" :
	"themes/".DISPLAY_THEME."/index.php"
);

if (is_readable(BASE.$page)) {
	include_once(BASE.$page);
} else echo BASE."$page not readable";

$microtime_str = explode(" ",microtime());
$i_end = $microtime_str[0] + $microtime_str[1];

echo "<!- Execution: " . round($i_end - $i_start,4) . " seconds. -->";
ob_end_flush();

?>
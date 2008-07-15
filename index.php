<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

define('SCRIPT_EXP_RELATIVE','');
define('SCRIPT_FILENAME','index.php');

ob_start("ob_gzhandler");
$microtime_str = explode(' ',microtime());
$i_start = $microtime_str[0] + $microtime_str[1];

// Initialize the Exponent Framework
require_once('exponent.php');

// if the user has turned on sef_urls then we need to route the request, otherwise we can just 
// skip it and default back to the old way of doing things.
$router->routeRequest();
$section = $router->getSection();
$sectionObj = $router->getSectionObj($section);

// set the output header
Header("Content-Type: text/html; charset=".LANG_CHARSET);

// Initialize the theme subsystem
if (!defined('SYS_THEME')) require_once(BASE.'subsystems/theme.php');

// Check to see if we are in maintenance mode.
if (MAINTENANCE_MODE && !exponent_users_isAdmin() && ( !isset($_REQUEST['module']) || $_REQUEST['module'] != 'loginmodule')) {
	//only admins/acting_admins are allowed to get to the site, all others get the maintenance view
	$template = new standalonetemplate('_maintenance');
	$template->output();
} else {
	if (MAINTENANCE_MODE > 0) flash('error', "Maintenance Mode is Enabled");
	//the default user is anonymous
	if (!exponent_sessions_loggedIn()) {
		// Initialize the users subsystem
		require_once(BASE.'subsystems/users.php');

		//TODO: Maxims initial anonymous user implementation
		//exponent_users_login("anonymous", "anonymous");
	}

	if (!DEVELOPMENT && @file_exists(BASE.'install/not_configured')) {
		header('Location: '.URL_FULL.'install/index.php?page=setlang');
		exit('Redirecting to the Exponent Install Wizard');
	}

	// Handle sub themes
	$page = exponent_theme_getTheme();

	// If we are in a printer friendly request then we need to change to our printer friendly subtheme
	if (PRINTER_FRIENDLY == 1) {
		exponent_sessions_set("uilevel",0);
		$pftheme = exponent_theme_getPrinterFriendlyTheme();  	// get the printer friendly theme 
		$page = $pftheme == null ? $page : $pftheme;		// if there was no theme found then just use the current subtheme
	}
 
	$base_i18n = exponent_lang_loadFile('index.php');

	if (is_readable($page)) {
		if (!exponent_javascript_inAjaxAction()) {
			include_once($page);
			exponent_theme_satisfyThemeRequirements();
		} else {
			exponent_theme_runAction();
		}
	} else {
		echo sprintf($base_i18n['not_readable'], $page);
	}

	if (PRINTER_FRIENDLY == 1) {
		//$levels = exponent_sessions_get('uilevels');
		//if (!empty($levels)) exponent_sessions_set('uilevel',max(array_keys($levels)));
		exponent_sessions_unset('uilevel');
	}
}


//$microtime_str = explode(' ',microtime());
//$i_end = $microtime_str[0] + $microtime_str[1];
//echo "\r\n<!--".sprintf($base_i18n['exec_time'],round($i_end - $i_start,4)).'-->';

while (ob_get_level() > 0) {
           ob_end_flush();
}

?>

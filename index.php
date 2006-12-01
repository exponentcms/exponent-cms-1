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


// Check to see if we are in maintenance mode.
if (MAINTENANCE_MODE AND (!exponent_sessions_loggedIn() OR $user->is_acting_admin == 0)) {
	//only admins/acting_admins are allowed to get to the site,
	//all others get the maintenance view
	//Note: admins are automatically acting admins

	$template = new standalonetemplate('_maintenance');
	$template->output();
} else {
	//the default user is anonymous
	if (!exponent_sessions_loggedIn()) {
		// Initialize the users subsystem
		require_once(BASE.'subsystems/users.php');
		
		//TODO: Maxims initial anonymous user implementation
		exponent_users_login("anonymous", "anonymous");
	}
	
	// Initialize the theme subsystem
	if (!defined('SYS_THEME')) require_once(BASE.'subsystems/theme.php');
	
	if (!DEVELOPMENT && @file_exists(BASE.'install/not_configured')) {
		header('Location: install/index.php?page=setlang');
		exit('Redirecting to the Exponent Install Wizard');
	}	
	
	// Setting $page  to an empty value, we do not want to get out
	// side params to be used when handling subsystems fail.
	
	$page = '';
	
	// Handle sub themes
	$page = ($sectionObj && $sectionObj->subtheme != '' && is_readable(BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$sectionObj->subtheme.'.php') ?
		BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$sectionObj->subtheme.'.php' :
		BASE.'themes/'.DISPLAY_THEME.'/index.php'
	);
	
	$base_i18n = exponent_lang_loadFile('index.php');
	
	if (is_readable($page)) {
		include_once($page);
	} else {
		echo sprintf($base_i18n['not_readable'], $page);
	}
}

$microtime_str = explode(' ',microtime());
$i_end = $microtime_str[0] + $microtime_str[1];

echo "\r\n<!--".sprintf($base_i18n['exec_time'],round($i_end - $i_start,4)).'-->';

while (ob_get_level() > 0) {
           ob_end_flush();
}

?>

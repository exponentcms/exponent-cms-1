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

ob_start();

// Initialize the Pathos Framework
include_once('pathos.php');

define('SCRIPT_RELATIVE',PATH_RELATIVE);
define('SCRIPT_ABSOLUTE',BASE);
define('SCRIPT_FILENAME','login.php');

// Initialize the Sessions Subsystem
if (!defined('SYS_SESSIONS')) include_once(BASE.'subsystems/sessions.php');
// Initialize the Theme Subsystem
if (!defined('SYS_THEME')) include_once(BASE.'subsystems/theme.php');

if (pathos_sessions_loggedIn()) {
	$SYS_FLOW_REDIRECTIONPATH = 'pathos_default';
	pathos_flow_redirect();
	exit;
} else if (isset($_REQUEST['module']) && isset($_REQUEST['action'])) {
	$SYS_FLOW_REDIRECTIONPATH = 'loginredirect'; 
	pathos_theme_runAction();
	loginmodule::show(DEFAULT_VIEW,null);
} else {
	$SYS_FLOW_REDIRECTIONPATH = 'loginredirect'; 
	pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
	loginmodule::show(DEFAULT_VIEW,null);
}

$template = new standalonetemplate('loginredirect');

$template->assign('output',ob_get_contents());
ob_end_clean();
$template->output();

?>
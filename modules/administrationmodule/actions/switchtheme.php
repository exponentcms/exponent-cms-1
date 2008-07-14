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

// Part of the Extensions category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_CONFIG')) include_once(BASE.'subsystems/config.php');
	exponent_sessions_set('display_theme',$_GET['theme']);
	if (DISPLAY_THEME_REAL == $_GET['theme']){
		exponent_sessions_set('display_theme',$_GET['theme']);
	}
	exponent_config_change('DISPLAY_THEME_REAL', $_GET['theme']);
	exponent_theme_remove_css();
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
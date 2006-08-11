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

//GREP:VIEWIFY
if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('searching',exponent_core_makeLocation('administrationmodule'))) {
	$template = new template('searchmodule','_spiderSite');
	
	if (!defined('SYS_MODULES')) include_once(BASE.'subsystems/modules.php');
	$db->delete('search');
	$mods = array();
	$modnames = array();
	foreach (exponent_modules_list() as $mod) {
		$name = call_user_func(array($mod,'name'));
		if (class_exists($mod) && is_callable(array($mod,'spiderContent'))) {
			if (call_user_func(array($mod,'spiderContent'))) {
				$mods[$name] = 1;
			} else {
//				$mods[$name] = 0;	
			}
		} else {
//			$mods[$name] = 0;
		}
	}
	
	uksort($mods,'strnatcasecmp');
	
	$template->assign('mods',$mods);
	$template->output();
}

?>
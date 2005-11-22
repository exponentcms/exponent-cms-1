<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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
//GREP:VIEWIFY
if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('searching',pathos_core_makeLocation('administrationmodule'))) {
	$template = new template('searchmodule','_spiderSite');
	
	if (!defined('SYS_MODULES')) include_once(BASE.'subsystems/modules.php');
	$db->delete('search');
	$mods = array();
	$modnames = array();
	foreach (pathos_modules_list() as $mod) {
		$name = call_user_func(array($mod,'name'));
		if (class_exists($mod) && is_callable(array($mod,'spiderContent'))) {
			if (call_user_func(array($mod,'spiderContent'))) {
				$mods[$name] = 1;
			}
		} else {
			$mods[$name] = 0;
		}
	}
	
	uksort($mods,'strnatcasecmp');
	$template->assign('mods',$mods);
	$template->output();
}

?>
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

// Part of the Configuration category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('configuration',pathos_core_makeLocation('administrationmodule'))) {
	$template = new template('administrationmodule','_sysinfo',$loc);
	
	ob_start();
	phpinfo(INFO_GENERAL+INFO_CONFIGURATION+INFO_MODULES);
	$str = ob_get_contents();
	$str = preg_replace('/[\r\n]*/','',$str);
	$str = preg_replace('/<style.*style>/','',$str);
	$str = preg_replace('/<img /','<img style="float:right;" ',$str);
	$str = str_replace(';','; ',$str);
	$str = str_replace(',',', ',$str);
	$str = str_replace(array('<html>','<body>','</body>','</html>'),'',$str);
	ob_end_clean();
	
	$template->assign('phpinfo',$str);
	
	if (!defined('SYS_MODULES')) include_once(BASE.'subsystems/modules.php');
	if (!defined('SYS_INFO')) include_once(BASE.'subsystems/info.php');
	
	$mods = array();
	
	foreach (pathos_modules_list() as $m) {
		if (class_exists($m)) {
			$mobj = new $m();
			$mods[$m] = array(
				'name'=>$mobj->name(),
				'author'=>$mobj->author(),
				'description'=>$mobj->description(),
			);
		}
	}
	
	$template->assign('modules',$mods);
	$template->assign('subsystems',pathos_info_subsystems());
	
	$template->assign('override_style',1);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
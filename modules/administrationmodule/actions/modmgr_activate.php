<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

$_GET['activate'] = intval($_GET['activate']);

if (pathos_permissions_check('extensions',pathos_core_makeLocation('administrationmodule'))) {
	if (isset($_GET['all'])) {
		$db->delete('modstate');
		$modstate->active = $_GET['activate'];
		if (!defined('SYS_MODULES')) require_once(BASE.'subsystems/modules.php');
		foreach (pathos_modules_list() as $mod) {
			$modstate->module = $mod;
			$db->insertObject($modstate,'modstate');
		}
	} else {
		// GREP:SECURITY -- SQL created off of _GET parameter that is non-numeric.  Needs to be sanitized.
		$modstate = $db->selectObject('modstate',"module='".$_GET['mod']."'");
		if ($modstate == null) {
			$modstate->active = $_GET['activate'];
			$modstate->module = $_GET['mod'];
			$db->insertObject($modstate,'modstate');
		} else {
			$modstate->active = $_GET['activate'];
			$db->updateObject($modstate,'modstate',"module='".$_GET['mod']."'");
		}
	}
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
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

// Part of the Extensions category

if (!defined('PATHOS')) exit('');

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
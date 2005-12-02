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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	if (isset($_POST['s'])) {
		$assoc = $db->selectObject("approvalpolicyassociation","module='".$_POST['m']."' AND source='".$_POST['s']."' AND is_global=0");
		if ($assoc) {
			$assoc->policy_id = $_POST['policy'];
			$db->updateObject($assoc,"approvalpolicyassociation","module='".$_POST['m']."' AND source='".$_POST['s']."' AND is_global=0");
		} else {
			$assoc->module = $_POST['m'];
			$assoc->source = $_POST['s'];
			$assoc->policy_id = $_POST['policy'];
			$assoc->is_global = 0;
			$db->insertObject($assoc,"approvalpolicyassociation");
		}
	} else {
		// Save global
		$assoc = $db->selectObject("approvalpolicyassociation","module='".$_POST['m']."' AND is_global=1");
		if ($assoc) {
			$assoc->policy_id = $_POST['policy'];
			$db->updateObject($assoc,"approvalpolicyassociation","module='".$_POST['m']."' AND is_global=1");
		} else {
			// new
			$assoc->module = $_POST['m'];
			$assoc->policy_id = $_POST['policy'];
			$assoc->is_global = 1;
			$db->insertObject($assoc,"approvalpolicyassociation");
		}
	}
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
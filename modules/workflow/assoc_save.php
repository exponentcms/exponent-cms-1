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

define("SCRIPT_EXP_RELATIVE","modules/workflow/");
define("SCRIPT_FILENAME","assoc_save.php");

include_once("../../pathos.php");

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	if (isset($_POST['s'])) {
		$assoc = $db->selectObject("approvalpolicyassociation","module='".$_POST['m']."' AND source='".$_POST['s']."' AND is_global=0");
		if ($assoc) {
			$assoc->policy_id = $_POST['policy'];
			if ($assoc->policy_id == 0) $db->delete("approvalpolicyassociation","module='".$_POST['m']."' AND source='".$_POST['s']."' AND is_global=0");
			else $db->updateObject($assoc,"approvalpolicyassociation","module='".$_POST['m']."' AND source='".$_POST['s']."' AND is_global=0");
		} else {
			if ($_POST['policy'] != 0) {
				$assoc->module = $_POST['m'];
				$assoc->source = $_POST['s'];
				$assoc->policy_id = $_POST['policy'];
				$assoc->is_global = 0;
				$db->insertObject($assoc,"approvalpolicyassociation");
			}
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
	header("Location: " . $_POST['redirect']);
}

?>
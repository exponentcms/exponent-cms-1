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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$action = null;
	if (isset($_POST['id'])) {
		$action = $db->selectObject("workflowaction","id=".$_POST['id']);
		$action->method = $_POST['method'];
		$action->parameters = $_POST['parameters'];
		$db->updateObject($action,"workflowaction");
	} else {
		$action->method = $_POST['method'];
		$action->parameters = $_POST['parameters'];
		
		$action->policy_id = $_POST['policy_id'];
		$action->type = $_POST['type'];
		if ($db->countObjects('workflowaction','policy_id='.$_POST['policy_id'].' AND type='.$_POST['type']) == 0) {
			$action->rank = 0;
		} else {
			$action->rank = $db->max('workflowaction','rank','policy_id,type','policy_id='.$_POST['policy_id'].' AND type='.$_POST['type'])+1;
		}
		$db->insertObject($action,'workflowaction');
	}
	
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
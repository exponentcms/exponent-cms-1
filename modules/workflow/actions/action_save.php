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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$action = null;
	if (isset($_POST['id'])) {
		$action = $db->selectObject("workflowaction","id=".$_POST['id']);
		$action->method = $_POST['method'];
		$action->parameters = $_POST['parameters'];
		$db->updateObject($action,'workflowaction');
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
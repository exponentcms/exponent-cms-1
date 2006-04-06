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

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {
	$policy = $db->selectObject('approvalpolicy','id='.intval($_GET['id']));
	if ($policy) {
		$db->delete('approvalpolicy','id='.$policy->id);
		$db->delete('approvalpolicyassociation','policy_id='.$policy->id);
		$db->delete('workflowaction','policy_id='.$policy->id);
		
		// Start deleting revisions
		if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
		foreach(exponent_workflow_getInfoTables() as $table) {
			// For each type underneath control of workflow, find
			$typename = str_replace('_wf_info','',$table); // FIXME something better than str_replace
			foreach ($db->selectObjects($table,'policy_id='.$policy->id) as $info) {
				// Delete all active revisions for each info object
				// NOTE : this will not delete old ones.
				$db->delete($typename.'_wf_revision','wf_original='.$info->real_id);
			}
			// Delete all info objects
			$db->delete($table,'policy_id='.$policy->id);
		}
		
		exponent_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>
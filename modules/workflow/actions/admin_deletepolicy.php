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
	$policy = $db->selectObject("approvalpolicy","id=".$_GET['id']);
	if ($policy) {
		$db->delete("approvalpolicy","id=".$policy->id);
		$db->delete("approvalpolicyassociation","policy_id=".$policy->id);
		$db->delete("workflowaction","policy_id=".$policy->id);
		
		// Start deleting revisions
		if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
		foreach(pathos_workflow_getInfoTables() as $table) {
			// For each type underneath control of workflow, find
			$typename = str_replace("_wf_info","",$table); // FIXME something better than str_replace
			foreach ($db->selectObjects($table,"policy_id=".$policy->id) as $info) {
				// Delete all active revisions for each info object
				// NOTE : this will not delete old ones.
				$db->delete($typename."_wf_revision","wf_original=".$info->real_id);
			}
			// Delete all info objects
			$db->delete($table,"policy_id=".$policy->id);
		}
		
		pathos_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>
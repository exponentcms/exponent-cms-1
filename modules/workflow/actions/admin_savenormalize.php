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

// Part of the Administration Control Panel : Workflow category

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$policy = unserialize(stripslashes($_POST['policy']));
	
	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	
	foreach ($_POST['selection'] as $type=>$items) {
		foreach ($items as $id=>$action) {
			$info = $db->selectObject($type."_wf_info","real_id=$id");
			$revision = $db->selectObject($type."_wf_revision","wf_original=".$info->real_id." AND wf_major=".$info->current_major." AND wf_minor=".$info->current_minor);
			
			switch ($action) {
				case "restart":
					$revision = pathos_workflow_restartRevisionPath($revision,$type,$policy,$info);
					break;
				case "eval":
					$revision = pathos_workflow_evaluateRevisionPath($revision,$type,$policy,$info);
					break;
			}
		}
	}
	
	// In either case, we have to update the revision info object
	// Having gotten $revision from the handler functions, it should be up to date.
	$db->updateObject($policy,"approvalpolicy","id=".$policy->id);
	
	pathos_flow_redirect();
}

?>
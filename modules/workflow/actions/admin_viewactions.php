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

if ($user && $user->is_acting_admin) {

	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	$actions = pathos_workflow_getActions($_GET['id']);
	
	// Workflow constants action names.
	$names = array(
		SYS_WORKFLOW_ACTION_POSTED=>"New Content Posted",
		SYS_WORKFLOW_ACTION_EDITED=>"Existing Content Edited",
		SYS_WORKFLOW_ACTION_APPROVED_APPROVED=>"Content Approved as-is",
		SYS_WORKFLOW_ACTION_APPROVED_EDITED=>"Content Edited, but approved",
		SYS_WORKFLOW_ACTION_APPROVED_DENIED=>"Content Denied Approval",
		SYS_WORKFLOW_ACTION_APPROVED_FINAL=>"Content Published",
		SYS_WORKFLOW_ACTION_DELETED=>"Approval Path Deleted",
		SYS_WORKFLOW_ACTION_RESTARTED=>"Approval Path Restarted",
		SYS_WORKFLOW_ACTION_IMPLICIT_APPROVAL=>"New Content Implicitly Approved",
		SYS_WORKFLOW_ACTION_POSTED_ADMIN=>"Content posted or edited by an Administrator",
		SYS_WORKFLOW_ACTION_APPROVED_ADMIN=>"Content approved by an Administrator"
	);
	
	$template = new template("workflow","_viewactions",$loc);
	$template->assign("actions",$actions);
	$template->assign("names",$names);
	$template->assign("policy_id",$_GET['id']);
	$template->output();
}

?>
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

if (!defined("PATHOS")) exit("");

$rloc = pathos_core_makeLocation($_GET['m'],$_GET['s']);
if (pathos_permissions_check("manage_approval",$rloc)) {

	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");

	$template = new template("workflow","_revisions",$loc);
	
	$current = $db->max($_GET['datatype']."_wf_revision","wf_major","wf_original","wf_original=".$_GET['id']);
	$template->assign("current",$current);
	$template->assign("datatype",$_GET['datatype']);
	
	$revisions = $db->selectObjects($_GET['datatype']."_wf_revision","wf_original=".$_GET['id']);
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	usort($revisions,"pathos_sorting_workflowRevisionDescending");
	$template->assign("revisions",$revisions);
	
	$css = array(
		SYS_WORKFLOW_ACTION_POSTED=>"workflow_action workflow_action_posted",
		SYS_WORKFLOW_ACTION_EDITED=>"workflow_action workflow_action_",
		SYS_WORKFLOW_ACTION_APPROVED_APPROVED=>"workflow_action workflow_action_approved_approved",
		SYS_WORKFLOW_ACTION_APPROVED_EDITED=>"workflow_action workflow_action_approved_edited",
		SYS_WORKFLOW_ACTION_APPROVED_DENIED=>"workflow_action workflow_action_approved_denied",
		SYS_WORKFLOW_ACTION_APPROVED_FINAL=>"workflow_action workflow_action_approved_final",
		SYS_WORKFLOW_ACTION_DELETED=>"workflow_action workflow_action_deleted",
		SYS_WORKFLOW_ACTION_RESTARTED=>"workflow_action workflow_action_restarted",
		SYS_WORKFLOW_ACTION_IMPLICIT_APPROVAL=>"workflow_action workflow_action_implicit_approval",
		SYS_WORKFLOW_ACTION_RESTORED=>"workflow_action workflow_action_restored"
	);
	
	$template->assign("css",$css);
	
	$policies = $db->selectObjectsIndexedArray("approvalpolicy");
	$template->assign("policies",$policies);
	
	$template->assign("datatype",$_GET['datatype']);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
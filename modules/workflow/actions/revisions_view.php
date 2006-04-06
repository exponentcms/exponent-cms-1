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

if (!defined("EXPONENT")) exit("");

// Sanitize required _GET parameters
$_GET['id'] = intval($_GET['id']);
$_GET['datatype'] = preg_replace('/[^A-Za-z0-9_]/','',$_GET['datatype']);

$rloc = exponent_core_makeLocation($_GET['m'],$_GET['s']);
if (exponent_permissions_check("manage_approval",$rloc)) {

	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");

	$template = new template("workflow","_revisions",$loc);

	$current = $db->max($_GET['datatype']."_wf_revision","wf_major","wf_original","wf_original=".$_GET['id']);
	$template->assign("current",$current);
	$template->assign("datatype",$_GET['datatype']);
	
	$revisions = $db->selectObjects($_GET['datatype']."_wf_revision","wf_original=".$_GET['id']);
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($revisions,"exponent_sorting_workflowRevisionDescending");
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
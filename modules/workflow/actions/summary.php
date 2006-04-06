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

if ($user) {

	// NEED:
	//	datatype name
	//	'view' action - use "view_inapproval" ?
	//	location (made from GET[m] and GET[s])
	
	// Logic / Pseudo-code
	//	read {$datatype}_wf_info records for current location.
	//	loop over all info records
	
	$loc = exponent_core_makeLocation($_GET['m'],$_GET['s']);
	$datatype = $_GET['datatype'];
	
	// Initialize the canview boolean, to determine if we should output the template or exit.
	// This type of permissions check requires us to read the data from the database first,
	// and then decide whether or not to let the user in.
	$canview = exponent_permissions_check("approve",$loc) || exponent_permissions_check("manage_approval",$loc);
	
	if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
	
	exponent_flow_set(SYS_FLOW_PROTECTED, SYS_FLOW_ACTION);
	
	if ($db->tableExists($datatype."_wf_info")) {
		$approveloc = exponent_core_makeLocation($_GET['m'],$_GET['s']);
		$summaries = $db->selectObjects($datatype."_wf_info","location_data='".serialize($approveloc)."'");
		for ($i = 0; $i < count($summaries); $i++) {
			$summaries[$i]->revision = $db->selectObject($datatype."_wf_revision","wf_original=".$summaries[$i]->real_id." AND wf_major=".$summaries[$i]->current_major." AND wf_minor=".$summaries[$i]->current_minor);
		
			$summaries[$i]->state_data = unserialize($summaries[$i]->current_state_data);
			$involved_users = array();
			foreach ($summaries[$i]->state_data[0] as $id) {
				$involved_users[$id] = exponent_users_getUserById($id);;
			}
			if (isset($involved_users[$user->id])) $canview = true;
			
			$summaries[$i]->involved = $involved_users;
			
			$summaries[$i]->policy = $db->selectObject("approvalpolicy","id=".$summaries[$i]->policy_id);
			$summaries[$i]->real = $db->selectObject($datatype,"id=".$summaries[$i]->real_id);
		}
	}
	
	if ($canview) {
		$template = new template("workflow","_summary",exponent_core_makeLocation('workflow',$loc->src));
		$template->register_permissions(
			array("manage_approval","approve"),
			$loc
		);
		$template->assign("summaries",$summaries);
		$template->assign("datatype",$datatype);
		$template->assign("user",$user);
		$template->output();
	}
} else {
	echo SITE_403_HTML;
}

?>
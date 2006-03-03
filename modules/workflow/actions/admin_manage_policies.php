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

// GREP:REFACTOR

// Part of the Administration Control Panel : Workflow category

if (!defined('EXPONENT')) exit('');

$loc = exponent_core_makeLocation('workflow');

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$policies = $db->selectObjects('approvalpolicy');
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	usort($policies,'exponent_sorting_byNameAscending');
	
	$template = new template('workflow','_policymanager',$loc);
	$template->assign('policies',$policies);
	$template->output();
	
	// NOW do the defaultassociations
	
	$policies = array();
	foreach ($db->selectObjects('approvalpolicy') as $pol) {
		$policies[$pol->id] = $pol;
	}
	
	$modules = array();
	$names = array();
	$defaults = array();
	foreach (exponent_modules_list() as $mod) {
		$m = new $mod();
		if (!$m->supportsWorkflow()) continue;
		$names[$mod] = $m->name();
		$modules[$mod] = array();
		
		// Grab all policies
		$assocs = array();
		foreach ($db->selectObjects('approvalpolicyassociation',"module='$mod' AND is_global=0") as $assoc) {
			$assocs[$assoc->source] = $assoc->policy_id;
		}
		
		$default = $db->selectObject('approvalpolicyassociation',"module='$mod' AND is_global=1");
		$defaults[$mod] = $default->policy_id;
		
		// Now grab all the sources.
		foreach ($db->selectObjects('locationref',"module='$mod'") as $ref) {
			$modules[$mod][] = array(
				'source'=>$ref->source,
				'policy_id'=>(isset($assocs[$ref->source])?$assocs[$ref->source]:0)
			);
		}
	}
	uksort($modules,'strnatcmp');
	
	$template = new template('workflow','_assocviewer',$loc);
	$template->assign('modules',$modules);
	$template->assign('names',$names);
	$template->assign('defaults',$defaults);
	$template->assign('policies',$policies);
	$template->assign('policy_count',count($policies));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
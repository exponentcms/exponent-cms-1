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

$loc = pathos_core_makeLocation("workflow");

if ($user && $user->is_acting_admin == 1) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$policies = $db->selectObjects("approvalpolicy");
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	usort($policies,"pathos_sorting_byNameAscending");
	
	$template = new Template("workflow","_policymanager",$loc);
	$template->assign("policies",$policies);
	$template->output();
	
	// NOW do the defautl associations
	// Used to sort the sources for the viewer.
	function prettySourceCmp($a,$b) {
		return strnatcmp($a['prettySource'],$b['prettySource']);
	}
	
	$policies = array();
	foreach ($db->selectObjects("approvalpolicy") as $pol) {
		$policies[$pol->id] = $pol;
	}
	
	$modules = array();
	$names = array();
	$defaults = array();
	foreach (pathos_modules_list() as $mod) {
		$m = new $mod();
		if (!$m->supportsWorkflow()) continue;
		$names[$mod] = $m->name();
		$modules[$mod] = array();
		
		// Grab all policies
		$assocs = array();
		foreach ($db->selectObjects("approvalpolicyassociation","module='$mod' AND is_global=0") as $assoc) {
			$assocs[$assoc->source] = $assoc->policy_id;
		}
		
		$default = $db->selectObject("approvalpolicyassociation","module='$mod' AND is_global=1");
		$defaults[$mod] = $default->policy_id;
		
		// Now grab all the sources.
		foreach ($db->selectObjects("locationref","module='$mod'") as $ref) {
			$modules[$mod][] = array(
				"source"=>$ref->source,
				"prettySource"=>pathos_core_translateLocationSource($ref->source),
				"policy_id"=>(isset($assocs[$ref->source])?$assocs[$ref->source]:0)
			);
		}
		
		uasort($modules[$mod],"prettySourceCmp");
		
	}
	uksort($modules,"strnatcmp");
	
	$template = new Template("workflow","_assocviewer",$loc);
	$template->assign("modules",$modules);
	$template->assign("names",$names);
	$template->assign("defaults",$defaults);
	$template->assign("policies",$policies);
	$template->assign("policy_count",count($policies));
	$template->output();
}

?>
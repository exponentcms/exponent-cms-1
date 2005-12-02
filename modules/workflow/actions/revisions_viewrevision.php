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

if (!defined("PATHOS")) exit("");

// Sanitize required _GET parameters
$_GET['id'] = intval($_GET['id']);

// GREP:SECURITY -- SQL is created from _GET parameter that is non-numeric.  Needs to be sanitized.

$object = $db->selectObject($_GET['datatype']."_wf_revision","id=".$_GET['id']);
$rloc = unserialize($object->location_data);
if (pathos_permissions_check("manage_approval",$rloc)) {
	// We need the module, in order to render the view correctly.
	$oloc = unserialize($object->location_data);
	$module = $oloc->mod;

	$template = new template($module,"_workflowview",$loc);
	$template->assign("item",$object);
	$view = $template->render();
	
	$t = new template("workflow","_viewrevision",$loc);
	$t->assign("view",$view);
	$t->assign("back",pathos_flow_get());
	$t->assign("revision",$object);
	$t->output();
} else {
	echo SITE_403_HTML;
}

?>
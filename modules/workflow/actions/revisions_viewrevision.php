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
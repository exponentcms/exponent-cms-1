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

// PERM CHECK
	$info = $db->selectObject($_GET['datatype']."_wf_info","real_id=".$_GET['id']);
	$object = $db->selectObject($_GET['datatype']."_wf_revision","wf_original=".$_GET['id']." AND wf_major=".$info->current_major." AND wf_minor=".$info->current_minor);

	// We need the module, in order to render the view correctly.
	$oloc = unserialize($object->location_data);
	$module = $oloc->mod;

	$template = new template($module,"_workflowview",$loc);
	$template->assign("item",$object);
	$template->output();
	
	$template = new template("workflow","_previewlinks",$loc);
	$template->assign("datatype",$_GET['datatype']);
	$template->assign("mod",$module);
	$template->assign("id",$_GET['id']);
	$template->output();
// END PERM CHECK

?>
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

if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
pathos_forms_initialize();

$f = null;
if (isset($_POST['id'])) {
	$f = $db->selectObject("formbuilder_form","id=".$_POST['id']);
}

if (pathos_permissions_check("editform",unserialize($f->location_data))) {

	$f = formbuilder_form::update($_POST,$f);
	
	$f->table_name = formbuilder_form::updateTable($f);
	
	//$f->location_data = serialize($loc);
	
	if (isset($f->id)) $db->updateObject($f,"formbuilder_form");
	else {
		$f->location_data = serialize(pathos_core_makeLocation($_POST['m'],$_POST['s'],$_POST['i']));
		$f->id = $db->insertObject($f,"formbuilder_form");
		//Create Default Report;
		$rpt->name = "Default Report";
		$rpt->description = "This is the auto generated default report. Leave the report definition blank to use the default 'all fields' report.";
		$rpt->location_data = $f->location_data;
		$rpt->text = "";
		$rpt->column_names = "";
		$rpt->form_id = $f->id;
		$db->insertObject($rpt,"formbuilder_report");
	}
	
	//Delete All addresses as we will be rebuilding it.
	$db->delete("formbuilder_address","form_id=".$f->id);
	$data->group_id = 0;
	$data->user_id = 0;
	$data->email="";
	$data->form_id = $f->id;
	foreach (listbuildercontrol::parseData($_POST,'groups') as $group_id) {
		$data->group_id = $group_id;
		$db->insertObject($data,"formbuilder_address");
	}
	$data->group_id = 0;
	foreach (listbuildercontrol::parseData($_POST,'users') as $user_id) {
		$data->user_id = $user_id;
		$db->insertObject($data,"formbuilder_address");
	}
	$data->user_id = 0;
	foreach (listbuildercontrol::parseData($_POST,'addresses') as $email) {
		$data->email = $email;
		$db->insertObject($data,"formbuilder_address");
	}

	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
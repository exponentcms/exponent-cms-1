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

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('modules/formbuilder/actions/save_form.php');

if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
exponent_forms_initialize();

$f = null;
if (isset($_POST['id'])) {
	$f = $db->selectObject('wizard_form','id='.intval($_POST['id']));
}

if (exponent_permissions_check('editform',unserialize($f->location_data))) {
	$f = wizard_form::update($_POST,$f);
	$f->table_name = wizard_form::updateTable($f);
	
	if (isset($f->id)) {
		$db->updateObject($f,'wizard_form');
	} else {
		$f->location_data = serialize(exponent_core_makeLocation($_POST['m'],$_POST['s'],$_POST['i']));
		$f->id = $db->insertObject($f,'wizard_form');
		//Create Default Report;
		$rpt->name = $i18n['default_report'];
		$rpt->description = $i18n['auto_generated'];
		$rpt->location_data = $f->location_data;
		$rpt->text = '';
		$rpt->column_names = '';
		$rpt->wizard_id = $f->wizard_id;
		$db->insertObject($rpt,'wizard_report');
	}
	
	//Delete All addresses as we will be rebuilding it.
	$db->delete('wizard_address','form_id='.$f->id);
	$data->group_id = 0;
	$data->user_id = 0;
	$data->email='';
	$data->form_id = $f->id;
	foreach (listbuildercontrol::parseData($_POST,'groups') as $group_id) {
		$data->group_id = $group_id;
		$db->insertObject($data,'wizard_address');
	}
	$data->group_id = 0;
	foreach (listbuildercontrol::parseData($_POST,'users') as $user_id) {
		$data->user_id = $user_id;
		$db->insertObject($data,'wizard_address');
	}
	$data->user_id = 0;
	foreach (listbuildercontrol::parseData($_POST,'addresses') as $email) {
		$data->email = $email;
		$db->insertObject($data,'wizard_address');
	}

	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>

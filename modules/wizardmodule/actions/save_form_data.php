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
if (!defined("SYS_USER")) require_once(BASE."subsystems/users.php");
if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
exponent_forms_initialize();
global $user;
$f = $db->selectObject("wizard_form","id=".intval($_POST['id']));
//$rpt = $db->selectObject("wizard_report","form_id=".intval($_POST['id']));
$controls = $db->selectObjects("wizard_control","form_id=".$f->id." and is_readonly=0");
if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
usort($controls,"exponent_sorting_byRankAscending");

$db_data = null;
$fields = array();
$captions = array();
foreach ($controls as $c) {
	$ctl = unserialize($c->data);
	$control_type = get_class($ctl);
	$def = call_user_func(array($control_type,"getFieldDefinition"));
	if ($def != null) {
		$value = call_user_func(array($control_type,'parseData'),$c->name,$_POST,true);
		$varname = $c->name;
		$db_data->$varname = $value;
		$fields[$c->name] = call_user_func(array($control_type,'templateFormat'),$value,$ctl);
		$captions[$c->name] = $c->caption;
	}
}

if (isset($_POST['data_id'])) {
	$olddata = $db->selectObject($f->table_name,'id='.intval($_POST['data_id']));
	$db_data->ip = $olddata->ip;
	$db_data->user_id = $olddata->user_id;
	$db_data->timestamp = $olddata->timestamp;
	$db_data->optional_value_1 = $olddata->optional_value_1;
	$db_data->optional_value_2 = $olddata->optional_value_2;
	$db->delete($f->table_name,'id='.intval($_POST['data_id']));
	$db->insertObject($db_data, $f->table_name);

	$url = null;	
	$url = exponent_sessions_get('wizard_redirecturl');
	if ($url != null) {
		header('Location: ' . $url);
        	exit();
	}
	//exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>

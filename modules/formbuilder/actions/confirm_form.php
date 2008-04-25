<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

//eDebug($_POST);
$cols = $db->selectObjects('formbuilder_control', 'form_id='.intval($_POST['id']));
//eDebug($cols);
foreach($cols as $col) {
	$coldef = unserialize($col->data);
	$coldata = new ReflectionClass($coldef);
        $coltype = $coldata->getName();
	if (!empty($_POST[$col->name])) {
		if ($coltype == 'checkboxcontrol') {
			$responses[$col->caption] = 'Yes';
		} else {
			$responses[$col->caption] = $_POST[$col->name];
		}
	} else {
		if ($coltype == 'checkboxcontrol') {
                        $responses[$col->caption] = 'No';
                } else {
                        $responses[$col->caption] = '';
                }	
	}
}
// remove some post data we don't want to pass thru to the form
unset($_POST['action']);
unset($_POST['module']);
exponent_sessions_set('formmodule_data_'.$_POST['id'], $_POST);

$template = new template("formbuilder","_confirm_form");
$template->assign('responses', $responses);
$template->assign('postdata', $_POST);
$template->output();

?>

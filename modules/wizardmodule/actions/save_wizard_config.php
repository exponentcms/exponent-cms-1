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
global $db;

//eDebug($_POST); exit();
$wizard = null;
if (isset($_POST['wizard_id'])) {
	$wizard = $db->selectObject('wizardmodule_config','wizard_id='.intval($_POST['wizard_id']));
} 

$wizard = wizardmodule_config::update($_POST,$wizard);

//Set the location data for this instance of wizard module
$loc->mod = "wizardmodule";
$loc->src = $_POST['src'];
//$loc->int = "";
$wizard->location_data = serialize($loc);

//eDebug($wizard);
 
if (isset($wizard->id)) {
	$db->updateObject($wizard, 'wizardmodule_config', "id=".$wizard->id);
} else {
	$id = $db->insertObject($wizard, 'wizardmodule_config');
}

if (isset($_POST['users'])) {
	$users = explode("|!|", $_POST['users']);
	$db->delete("wizard_address", "user_id != 0 AND wizard_id=".$_POST['wizard_id']);
	foreach ($users as $user_id) {
		$user =null;
		$user->user_id = $user_id;
		$user->wizard_id = $_POST['wizard_id'];
		eDebug($user);
		$db->insertObject($user, "wizard_address");
	}
}

if (isset($_POST['addresses'])) {
	$all_addys = explode("|!|", $_POST['addresses']);
	$db->delete("wizard_address", "email != '' AND wizard_id=".$_POST['wizard_id']);
        foreach ($all_addys as $email_addy) {
		$user = null;
                $user->email = $email_addy;
                $user->wizard_id = $_POST['wizard_id'];
		eDebug($user);
                $db->insertObject($user, "wizard_address");
        }
}

if (isset($_POST['groups'])) {
	$groups = explode("|!|", $_POST['groups']);
	$db->delete("wizard_address", "group_id != 0 AND wizard_id=".$_POST['wizard_id']);
        foreach ($groups as $group_id) {
		$user = null;
                $user->group_id = $group_id;
                $user->wizard_id = $_POST['wizard_id'];
		eDebug($user);
                $db->insertObject($user, "wizard_address");
        }
}
//exit();
exponent_flow_redirect();

?>

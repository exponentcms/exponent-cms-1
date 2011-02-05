<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

//exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
if (exponent_users_isLoggedIn()) {
	validator::validate(array('presence_of'=>'firstname','presence_of'=>'lastname','presence_of'=>'confirm'), $_POST);
	$signature->firstname = $_POST['firstname'];
	$signature->lastname = $_POST['lastname'];
	$signature->user_id = $user->id;
	$signature->confirm = isset($_POST['confirm']) ? 1 : 0;
	$signature->signed_at = time();
	$signature->location_data = serialize($loc);

	// look up the resources module config object to get the text of the agreement
	$conf = $db->selectObject('resourcesmodule_config', "location_data='".serialize($loc)."'");
	$signature->agreement_body = $conf->agreement_body;
	$db->insertObject($signature, 'resource_agreement');
	//redirect_to(array('module'=>'resourcesmodule', 'action'=>'download_resource', 'id'=>intval($_REQUEST['id'])));
} else {
	$msg = exponent_lang_getText("A signed confidentiality agreement is required to view this document. To sign the agreement you must first be logged in.");

	flash ('error', $msg);
	exponent_flow_redirect();
}
            
?>

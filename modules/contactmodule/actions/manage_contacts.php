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

if (exponent_permissions_check('configure',$loc)) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$contacts = array();
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	
	foreach ($db->selectObjects('contact_contact',"location_data='".serialize($loc)."'") as $c) {
		if ($c->user_id != 0) {
			$u = exponent_users_getUserById($c->user_id);
			$c->email = $u->email;
			$c->name = $u->firstname . ' ' . $u->lastname;
			if (trim($c->name) == '') $c->name = $u->username;
		} else {
			$c->name = '';
		}
		$contacts[] = $c;
	}
	
	$template = new template('contactmodule','_contactmanager',$loc);
	$template->assign('contacts',$contacts);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
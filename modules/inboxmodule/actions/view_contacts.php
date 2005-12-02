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

if (!defined('PATHOS')) exit('');

if ($user) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	
	$groups = $db->selectObjects('inbox_contactlist','owner='.$user->id);
	
	$banned = $db->selectObjects('inbox_contactbanned','owner='.$user->id);
	for ($i = 0; $i < count($banned); $i++) {
		$banned[$i]->user = pathos_users_getUserById($banned[$i]->user_id);
	}
	
	$template = new template('inboxmodule','_viewcontacts',$loc);
	$template->assign('groups',  $groups);
	$template->assign('banned',  $banned);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
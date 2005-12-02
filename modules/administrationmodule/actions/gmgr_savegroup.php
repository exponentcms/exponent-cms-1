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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	if (isset($_POST['id'])) { // Existing user profile edit
		$g = pathos_users_getGroupById($_POST['id']);
		$g = pathos_users_groupUpdate($_POST,$g);
		pathos_users_saveGroup($g);
		
		pathos_flow_redirect();
	} else {
		if (pathos_users_getGroupByName($_POST['name']) != null) {
			$i18n = pathos_lang_loadFile('modules/administrationmodule/actions/gmgr_savegroup.php');
			$post = $_POST;
			$post['_formError'] = $i18n['name_taken'];
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			$g = pathos_users_groupUpdate($_POST,null);
			pathos_users_saveGroup($g);
			pathos_flow_redirect();
		}
	}
} else {
	echo SITE_403_HTML;
}

?>
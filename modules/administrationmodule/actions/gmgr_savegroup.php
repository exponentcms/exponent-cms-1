<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
//GREP:HARDCODEDTEXT

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	if (isset($_POST['id'])) { // Existing user profile edit
		$g = pathos_users_getGroupById($_POST['id']);
		$g = pathos_users_groupUpdate($_POST,$g);
		pathos_users_saveGroup($g);
		
		pathos_flow_redirect();
	} else {
		if (pathos_users_getGroupByName($_POST['name']) != null) {
			pathos_lang_loadDictionary('modules','administrationmodule');
			$post = $_POST;
			$post['_formError'] = TR_ADMINISTRATIONMODULE_GROUPNAMETAKEN;
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
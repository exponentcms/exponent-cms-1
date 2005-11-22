<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin == 1) {
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	if (!defined('SYS_SECURITY')) require_once(BASE.'subsystems/security.php');
	if (isset($_POST['id'])) { // Existing user profile edit
		$u = pathos_users_getUserById($_POST['id']);
		$u = pathos_users_update($_POST,$u);
		pathos_users_saveUser($u);
		
		pathos_flow_redirect();
	} else {
		$i18n = pathos_lang_loadFile('modules/administrationmodule/actions/umgr_saveuser.php');
		
		if (pathos_users_getUserByName($_POST['username']) != null) {
			$post = $_POST;
			unset($post['username']);
			$post['_formError'] = $i18n['name_taken'];
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else if ($_POST['pass1'] != $_POST['pass2']) {
			$post = $_POST;
			unset($post['pass1']);
			unset($post['pass2']);
			$post['_formError'] = $i18n['unmatched_passwords'];
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			$strength_error = pathos_security_checkPasswordStrength($_POST['username'],$_POST['pass1']);
			if ($strength_error != '') {
				$post = $_POST;
				unset($post['pass1']);
				unset($post['pass2']);
				$post['_formError'] = sprintf($i18n['strength_failed'],$strength_error);
				pathos_sessions_set('last_POST',$post);
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				$u = pathos_users_create($_POST,null);
				$u = pathos_users_saveProfileExtensions($_POST,$u,true);
				pathos_flow_redirect();
			}
		}
	}
} else {
	echo SITE_403_HTML;
}

?>

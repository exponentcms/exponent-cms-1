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

// Part of the User Management category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('user_management',exponent_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin == 1) {
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	if (!defined('SYS_SECURITY')) require_once(BASE.'subsystems/security.php');
	if (isset($_POST['id'])) { // Existing user profile edit
		$_POST['id'] = intval($_POST['id']);
		$u = exponent_users_getUserById(intval($_POST['id']));
		$u = exponent_users_update($_POST,$u);
		//save extensions
		exponent_users_saveProfileExtensions($_POST,$u,false);
		exponent_users_saveUser($u);
		exponent_flow_redirect();
	} else {
		$i18n = exponent_lang_loadFile('modules/administrationmodule/actions/umgr_saveuser.php');
		$_POST['username'] = trim($_POST['username']);
		if (exponent_users_getUserByName($_POST['username']) != null) {
			unset($_POST['username']);
	                validator::failAndReturnToForm($i18n['name_taken'], $_POST);
		} else if ($_POST['pass1'] != $_POST['pass2']) {
			unset($_POST['pass1']);
	                unset($_POST['pass2']);
	                validator::failAndReturnToForm($i18n['unmatched_passwords'], $_POST);
		} else {
			$username_error = exponent_security_checkUsername($_POST['username']);
			$strength_error = exponent_security_checkPasswordStrength($_POST['username'],$_POST['pass1']);

			if ($username_error != ''){
				unset($_POST['username']);
		        validator::failAndReturnToForm(sprintf($i18n['username_failed'],$username_error), $_POST);
			}else if ($strength_error != '') {
				unset($_POST['pass1']);
	            unset($_POST['pass2']);
        	    validator::failAndReturnToForm(sprintf($i18n['strength_failed'],$strength_error), $_POST);
			} else {
				validator::validate(array('valid_email'=>'email'), $_POST);
				$u->is_locked = false;	// don't auto-lock admin created accounts
				
				$u = exponent_users_create($_POST,null);
				$u = exponent_users_saveProfileExtensions($_POST,$u,true);
				exponent_flow_redirect();
			}
		}
	}
} else {
	echo SITE_403_HTML;
}

?>

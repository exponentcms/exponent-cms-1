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
 
if (!defined('EXPONENT')) exit('');

if ($user) {
	$i18n = exponent_lang_loadFile('modules/loginmodule/actions/savepass.php');
	
	if ($user->password == md5($_POST['oldpass'])) {
		if ($_POST['pass1'] == $_POST['pass2']) {
			if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
			if (!defined('SYS_SECURITY')) require_once(BASE.'subsystems/security.php');
			$strength_error = exponent_security_checkPasswordStrength($user->username,$_POST['pass1']);
			if ($strength_error != '') {
				$post = $_POST;
				unset($post['pass1']);
				unset($post['pass2']);
				$post['_formError'] = sprintf($i18n['not_strong_enough'],$strength_error);
				exponent_sessions_set('last_POST',$post);
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				exponent_users_changepass($_POST['pass1']);
				exponent_flow_redirect();
			}
		} else { // Passwords don't match
			$post = $_POST;
			unset($post['pass1']);
			unset($post['pass2']);
			$post['_formError'] = $i18n['unmatched_passwords'];
			exponent_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else { // Old password incorrect
		$post = array('_formError'=>$i18n['bad_password']);
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	echo SITE_403_HTML;
}

?>
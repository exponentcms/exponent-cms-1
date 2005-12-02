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

if (!$user && SITE_ALLOW_REGISTRATION == 1) {
	$i18n = pathos_lang_loadFile('modules/loginmodule/actions/saveuser.php');

	$capcha_real = pathos_sessions_get('capcha_string');
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	if (!defined('SYS_SECURITY')) require_once(BASE.'subsystems/security.php');
	if (pathos_users_getUserByName($_POST['username']) != null) {
		$post = $_POST;
		unset($post['username']);
		$post['_formError'] = $i18n['username_taken'];
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
			$post['_formError'] = sprintf($i18n['not_strong_enough'],$strength_error);
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			// Finally, check the capcha
			if (SITE_USE_CAPTCHA && strtoupper($_POST['captcha_string']) != $capcha_real) {
				$post = $_POST;
				unset($post['captcha_string']);
				$post['_formError'] = $i18n['bad_captcha'];
				pathos_sessions_set('last_POST',$post);
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				pathos_sessions_unset('capcha_string');
				$u = pathos_users_create($_POST,null);
				$u = pathos_users_saveProfileExtensions($_POST,$u,true);
				pathos_users_login($_POST['username'],$_POST['pass1']);
				pathos_flow_redirect();
			}
		}
	}
} else {
	echo SITE_403_HTML;
}

?>

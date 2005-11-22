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
 
if (!defined('PATHOS')) exit('');

if ($user) {
	$i18n = pathos_lang_loadFile('modules/loginmodule/actions/savepass.php');
	
	if ($user->password == md5($_POST['oldpass'])) {
		if ($_POST['pass1'] == $_POST['pass2']) {
			if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
			if (!defined('SYS_SECURITY')) require_once(BASE.'subsystems/security.php');
			$strength_error = pathos_security_checkPasswordStrength($user->username,$_POST['pass1']);
			if ($strength_error != '') {
				$post = $_POST;
				unset($post['pass1']);
				unset($post['pass2']);
				$post['_formError'] = sprintf($i18n['not_strong_enough'],$strength_error);
				pathos_sessions_set('last_POST',$post);
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				pathos_users_changepass($_POST['pass1']);
				pathos_flow_redirect();
			}
		} else { // Passwords don't match
			$post = $_POST;
			unset($post['pass1']);
			unset($post['pass2']);
			$post['_formError'] = $i18n['unmatched_passwords'];
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else { // Old password incorrect
		$post = array('_formError'=>$i18n['bad_password']);
		pathos_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	echo SITE_403_HTML;
}

?>
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
 
if (!defined("PATHOS")) exit("");

if ($user) {
	if ($user->password == md5($_POST['oldpass'])) {
		if ($_POST['pass1'] == $_POST['pass2']) {
			if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
			if (!defined("SYS_SECURITY")) include_once(BASE."subsystems/security.php");
			$strength_error = pathos_security_checkPasswordStrength($user->username,$_POST['pass1']);
			if ($strength_error != "") {
				$post = $_POST;
				unset($post['pass1']);
				unset($post['pass2']);
				$post['_formError'] = "Your password is not strong enough : $strength_error";
				pathos_sessions_set("last_POST",$post);
				header("Location: " . $_SERVER['HTTP_REFERER']);
			} else {
				pathos_users_changepass($_POST['pass1']);
				pathos_flow_redirect();
			}
		} else { // Passwords don't match
			$post = $_POST;
			unset($post['pass1']);
			unset($post['pass2']);
			$post['_formError'] = "Passwords don't match";
			pathos_sessions_set("last_POST",$post);
			header("Location: " . $_SERVER['HTTP_REFERER']);
		}
	} else { // Old password incorrect
		$post = array("_formError"=>"Your old password is incorrect");
		pathos_sessions_set("last_POST",$post);
		header("Location: " . $_SERVER['HTTP_REFERER']);
	}
	
}

?>
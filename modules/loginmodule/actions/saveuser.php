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
if (!defined("PATHOS")) exit("");

if (!$user && SITE_ALLOW_REGISTRATION == 1) {
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	if (!defined("SYS_SECURITY")) include_once(BASE."subsystems/security.php");
	if (pathos_users_getUserByName($_POST['username']) != null) {
		$post = $_POST;
		unset($post['username']);
		$post['_formError'] = "Username already taken.";
		pathos_sessions_set("last_POST",$post);
		header("Location: " . $_SERVER['HTTP_REFERER']);
	} else if ($_POST['pass1'] != $_POST['pass2']) {
		$post = $_POST;
		unset($post['pass1']);
		unset($post['pass2']);
		$post['_formError'] = "Passwords don't match";
		pathos_sessions_set("last_POST",$post);
		header("Location: " . $_SERVER['HTTP_REFERER']);
	} else {
		$strength_error = pathos_security_checkPasswordStrength($_POST['username'],$_POST['pass1']);
		if ($strength_error != "") {
			$post = $_POST;
			unset($post['pass1']);
			unset($post['pass2']);
			$post['_formError'] = "Your password is not strong enough : $strength_error";
			pathos_sessions_set("last_POST",$post);
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			$u = pathos_users_create($_POST,null);
			$u = pathos_users_saveProfileExtensions($_POST,$u,true);
			pathos_users_login($_POST['username'],$_POST['pass1']);
			pathos_flow_redirect();
		}
	}
}

?>

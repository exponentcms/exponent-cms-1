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

if ($user && $user->is_admin == 1) {
	if (!defined("SYS_USERS")) include_once("subsystems/users.php");
	if (isset($_POST['id'])) { // Existing user profile edit
		$g = pathos_users_getGroupById($_POST['id']);
		$g = pathos_users_groupUpdate($_POST,$g);
		pathos_users_saveGroup($g);
		
		pathos_flow_redirect();
	} else {
		if (pathos_users_getGroupByName($_POST['name']) != null) {
			$post = $_POST;
			$post['_formError'] = "The group name name is already taken.";
			pathos_sessions_set("last_POST",$post);
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			$g = pathos_users_groupUpdate($_POST,$g);
			pathos_users_saveGroup($g);
			pathos_flow_redirect();
		}
	}
} else {
	echo SITE_403_HTML;
}

?>
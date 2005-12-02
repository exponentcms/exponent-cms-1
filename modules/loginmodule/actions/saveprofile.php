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

if ($user) {
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');
	$user = pathos_users_update($_POST,$user);
	$user = pathos_users_saveUser($user);
	$user = pathos_users_saveProfileExtensions($_POST,$user,false);
	$_SESSION[SYS_SESSION_KEY]['user'] = $user;
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
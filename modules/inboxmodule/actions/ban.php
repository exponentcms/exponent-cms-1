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

$u = null;
if (isset($_REQUEST['uid'])) {
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	$u = exponent_users_getUserById($_REQUEST['uid']);
}

if ($user && $u) {
	$ban = null;
	$ban->owner = $user->id;
	$ban->user_id = $u->id;
	$db->insertObject($ban,'inbox_contactbanned');
	exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>

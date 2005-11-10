<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

$u = null;
if (isset($_REQUEST['uid'])) {
	if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
	$u = pathos_users_getUserById((int)$_REQUEST['uid']);
}

if ($user && $u) {
	if ($user->id == $u->id || $u->is_acting_admin == 1) {
		// GREP:HARDCODEDTEXT
		echo 'You cannot ban yourself or an administrator from sending mail.';
	} else {
		$ban = null;
		$ban->owner = $user->id;
		$ban->user_id = $u->id;
		$db->insertObject($ban,"inbox_contactbanned");
		pathos_flow_redirect();
	}
} else {
	echo SITE_404_HTML;
}

?>

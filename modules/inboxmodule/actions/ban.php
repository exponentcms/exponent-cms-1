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

$contact = null;
$u = null;
if (isset($_REQUEST['cid'])) {
	$contact = $db->selectObject("inbox_contact","id=".$_REQUEST['cid']);
} else if (isset($_REQUEST['uid'])) {
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	$u = pathos_users_getUserById($_REQUEST['uid']);
}

if ($user && (($contact && $contact->owner == $user->id) || $u)) {
	$uid = 0;
	if ($contact) {
		$uid = $contact->user_id;
		$db->delete("inbox_contact","id=".$contact->id);
	} else $uid = $u->id;
	
	$ban = null;
	$ban->owner = $user->id;
	$ban->user_id = $uid;
	$db->insertObject($ban,"inbox_contactbanned");
	pathos_flow_redirect();
}

?>
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

$list = null;
if (isset($_POST['id'])) {
	$list = $db->selectObject('inbox_contactlist','id='.$_POST['id']);
}

if ($user && ($list == null || $list->owner == $user->id)) {
	$list = inbox_contactlist::update($_POST,$list);
	$list->owner = $user->id;
	
	if (isset($list->id)) {
		$db->updateObject($list,'inbox_contactlist');
	} else {
		$list->id = $db->insertObject($list,'inbox_contactlist');
	}
	
	$db->delete('inbox_contactlist_member','list_id='.$list->id);
	$member = null;
	$member->list_id = $list->id;
	
	foreach ($list->_members as $id) {
		if ($id != '') {
			$member->user_id = $id;
			$db->insertObject($member,'inbox_contactlist_member');
		}
	}
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
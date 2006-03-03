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

$list = null;
if (isset($_POST['id'])) {
	$list = $db->selectObject('inbox_contactlist','id='.intval($_POST['id']));
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
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
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

if (!defined('PATHOS')) exit('');

$item = null;
$channel = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject('channelitem','id='.$_GET['id']);
	if ($item) {
		$channel = $db->selectObject('channel','id='.$item->channel_id);
		if ($channel) {
			$loc = unserialize($channel->location_data);
		}
	}
}

if (!isset($_GET['accept'])) {
	$_GET['accept'] = 1;
}

if ($item && $channel) {
	if (pathos_permissions_check('manage_channel',$loc)) {
		$original = $db->selectObject($item->tablename,'id='.$item->item_id);
		
		if ($_GET['accept'] == 1) {
			// Insert CONVERSION stuff here
			if ($item->status == 1) { // New
				unset($original->id);
				$original->location_data = serialize($loc);
				$item->published_id = $db->insertObject($original,'newsitem');
		
				$item->status = 0;
				$db->updateObject($item,'channelitem');
			} else if ($item->status == 2) { // Edit
				$original->id = $item->published_id;
				$original->location_data = serialize($loc);
				$db->updateObject($original,'newsitem');
		
				$item->status = 0;
				$db->updateObject($item,'channelitem');
			} else if ($item->status == 3) { // Delete
				$db->delete('newsitem','id='.$item->published_id);
				$db->delete('channelitem','id='.$item->id);
			}
		} else {
			echo 'Declining';
			if ($item->status == 1) { // Declining a new submission
				$db->delete('channelitem','id='.$item->id);
			} else { // Declining a change to an existing submission
				$item->status = 0;
				$db->updateObject($item,'channelitem');
			}
		}
		
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
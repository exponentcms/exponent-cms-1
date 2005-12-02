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
			// The user has opted to accept this status change.
			
			// Because someone may have pulled a previously pulled item from us, we need to
			// update the status of other channel items where we are an item_id
			$other_item = null;
			$other_item->status = $item->status;
			
			// Insert CONVERSION stuff here
			if ($item->status == 1) {
				// Acceptance of a new item means inserting it into the item space of
				// the current news module.
				unset($original->id);
				$original->location_data = serialize($loc);
				$item->published_id = $db->insertObject($original,'newsitem');
				
				// After that, the status of the channel item needs to be changed to 0 (no action needed)
				$item->status = 0;
				$db->updateObject($item,'channelitem');
			} else if ($item->status == 2) {
				// Acceptance of an edit means updating the published item with the data from
				// the source item.
				$original->id = $item->published_id;
				$original->location_data = serialize($loc);
				$db->updateObject($original,'newsitem');
				
				// After that, the status of the channel item needs to be changed to 0 (no action needed)
				$item->status = 0;
				$db->updateObject($item,'channelitem');
			} else if ($item->status == 3) { // Delete
				$db->delete('newsitem','id='.$item->published_id);
				$db->delete('channelitem','id='.$item->id);
			}
			
			// Refire the change status event to anyone who pulled from us.  (See creation of $other_item, above)
			$db->updateObject($other_item,'channelitem','item_id='.$item->published_id);
		} else {
			// The user has opted to decline this status change.  No updates will
			// be made to the published item.
			if ($item->status == 1) {
				// Declining a new submission - remove the item from the channel.
				$db->delete('channelitem','id='.$item->id);
			} else {
				// Declining a change to an existing submission.
				// Note: if this was a delete notification, the channelitem will be gone the next
				// time they go to view the channel, since the link is invalid.
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
<?php

define('SYS_CHANNELS',1);

#define('SYS_CHANNELS_PREVIOUS',	0);
#define('SYS_CHANNELS_NEW',		1);
#define('SYS_CHANNELS_EDIT',		2);
#define('SYS_CHANNELS_DELETE',	3);

/* exdoc
 *
 * Get a list of types, with their user-friendly names.
 */
function pathos_channels_types() {
	
}

function pathos_channels_typeName($type) {

}

function pathos_channels_list($type) {
	global $db;
	$channels = array();
	foreach ($db->selectObjects('channel',"type='".$type."'") as $c) {
		$channels[$c->id] = $c->name;
	}
	return $channels;
}

/* exdoc
 *
 * Retrieves a list of the items in the channel.
 */
function pathos_channels_getItems($channel) {
	global $db;
	$items = array();
	$tmp = array();
	foreach ($db->selectObjects('channelitem','channel_id='.$channel->id) as $item) {
		if ($item->status == 1) {
			$tmp[0] = $db->selectObject($item->tablename,'id='.$item->item_id);
		} else {
			$tmp[0] = $db->selectObject($item->tablename,'id='.$item->published_id);
		}
		if ($tmp[0]) {
			$tmp[1] = $item;
			$items[] = $tmp;
		} else {
			$db->delete('channelitem','id='.$item->id);
		}
	}
	return $items;
}

function pathos_channels_getChannel($loc) {
	global $db;
	return $db->selectObject('channel',"location_data='".serialize($loc)."'");
}

function pathos_channels_hasItems($loc,$all = false) {
	$where = '';
	if (!$all) $where = ' AND status > 0';
	
	global $db;
	$channel = $db->selectObject('channel',"location_data='".serialize($loc)."'");
	if ($channel) {
		return $db->countObjects('channelitem','channel_id='.$channel->id.$where);
	}
	return false;
}

?>
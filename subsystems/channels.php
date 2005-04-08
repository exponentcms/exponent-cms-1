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

function pathos_channels_list($type,$exclude_loc = null) {
	global $db;
	if (is_object($exclude_loc)) {
		$exclude_loc = serialize($exclude_loc);
	}
	$channels = array();
	foreach ($db->selectObjects('channel',"type='".$type."' AND location_data != '".$exclude_loc."' AND name !=''") as $c) {
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
	$o = $db->selectObject('channel',"location_data='".serialize($loc)."'");
	if (!$o && is_callable(array($loc->mod,'channelType'))) {
		$o->location_data = serialize($loc);
		$o->is_open = 1;
		$o->name = '';
		$o->type = call_user_func(array($loc->mod,'channelType'));
		$o->id = $db->insertObject($o,'channel');
	}
	return $o;
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
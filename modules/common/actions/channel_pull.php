<?php

/* POST data
 *
 * items = array(
 *	type=>array(
 *		id=>on,
 *		id=>on
 *	)
 * )
 */

$channel = $db->selectObject('channel','id='.$_POST['channel_id']);
$loc = unserialize($channel->location_data);

$channel_item = null;
$channel_item->channel_id = $_POST['channel_id'];

foreach ($_POST['item'] as $type=>$ids) {
	$channel_item->tablename = $type;
	$channel_item->titlefield = 'title';
	$channel_item->viewlink = 'http://oicgroup.net';

	foreach ($ids as $id) {
		$channel_item->item_id = $id;
		if (pathos_permissions_check('manage_channel',$loc)) {
			echo 'Can manage channel.  Trying to post as real item<br />';
			// The poster has manage_channel in the channel destination.
			// Need to shoot this through sans-approval.
			$object = $db->selectObject($type,'id='.$id);
			unset($object->id);
			$object->location_data = serialize($loc);
			$channel_item->published_id = $db->insertObject($object,'newsitem');
			$channel_item->status = 0;
			$db->insertObject($channel_item,'channelitem');
		} else {
			$channel_item->status = 1; // Flag this as a new post.
			$channel_item->published_id = 0; // Set to zero, since we haven't published it yet.
			$db->insertObject($channel_item,'channelitem');
		}
	}
}

?>
<script type="text/javascript">
window.opener.location.href = window.opener.location.href
window.close();
</script>

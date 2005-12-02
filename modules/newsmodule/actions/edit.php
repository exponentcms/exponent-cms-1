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

if (!defined("PATHOS")) exit("");

$news = null;
$iloc = null;
if (isset($_GET['id'])) {
	$news = $db->selectObject("newsitem","id=" . intval($_GET['id']));
}

if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
}

if (($news != null && pathos_permissions_check("edit_item",$loc)) || 
	($news == null && pathos_permissions_check("add_item",$loc)) ||
	($iloc != null   && pathos_permissions_check("edit_item",$iloc)) 
) {
	$form = newsitem::form($news);
	$form->location($loc);
	$form->meta("action","save");
	
	// Get a list of channels that this newsitem has already been submitted to.
	$existing_channels = array();
	if (isset($news->id)) {
		foreach ($db->selectObjects('channelitem','item_id='.$news->id) as $item) {
			$existing_channels[$item->channel_id] = 1;
		}
	}
	
	if (!defined('SYS_CHANNELS')) require_once(BASE.'subsystems/channels.php');
	$channels = pathos_channels_list('post',$loc);
	if (count($channels)) {
		$form->registerBefore('submit',null,'',new htmlcontrol('<hr size="1" />Share with Other Modules'));
		foreach ($channels as $id=>$name) {
			$cb = new checkboxcontrol(false,true);
			if (isset($existing_channels[$id])) {
				$cb->default = true;
				$cb->disabled = true;
			}
			$form->registerBefore('submit','channels['.$id.']',$name,$cb);
		}
	}
	
	$template = new template("newsmodule","_form_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit", (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
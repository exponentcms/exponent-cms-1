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

$config = $db->selectObject("newsmodule_config","location_data='".serialize($loc)."'");
if ($config == null) {
	$config->sortorder = "ASC";
	$config->item_limit = 10;
}

$template = new template("newsmodule","_contentSelector",$loc);

$news = $db->selectObjects("newsitem","location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0 ORDER BY posted " . $config->sortorder);
if (isset($_GET['channel_id'])) {
	$items = array();
	foreach ($db->selectObjectsIndexedArray('channelitem','channel_id='.$_GET['channel_id']) as $item) {
		$items[$item->item_id] = 1;
	}
	
	$channel = $db->selectObject('channel','id='.$_GET['channel_id']);
	if ($channel->location_data == serialize($loc)) {
		foreach ($news as $item) {
			$items[$item->id] = 1;
		}
	}
	$template->assign('existing_items',$items);
}

if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
usort($news,($config->sortorder == "DESC" ? "pathos_sorting_byPostedDescending" : "pathos_sorting_byPostedAscending"));

$template->assign('haveNews',count($news) != 0 ? 1 : 0);
$template->assign("news",$news);

$template->output();

?>
<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (!defined("EXPONENT")) exit("");

exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
if (isset($_GET['id'])) {
	$news = $db->selectObject("newsitem","id=" . intval($_GET['id']));
} else if (isset($_GET['title'])) {
	$news = $db->selectObject("newsitem","title='" . router::decode($_REQUEST['title'])."'");
} else if (isset($_GET['internal_name'])) {
	$news = $db->selectObject("newsitem","internal_name='".$_GET['internal_name']."'".$where);
}

if ($news != null) {
	#Added to count reads of each story
	$old_read_count = $news->reads;
	$new_read_count = $old_read_count + 1;
	$news->reads = $new_read_count;
	$db->updateObject($news,"newsitem");
	
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
	
	$news->permissions = array(
		"edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$iloc)) ? 1 : 0),
		"delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$iloc)) ? 1 : 0),
		"administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$iloc)) ? 1 : 0)
	);
	$file = $db->selectObject("file","id=".$news->file_id);
	if(!empty($file)){
		$news->image = $file->directory.'/'.$file->filename;
		//$item->image = URL_FULL.$file->directory.'/'.$file->filename;
	}
	
//	$news->real_posted = ($news->publish != 0 ? $news->publish : $news->posted);
	$news->posted = ($news->publish != 0 ? $news->publish : $news->posted);
	if ($news->publish == 0) {$news->publish = $news->posted;}
	
	$view = (isset($_GET['view']) ? $_GET['view'] : "_viewSingle");
	$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
	
	$template = new template("newsmodule",$view,$loc);
	
	$template->assign("newsitem",$news);
	$template->assign('config', $config);		
	$template->assign("loc",$loc);
	$template->assign('moduletitle',$title);
	
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>

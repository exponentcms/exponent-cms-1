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

if (!defined('EXPONENT')) exit('');

$config = $db->selectObject("newsmodule_config","location_data='".serialize($loc)."'");
if ($config == null) {
	$config->sortorder = "ASC";
	$config->item_limit = 10;
}

$locsql = "(location_data='".serialize($loc)."'";
if (!empty($config->aggregate)) {
	$locations = unserialize($config->aggregate);
	foreach ($locations as $source) {
		$tmploc = null;
		$tmploc->mod = 'newsmodule';
		$tmploc->src = $source;
		$tmploc->int = '';
		$locsql .= " OR location_data='".serialize($tmploc)."'";
	}
}
$locsql .= ')';

$canviewapproval = false;
if ($user) $canviewapproval = exponent_permissions_check("approve",$loc) || exponent_permissions_check("manage_approval",$loc);
if (!$canviewapproval) { // still not able to view
//	foreach($db->selectObjects("newsitem","location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0") as $post) {
	foreach($db->selectObjects("newsitem",$locsql . " AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0") as $post) {
		if ($user && $user->id == $post->poster) {
			$canviewapproval = true;
			break;
		}
	}
}

$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");

$template = new template("newsmodule",(isset($_GET['view']) ? $_GET['view']:"Default"),$loc);
$template->register_permissions(
	array("administrate","configure","add_item","delete_item","edit_items","manage_approval"),
	$loc
);

//$news = $db->selectObjects("newsitem","location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0 ORDER BY publish ".$config->sortorder);
$news = $db->selectObjects("newsitem",$locsql . " AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0 ORDER BY publish ".$config->sortorder);
for ($i = 0; $i < count($news); $i++) {
	$news[$i]->posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
	if ($news[$i]->publish == 0) {$news[$i]->publish = $news[$i]->posted;}
}

foreach ($news as $item){
	$file = $db->selectObject("file","id=".$item->file_id);
	if(!empty($file)){
		$item->image = $file->directory.'/'.$file->filename;
		//$item->image = URL_FULL.$file->directory.'/'.$file->filename;
	}
}

for ($i = 0; $i < count($news); $i++) {
	$nloc = null;
	$nloc->mod = $loc->mod;
	$nloc->src = $loc->src;
	$nloc->int = $news[$i]->id;
	
	$news[$i]->permissions = array(
		"edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$nloc)) ? 1 : 0),
		"delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$nloc)) ? 1 : 0),
		"administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$nloc)) ? 1 : 0)
	);
//  $news[$i]->real_posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
  $news[$i]->posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
  if ($news[$i]->publish == 0) {$news[$i]->publish = $news[$i]->posted;}
}

switch($config->sortfield) {
	case "posted":
		$field = "Posted";
		break;
	case "publish":
		$field = "Published";
		break;
	case "edited":
		$field = "Edited";
		break;
	default:
		$field = "Posted";
		break;
}

if ($config->sortorder == "ASC") {
	$order = "Ascending";
} else {
	$order = "Descending";
}

$sortFunc = 'exponent_sorting_by'.$field.$order;

if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
usort($news,$sortFunc);
$news = array_slice($news, 0, $config->item_limit);

// EVIL WORKFLOW
//$in_approval = $db->countObjects("newsitem_wf_info","location_data='".serialize($loc)."'");
$in_approval = $db->countObjects("newsitem_wf_info",$locsql);
$template->assign("canview_approval_link",$canviewapproval);
$template->assign("in_approval",$in_approval);
$template->assign("news",$news);
$template->assign("morenews",0);
$template->assign('config', $config);	
$template->assign('moduletitle',$title);

$template->output();

?>

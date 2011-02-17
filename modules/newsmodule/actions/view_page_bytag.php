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

exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

$loc->int = '';  // remove the rouge item id

$config = $db->selectObject("newsmodule_config","location_data='".serialize($loc)."'");
if ($config == null) {
	$config->sortorder = "DESC";
	$config->sortfield = "Posted";
	$config->item_limit = 10;
	$config->enable_pagination = 0;
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

if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
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

$template = new template("newsmodule","_view_page_bytag",$loc);
if ($template->viewconfig['featured_only']) {
	$locsql .= " AND is_featured=1 ";
}

// //$total = $db->countObjects('newsitem',"location_data='".serialize($loc)."' AND (publish = 0 or publish <= " . 
// //	time() . ") AND (unpublish = 0 or unpublish > " . time() . $featuresql . ") AND approved != 0");
// $total = $db->countObjects('newsitem',$locsql . " AND (publish = 0 or publish <= " . 
	// time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0");
	
// if($config->enable_pagination == 0){
// //	$news = $db->selectObjects("newsitem","location_data='" . 
// //	serialize($loc) . "' AND (publish = 0 or publish <= " . 
// //	time() . ") AND (unpublish = 0 or unpublish > " . time() . $featuresql . ") AND approved != 0 ORDER BY publish ".$config->sortorder);
	// $news = $db->selectObjects("newsitem",$locsql . " AND (publish = 0 or publish <= " . 
	// time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0 ORDER BY ".
	// $config->sortfield.' ' . $config->sortorder);
// } else {			
// //	$news = $db->selectObjects("newsitem","location_data='". 
// //	serialize($loc) . "' AND (publish = 0 or publish <= ". 
// //	time() . ') AND (unpublish = 0 or unpublish > ' . time() . $featuresql . ') AND approved != 0 ORDER BY '.
// //	$config->sortfield.' ' . $config->sortorder. 
// //	$db->limit($config->item_limit,($_GET['page']*$config->item_limit)));
	// $news = $db->selectObjects("newsitem",$locsql . " AND (publish = 0 or publish <= ". 
	// time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.
	// $config->sortfield.' ' . $config->sortorder. 
	// $db->limit($config->item_limit,($_GET['page']*$config->item_limit)));
// }

// for ($i = 0; $i < count($news); $i++) {
	// $nloc = null;
	// $nloc->mod = $loc->mod;
	// $nloc->src = $loc->src;
	// $nloc->int = $news[$i]->id;
	
	// $news[$i]->permissions = array(
		// "edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$nloc)) ? 1 : 0),
		// "delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$nloc)) ? 1 : 0),
		// "administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$nloc)) ? 1 : 0)
	// );
// //	$news[$i]->real_posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
	// $news[$i]->posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
	// if ($news[$i]->publish == 0) {$news[$i]->publish = $news[$i]->posted;}	
// }



$where = $locsql . " AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " .
	time() . ") AND approved != 0 ORDER BY " . $config->sortfield . ' ' . $config->sortorder;

$all_news = $db->selectObjects('newsitem', $where);
$viewing_tag = $db->selectObject('tags', "id=".intval($_REQUEST['id']));
$news = array();
for ($i = 0; $i < count($all_news); $i++) {
	$all_news[$i]->posted = ($all_news[$i]->publish != 0 ? $all_news[$i]->publish : $all_news[$i]->posted);
	if ($all_news[$i]->publish == 0) {$all_news[$i]->publish = $all_news[$i]->posted;}
	$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$all_news[$i]->id);
	$not_there = true;
	$tags = unserialize($all_news[$i]->tags);
	$selected_tags = $db->selectObjectsInArray('tags', $tags);
	//eDebug($selected_tags);
	for ($j=0; $j < count($tags); $j++){
		if ($tags[$j] == intval($_REQUEST['id'])) $not_there = false;
	}
	if ($not_there == false) {
		$newsitem = $all_news[$i];
		$newsitem->selected_tags = $selected_tags;
		$newsitem->permissions = array(
			'administrate'=>exponent_permissions_check('administrate',$ploc),
			'configure'=>exponent_permissions_check('configure',$ploc),
			'add_item'=>exponent_permissions_check('add_item',$ploc),
			'delete_item'=>exponent_permissions_check('delete_item',$ploc),
			'edit_items'=>exponent_permissions_check('edit_items',$ploc),
			'manage_approval'=>exponent_permissions_check('manage_approval',$ploc),
		);
		array_push($news, $newsitem);
	}
}

usort($news,$sortFunc);
$total = count($news);
$news = array_slice($news,($_REQUEST['page'] * $config->item_limit),$config->item_limit,true);

//If rss is enabled tell the view to show the RSS button
if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
$template->assign('enable_rss', $config->enable_rss);

// EVIL WORKFLOW
//$in_approval = $db->countObjects("newsitem_wf_info","location_data='".serialize($loc)."'");
$in_approval = $db->countObjects("newsitem_wf_info",$locsql);
$template->assign("canview_approval_link",$canviewapproval);
$template->assign("in_approval",$in_approval);
$template->assign("news",$news);

//pagination
$template->assign('total_news',$total);
$template->assign('item_limit', $config->item_limit);
$template->assign('shownext',(($_GET['page']+1)*$config->item_limit) < $total);
$template->assign('page',$_GET['page']);
$template->assign('enable_pagination', $config->enable_pagination);
$template->assign('morenews', count($news) < $total);

$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
//$template->assign('moduletitle',$title);
$template->assign('moduletitle',$title." (<em>tagged with '".$viewing_tag->name."'</em>)");
$template->assign('viewing_tag',$viewing_tag);
$template->register_permissions(
	array("administrate","configure","add_item","delete_item","edit_items","manage_approval"),
	$loc
);
$template->assign('config', $config);		

$template->output();

?>
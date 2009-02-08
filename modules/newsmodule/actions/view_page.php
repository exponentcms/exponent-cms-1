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
	$config->enable_pagination = 0;
}

$canviewapproval = false;
if ($user) $canviewapproval = exponent_permissions_check("approve",$loc) || exponent_permissions_check("manage_approval",$loc);
if (!$canviewapproval) { // still not able to view
	foreach($db->selectObjects("newsitem","location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0") as $post) {
		if ($user && $user->id == $post->poster) {
			$canviewapproval = true;
			break;
		}
	}
}


$template = new template("newsmodule","_view_page",$loc);

$template->register_permissions(
	array("administrate","configure","add_item","delete_item","edit_items","manage_approval"),
	$loc
);

$total = $db->countObjects('newsitem',"location_data='".serialize($loc)."' AND (publish = 0 or publish <= " . 
		time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0");

if($config->enable_pagination == 0){
		$news = $db->selectObjects("newsitem","location_data='" . 
		serialize($loc) . "' AND (publish = 0 or publish <= " . 
		time() . ") AND (unpublish = 0 or unpublish > " . time() . ") AND approved != 0 ORDER BY publish ".$config->sortorder);
}else{		
		
		$news = $db->selectObjects("newsitem","location_data='". 
						serialize($loc) . "' AND (publish = 0 or publish <= ". 
						time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.
						$config->sortfield.' ' . $config->sortorder. 
						$db->limit($config->item_limit,($_GET['page']*$config->item_limit)));
		}

	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($news,($config->sortorder == "DESC" ? "exponent_sorting_byPostedDescending" : "exponent_sorting_byPostedAscending"));

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
  $news[$i]->real_posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
}
// EVIL WORKFLOW
$in_approval = $db->countObjects("newsitem_wf_info","location_data='".serialize($loc)."'");
$template->assign("canview_approval_link",$canviewapproval);
$template->assign("in_approval",$in_approval);
$template->assign("news",$news);

//pagination
$template->assign('total_news',$total);
$template->assign('item_limit', $config->item_limit);
$template->assign('shownext',(($_GET['page']+1)*$config->item_limit) < $total);
$template->assign('page',$_GET['page']);
$template->assign('enable_pagination', $config->enable_pagination);



$template->assign("morenews",0);

$template->output();

?>
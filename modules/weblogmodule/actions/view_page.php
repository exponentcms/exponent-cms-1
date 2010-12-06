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

$template = new template('weblogmodule','_view_page',$loc);

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
$viewconfig = $template->viewconfig;
if ($config == null) {
	$config->allow_comments = 1;
	$config->items_per_page = 10;
}
if ($viewconfig === null) {
	$viewconfig = array("type"=>"default");
	$viewconfig = array("num_posts"=>"0");
}

if (isset($_GET['single'])) {
	$config->items_per_page = 1;
	$viewconfig->num_posts = 1;
}
global $user;
$user_id = ($user ? $user->id : -1);

// If this module has been configured to aggregate then setup the where clause to pull
// posts from the proper blogs.
$locsql = "(location_data='".serialize($loc)."'";
if (!empty($config->aggregate)) {
	$locations = unserialize($config->aggregate);
	foreach ($locations as $source) {
		$tmploc = null;
		$tmploc->mod = 'weblogmodule';
		$tmploc->src = $source;
		$tmploc->int = '';
		$locsql .= " OR location_data='".serialize($tmploc)."'";
	}
}
$locsql .= ')';
$locsql .= " AND (publish = 0 or publish <= " . time() . " or poster=" . $user_id .
	') AND (unpublish = 0 or unpublish > ' . time() . " or poster=" . $user_id . ') '; 
if ($user->is_admin || $user->is_acting_admin) {
	$where = $locsql;
} else {	
	$where = '(is_draft = 0 OR poster = '.($user ? $user->id : -1).") AND ".$locsql;
}
if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';

//$where = "location_data='".serialize($loc)."' AND (is_draft = 0 OR poster = ".($user ? $user->id : -1).")";
//if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';;

$total = $db->countObjects('weblog_post',$where);
//$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY posted DESC '.$db->limit($config->items_per_page,($_GET['page']*$config->items_per_page)));
//$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY posted DESC '.$db->limit($viewconfig->num_posts,($_GET['page']*$viewconfig->num_posts)));
$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY publish DESC '.$db->limit($config->items_per_page,($_GET['page']*$config->items_per_page)));
if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
for ($i = 0; $i < count($posts); $i++) {
	$posts[$i]->posted = ($posts[$i]->publish != 0 ? $posts[$i]->publish : $posts[$i]->posted);
	if ($posts[$i]->publish == 0) {$posts[$i]->publish = $posts[$i]->posted;}
	$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$posts[$i]->id);
	
	$posts[$i]->permissions = array(
		'administrate'=>exponent_permissions_check('administrate',$ploc),
		'edit'=>exponent_permissions_check('edit',$ploc),
		'delete'=>exponent_permissions_check('delete',$ploc),
		'comment'=>exponent_permissions_check('comment',$ploc),
		'approve_comments'=>exponent_permissions_check('approve_comments',$ploc),
		'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
		'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
		'view_private'=>exponent_permissions_check('view_private',$ploc),
	);
	if (!exponent_permissions_check('approve_comments',$ploc) && $config->approve_comments) {
		$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id." AND approved=1");
	} else {
		$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id);
	}
	usort($comments,'exponent_sorting_byPostedDescending');
	$posts[$i]->comments = $comments;
	$posts[$i]->total_comments = count($comments);
}
//usort($posts,'exponent_sorting_byPostedDescending');
usort($posts,'exponent_sorting_byPublishedDescending');
$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");

//If rss is enabled tell the view to show the RSS button
if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
$template->assign('enable_rss', $config->enable_rss);
$template->assign('posts',$posts);
$template->assign('total_posts',$total);
$template->assign('shownext',(($_GET['page']+1)*$config->items_per_page) < $total);
//$template->assign('shownext',(($_GET['page']+1)*$viewconfig->numposts) < $total);
$template->assign('page',$_GET['page']);

$monitoring = false;
if ($user && ($user->id!=0)) {
	$weblog_monitor = null;
	$weblog_monitor = $db->selectObject("weblog_monitor","weblog_id=".$config->id." AND user_id=".$user->id);
	if ($weblog_monitor != null) $monitoring = true;
}
$template->assign("monitoring", $monitoring);
$template->assign('logged_in', exponent_users_isLoggedIn());
if (isset($_GET['view'])) {
	$view = $_GET['view']? $_GET['view'] : 0;
}
$template->assign("view", $view);
$template->register_permissions(
	array('administrate','configure','post','edit','delete','comment','approve_comments','edit_comments','delete_comments','view_private'),
	$loc);
$template->assign('moduletitle',$title);
$template->assign('config',$config);
//$template->assign('viewconfig',$template->viewconfig);
//$template->assign('num_posts',$viewconfig['num_posts']);
//$template->assign('paging',$viewconfig['paging']);
$template->output();

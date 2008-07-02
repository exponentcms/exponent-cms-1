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

if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
if ($config == null) $config->allow_comments = 1;

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
$where = '(is_draft = 0 OR poster = '.$user->id.") AND ".$locsql;

$all_posts = $db->selectObjects('weblog_post', $where);
$viewing_tag = $db->selectObject('tags', "id=".intval($_REQUEST['id']));
$posts = array();
if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
for ($i = 0; $i < count($all_posts); $i++) {
	$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$all_posts[$i]->id);
	$not_there = true;
	$tags = unserialize($all_posts[$i]->tags);
	$selected_tags = $db->selectObjectsInArray('tags', $tags);
	//eDebug($selected_tags);
	for ($j=0; $j < count($tags); $j++){
		if ($tags[$j] == intval($_REQUEST['id'])) $not_there = false;
	}
	if ($not_there == false) {
		$post = $all_posts[$i];
		$post->selected_tags = $selected_tags;
		$post->permissions = array(
			'administrate'=>exponent_permissions_check('administrate',$ploc),
			'edit'=>exponent_permissions_check('edit',$ploc),
			'delete'=>exponent_permissions_check('delete',$ploc),
			'comment'=>exponent_permissions_check('comment',$ploc),
			'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
			'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
			'view_private'=>exponent_permissions_check('view_private',$ploc),
		);
		$comments = $db->selectObjects('weblog_comment','parent_id='.$all_posts[$i]->id);
		
		usort($comments,'exponent_sorting_byPostedDescending');
		$post->comments = $comments;
		$post->total_comments = count($comments);
		array_push($posts, $post);
	}
}
usort($posts,'exponent_sorting_byPostedDescending');
$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
$template = new template('weblogmodule','Summary',$loc);
$template->assign('posts',$posts);
$template->assign('moduletitle',$title);
$template->assign('viewing_tag',$viewing_tag);
$template->register_permissions(
	array('administrate'/*,'configure'*/,'post','edit','delete','comment','edit_comments','delete_comments','view_private'),
	$loc);
$template->assign('config',$config);
$template->output();

?>

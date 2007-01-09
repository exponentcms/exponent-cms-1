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
# $Id: view_board.php,v 1.4 2005/04/26 03:03:16 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

require (BASE."subsystems/pagingObject.php");

if (!defined("SYS_USERS")) require(BASE."subsystems/users.php");
$users = array();

// Board
$bb = $db->selectObject("bb_board","id=". (int)$_GET['id']);
$loc = unserialize($bb->location_data);

$bbmod_config = $db->selectObject("bbmodule_config", "location_data='".serialize($loc)."'");  
if ($bbmod_config != null ) {
  $itemsperpage = $bbmod_config->items_perpage;
} else {
  $itemsperpage = 25;
}

$where = "board_id=".$bb->id . " AND parent=0 AND is_announcement=0 AND is_sticky=0 ";

$threadsPaging = new PagingObject(
  isset($_GET['page']) ? (int) $_GET['page'] : 1,
  $db->countObjects("bb_post", $where),
  $itemsperpage
);

// Calculate pages and get page count
$itemcount = $db->countObjects("bb_post", $where);
$pageid = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$pageid--;
$where .= " ORDER BY updated";
$where .= " LIMIT " . $threadsPaging->GetOffSet();

// Get posts / announcements / stickys 
$posts = $db->selectObjects("bb_post", $where);
$announcements = $db->selectObjects("bb_post","board_id=".$bb->id . " AND parent=0 AND is_announcement=1");
$stickys = $db->selectObjects("bb_post","board_id=".$bb->id . " AND parent=0 AND is_sticky=1");

// Get users for each post
for ($i = 0; $i < count($posts); $i++) {
	if (!isset($users[$posts[$i]->poster])) $users[$posts[$i]->poster] = exponent_users_getUserById($posts[$i]->poster);
	$posts[$i]->user = $users[$posts[$i]->poster];
	if ($posts[$i]->editted != 0) {
		if (!isset($users[$posts[$i]->editor])) $users[$posts[$i]->editor] = exponent_users_getUserById($posts[$i]->poster);
		$posts[$i]->editor = $users[$posts[$i]->editor];
	}
  		
	$last_reply = null;
	$last_reply = $db->selectObject("bb_post", "id=".$posts[$i]->last_reply);
	if($last_reply) $posts[$i]->last_poster = $db->selectObject("user", "id=".$last_reply->poster);
}

// Get users for announcements
for ($i = 0; $i < count($announcements); $i++) {
        if (!isset($users[$announcements[$i]->poster])) $users[$announcements[$i]->poster] = exponent_users_getUserById($announcements[$i]->poster);
        $announcements[$i]->user = $users[$announcements[$i]->poster];
        if ($announcements[$i]->editted != 0) {
                if (!isset($users[$announcements[$i]->editor])) $users[$announcements[$i]->editor] = exponent_users_getUserById($announcements[$i]->poster);
                $announcements[$i]->editor = $users[$announcements[$i]->editor];
        }
	$last_reply = $db->selectObject("bb_post", "id=".$announcements[$i]->last_reply);
  	$announcements[$i]->last_poster = $db->selectObject("user", "id=".$last_reply->poster);
}

// Get users for stickys
for ($i = 0; $i < count($stickys); $i++) {
        if (!isset($users[$stickys[$i]->poster])) $users[$stickys[$i]->poster] = exponent_users_getUserById($stickys[$i]->poster);
        $stickys[$i]->user = $users[$stickys[$i]->poster];
        if ($stickys[$i]->editted != 0) {
                if (!isset($users[$stickys[$i]->editor])) $users[$stickys[$i]->editor] = exponent_users_getUserById($stickys[$i]->poster);
                $stickys[$i]->editor = $users[$stickys[$i]->editor];
        }
	$last_reply = $db->selectObject("bb_post", "id=".$stickys[$i]->last_reply);
  	$stickys[$i]->last_poster = $db->selectObject("user", "id=".$last_reply->poster);
}

/* Sort posts
if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
if (!function_exists("exponent_sorting_byUpdatedDescending")) {
	function exponent_sorting_byUpdatedDescending($a,$b) {
		return ($a->updated > $b->updated ? -1 : 1);
	}
}
usort($posts,"exponent_sorting_byUpdatedDescending");
*/

//Update the last visited date for this user on this board.
global $user;
if ($user) {
  $db->delete('bb_user_board_visit', "board_id=".$bb->id." AND user_id=".$user->id);
  $last_visit = null;
  $last_visit->board_id = $bb->id;
  $last_visit->user_id = $user->id;
  $last_visit->last_visit = time();
  $db->insertObject($last_visit, 'bb_user_board_visit');  
}

$bbloc = exponent_core_makeLocation($loc->mod,$loc->src,"b".$bb->id);

$template = new template("bbmodule","_view",$loc);
$template->assign("board",$bb);
$template->assign("threads",$posts);
$template->assign("announcements",$announcements);
$template->assign("stickys",$stickys);

// Assign page data
$template->assign("curpage", $threadsPaging->GetCurrentPage());
$template->assign("pagecount",$threadsPaging->GetPageCount());
$template->assign("uplimit", $threadsPaging->GetUpperLimit());
$template->assign("downlimit", $threadsPaging->GetLowerLimit());

$template->register_permissions(
	array("administrate","configure","create_thread","delete_thread"),
	$bbloc
);

$template->assign("monitoring",($user && $db->selectObject("bb_boardmonitor","user_id=".$user->id." AND board_id=".$bb->id) != null ? 1 : 0));
$template->assign("loggedin",($user!= null ? 1 : 0));
$template->output();

?>

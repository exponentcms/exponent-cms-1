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
# $Id: view_thread.php,v 1.4 2005/04/26 03:03:16 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$bb = null;
$post = $db->selectObject("bb_post","id=".$_GET['id']);
	
//update the number of times this post has been viewed
$post->num_views++;
$db->updateObject($post, 'bb_post');

if ($post && $post->parent != 0) $post = $db->selectObject("bb_post","id=".$post->parent);
if ($post) $bb = $db->selectObject("bb_board","id=".$post->board_id);

if ($post && $bb) {
	$loc = unserialize($bb->location_data);
	exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
	$loc->int = "b".$bb->id;

	$replies = $db->selectObjects("bb_post","parent=".$post->id);
	
	global $user;
	$users = array();
	if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");

	//Get the user data (including the bb profile extension)	
	$users[$post->poster] = exponent_users_getUserById($post->poster);
	$post->poster = exponent_users_getFullProfile($users[$post->poster]);
	//Get the rank(s) of the poster
	$mainloc = exponent_core_makeLocation("bbmodule", $_GET['src']);
	$rank_ids = $db->selectObjects('bb_ranks_users', "user_id=".$post->poster->id." AND location_data='".serialize($mainloc)."'");
	$ranks = null;
	for ($i = 0; $i < count($rank_ids); $i++) {
		$ranks[$i] = $db->selectObject('bb_rank', "id=".$rank_ids[$i]->rank_id);
	}
	$post->poster->ranks = $ranks;

	//Get the users avatar image if available
	$avatar = null;
	$avatar = $db->selectObject("file", "id=".$post->poster->bb_user->file_id);  //get the users avatar
	if ($avatar != null && $avatar != "") {
		$post->poster->avatar_path = $avatar->directory.'/'.$avatar->filename;
	} else {
		$post->poster->avatar_path = "";
	}
	//eDebug($post);
	//die();
	for ($i = 0; $i < count($replies); $i++) {
		//Get the user data (including the bb profile extension)	
		$users[$replies[$i]->poster] = exponent_users_getUserById($replies[$i]->poster);
	        $replies[$i]->poster = exponent_users_getFullProfile($users[$replies[$i]->poster]);

        	$rank_ids = $db->selectObjects('bb_ranks_users', "user_id=".$replies[$i]->poster->id." AND location_data='".serialize($mainloc)."'");
		$ranks = null;
        	for ($y = 0; $y < count($rank_ids); $y++) {
			$ranks[$y] = $db->selectObject('bb_rank', "id=".$rank_ids[$y]->rank_id);
        	}
        	$replies[$i]->poster->ranks = $ranks;

		//Get the users avatar image if available
		$avatar = null;
   	$avatar = $db->selectObject("file", "id=".$replies[$i]->poster->bb_user->file_id);  //get the users avatar
		if ($avatar != null && $avatar != "") {
        		$replies[$i]->poster->avatar_path = $avatar->directory.'/'.$avatar->filename;
		} else {
			$replies[$i]->poster->avatar_path = "";
   	}
	}
	//eDebug($replies);
	//die();
  
  if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
  usort($replies,'exponent_sorting_byPostedAscending');
	
	$template = new template("bbmodule","_view_thread",$loc);
	$template->assign("thread",$post);
	$template->assign("replies",$replies);
	$template->register_permissions(
		array("administrate","create_thread","delete_thread","edit_post","reply"),
		$loc
	);

  $template->assign('board_name', $bb->name);
  $template->assign('board_id', $bb->id);

	//Get the users bb_user profile data and pass it to the template	
	$currentuser = exponent_users_getFullProfile($user);
        if (isset($currentuser->bb_user)) {
		$template->assign("currentuser", $currentuser);
        }

	$template->assign("monitoring",($user && $db->selectObject("bb_threadmonitor","user_id=".$user->id." AND thread_id=".$post->id) != null ? 1 : 0));
	$template->assign("loggedin",($user!= null ? 1 : 0));
	$template->output();
	
} else {
	echo SITE_404_HTML;
}

?>
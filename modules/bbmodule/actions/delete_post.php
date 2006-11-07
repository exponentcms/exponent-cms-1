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
# $Id: delete_post.php,v 1.4 2005/03/28 23:47:47 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$post = $db->selectObject("bb_post","id=".$_GET['id']);
if ($post) {
	function tmp_realignLastPost($bb,$db) {
		if ($db->countObjects("bb_post","board_id=".$bb->id) == 0) $bb->last_post_id = 0;
		else {
			$bb->last_post_id = $db->max("bb_post","id","board_id","board_id=".$bb->id);
		}
		$db->updateObject($bb,"bb_board");
	}

	$bb = $db->selectObject("bb_board","id=".$post->board_id);
	if ($bb) {
		$bbloc = unserialize($bb->location_data);
		$bbloc->int = "b".$bb->id;
		
		if (exponent_permissions_check("delete_thread",$bbloc)) {
			$lastbb_post = $db->selectObject("bb_post","id=".$bb->last_post_id);
		
			if ($post->parent == 0) { // thread delete
				$db->delete("bb_post","id=".$post->id ." OR parent=".$post->id);
				
				$bb->num_topics--;
				$db->updateObject($bb,"bb_board");
				
				if ($lastbb_post->parent == $post->id || $lastbb_post->id == $post->id) {
					// Deleted the last post.
					tmp_realignLastPost($bb,$db);
				}
				
				header('Location: '.URL_FULL.'index.php?module=bbmodule&src='.$bbloc->src.'&int=&action=view_board&id='.$bb->id);
				exit();
			} else {
				$db->delete("bb_post","id=".$post->id);
				
				$parent = $db->selectObject("bb_post","id=".$post->parent);
				$parent->num_replies--;
				$db->updateObject($parent,"bb_post");
				
				if ($lastbb_post->id == $post->id) {
					// Deleted the last post.
					tmp_realignLastPost($bb,$db);
				}
				
				header('Location: '.URL_FULL.'index.php?module=bbmodule&src='.$bbloc->src.'&int=&action=view_thread&id='.$parent->id);
				exit();
			}
		}
	} else echo SITE_404_HTML;
} else echo SITE_404_HTML;

?>
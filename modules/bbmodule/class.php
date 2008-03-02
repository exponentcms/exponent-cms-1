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
# $Id: class.php,v 1.7 2005/05/09 05:46:10 filetreefrog Exp $
##################################################

class bbmodule {
	function name() { return "Bulletin Board"; }
	function description() { return "A lightweight bulletin board system that manages a set of boards, and flat-threads."; }
	function author() { return "OIC Group, Inc"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"create_board"=>"Create Board",
				"edit_board"=>"Edit Boards",
				"delete_board"=>"Delete Boards"
			);
		} else if (substr($internal,0,1) == "p") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"delete_thread"=>"Delete Post / Replies",
				"edit_post"=>"Edit Post / Replies",
				"reply"=>"Post Replies",
				"announcements"=>"Post Announcements",
				"stickies"=>"Post Sticky Threads"
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"edit_board"=>"Edit Board",
				"delete_board"=>"Delete Board",
				"create_thread"=>"Start Threads",
				"delete_thread"=>"Delete Posts",
				"edit_post"=>"Edit Posts",
				"reply"=>"Post Replies",
				"announcements"=>"Post Announcements",
				"stickies"=>"Post Sticky Threads"
			);
		}
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
    global $user;

		if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		
		$directory = 'files/bbmodule/avatars/';
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		$boards = $db->selectObjects("bb_board","location_data='".serialize($loc)."'");
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src);
		for ($i = 0; $i < count($boards); $i++) {
			$iloc->int = "b" . $boards[$i]->id;
			$boards[$i]->permissions = array(
				"edit_board"=>exponent_permissions_check("edit_board",$iloc),
				"delete_board"=>exponent_permissions_check("delete_board",$iloc)
			);
			if ($boards[$i]->last_post_id != 0) {
				$lp = $db->selectObject("bb_post","id=".$boards[$i]->last_post_id);
				$lp->poster = exponent_users_getUserById($lp->poster);
				$boards[$i]->last_post = $lp;
			} else {
				$boards[$i]->last_post = null;
			}

      if ($user) {
        $last_visit = null;
        $last_visit = $db->selectObject('bb_user_board_visit', "board_id=".$boards[$i]->id." AND user_id=".$user->id);
          if ($last_visit == null) {
            $boards[$i]->num_posts_since_last_visit = $db->countObjects('bb_post', "board_id=".$boards[$i]->id);
          } else {
            $boards[$i]->num_posts_since_last_visit = count($db->selectObjects('bb_post', "board_id=".$boards[$i]->id." AND updated  > ".time()));
          }
      }
		}

    $monitoring = false;
    $loggedin = false;
    if ($user) {
        $loggedin = true;
        foreach ($boards as $board) {
            $board_monitor = null;
            $board_monitor = $db->selectObject("bb_boardmonitor","board_id=".$board->id." AND user_id=".$user->id);
            if ($board_monitor != null) $monitoring = true;

            $last_visit = $db->selectObject('bb_user_board_visit', "board_id=".$board->id." AND user_id=".$user->id);
            $board->num_posts_since_last_visit = $db->countObjects('bb_post', "board_id=".$board->id." AND updated  > ".time());
            if ($board->num_posts_since_last_visit == 0) {
              $board->num_posts_since_last_visit = $db->countObjects('bb_post', "board_id=".$board->id);
            }
        }
    } else { 
        $board->num_posts_since_last_visit = "N/A";
    } 


		$template = new template("bbmodule",$view,$loc);
		$template->assign("moduletitle",$title);
		$template->assign("boards",$boards);
    $template->assign("monitoring", $monitoring);
    $template->assign("loggedin", $loggedin);
		$template->register_permissions(
			array("administrate","configure","create_board","edit_board","delete_board"),
			$loc
		);
    
    //If the option to show online users is enabled, get the user data
    $show_users = false;
    $bbmod_config = $db->selectObject("bbmodule_config", "location_data='".serialize($loc)."'");  
    if ($bbmod_config != null && $bbmod_config->whos_online == true) {
      $users_online = $this->whoisOnline();
      $num_members = count($users_online);
      $anon_users = $this->total_anonymous_users();
      $total_users = $num_members + $anon_users;
      $show_users = true;

		  $template->assign("users_online",$users_online);
		  $template->assign("anon_users",$anon_users);
		  $template->assign("num_members",$num_members);
		  $template->assign("total_users",$total_users);
    }

    $template->assign("show_users", $show_users);
		$template->output();
	}

  function whoisOnline() {
    global $db;
    $db->delete('sessionticket','last_active < ' . (time() - SESSION_TIMEOUT));

    if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
    if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');

    $sessions = $db->selectObjects('sessionticket', "uid != 0");
    $users = array();
    for ($i = 0; $i < count($sessions); $i++) {
      $user = exponent_users_getUserById($sessions[$i]->uid);
      $users[$user->id] = $user;
    }

    return $users;
  }

  function total_anonymous_users() {
    	global $db;
		$timeout = time() - 3600;  // look for the last hour
		$anonymous = $db->countObjects("sessionticket", 'uid=0 and browser not like "%bot%" AND browser not like "%perl%" AND browser not like "%webmin%" and browser not like "%validator%" and browser not like "%Akregator%" and browser not like "%Slurp%" and browser not like "%google%" and browser not like "%wget%" and browser not like "%rss%" and browser not like "%newsgator%" and browser not like "%webmonitor%" and browser not like "%shelob%" and last_active >'.$timeout);
    	return $anonymous;
  }
  
	function deleteIn($loc) {
		global $db;
		
		foreach ($db->selectObjects("bb_board","location_data='".serialize($loc)."'") as $board) {
			$threads = $db->selectObjects("bb_post","board_id=".$board->id . " AND parent = 0");
			foreach ($threads as $thread) {
				$db->delete("bb_threadmonitor","thread_id=".$thread->id);
			}
			$db->delete("bb_post","board_id=".$board->id);
			$db->delete("bb_boardmonitor","board_id=".$board->id);
		}
		$db->delete("bb_board","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		
		foreach ($db->selectObjects("bb_board","location_data='".serialize($oloc)."'") as $board) {
			$board->location_data = $board->location_data = serialize($nloc);
			$old_id = $board->id;
			unset($board->id);
			$board->id = $db->insertObject($board,'bb_board');
			
			$threads = $db->selectObjects("bb_post","board_id=".$old_id . " AND parent = 0");
			foreach ($threads as $thread) {
				$thread->board_id = $board->id;
				$old_thread_id = $thread->id;
				unset($thread->id);
				
				$thread->id = $db->insertObject($thread,'bb_post');
				foreach ($db->selectObjects('bb_post','parent='.$old_thread_id) as $reply) {
					unset($reply->id);
					$reply->parent = $thread->id;
					$db->insertObject($reply,'bb_post');
				}
				
				foreach ($db->selectObjects('bb_threadmonitor','thread_id='.$old_thread_id) as $thread_monitor) {
					unset($thread_monitor->id);
					$thread_monitor->thread_id = $thread->id;
					$db->insertObject($thread_monitor,'bb_threadmonitor');
				}
			}
			
			foreach ($db->selectObjects('bb_boardmonitor','board_id='.$old_id) as $board_monitor) {
				unset($board_monitor->id);
				$board_monitor->board_id = $board->id;
				$db->insertObject($board_monitor,'bb_boardmonitor');
			}
		}
	}

	function searchName() {
                return "Forums";
        }
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->ref_module = 'bbmodule';
		
		if ($item) {
		
			$search->original_id = $item->id;
		
			if (isset($item->board_id)) {
				// Forum post
				$search->category = 'Bulletin Board Post';
				$search->ref_type = 'bb_post';
				$db->delete('search',"ref_module='bbmodule' AND ref_type='bb_post' AND original_id=" . $item->id);
				
				$search->title = ' ' . $item->subject . ' ';
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->view_link = 'index.php?module=bbmodule&action=view_thread&id='.$item->id;
				
				$board = $db->selectObject('bb_board','id='.$item->board_id);
				$search->location_data = $board->location_data;	
			} else {
				// Forum
				$search->category = 'Bulletin Board';
				$search->ref_type = 'bb_board';
				$db->delete('search',"ref_module='bbmodule' AND ref_type='bb_board' AND original_id=" . $item->id);
				
				$search->title = " " . $item->name . " ";
				$search->body = " " . exponent_search_removeHTML($item->description) . " ";
				$search->location_data = $item->location_data;
				$search->view_link = 'index.php?module=bbmodule&action=view_board&id='.$item->id;
			}
			$db->insertObject($search,"search");
		} else {
			$db->delete('search',"ref_module='bbmodule' AND ref_type='bb_post'");
			$db->delete('search',"ref_module='bbmodule' AND ref_type='bb_board'");
			
			$search->category = 'Bulletin Board';
			$search->ref_type = 'bb_board';
			
			$boards = $db->selectObjectsIndexedArray('bb_board');
			
			foreach ($boards as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->name . ' ';
				$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
				$search->location_data = $item->location_data;
				$search->view_link = 'index.php?module=bbmodule&action=view_board&id='.$item->id;
				$db->insertObject($search,'search');
			}
			
			$search->category = 'Bulletin Board Post';
			$search->ref_type = 'bb_post';
			
			foreach ($db->selectObjects('bb_post') as $post) {
				$search->original_id = $post->id;

				$title = '';
				if (isset($post->subject) && $post->subject != '') {
					$title = ' ' . $post->subject . ' ';
				} elseif ( (!isset($post->subject) || $post->subject='') && (isset($post->parent)) ) {
					$title = $db->selectCol('bb_post', 'subject', 'id='.$post->parent);
				} 

				if ($title == '') {	
					$search->title = substr($post->body,0,35);
				} else {
  					$search->title = $title;
				}

				$search->body = ' ' . exponent_search_removeHTML($post->body) . ' ';
				if ($post->board_id > 0) {
					$search->location_data = $boards[$post->board_id]->location_data;
				}
				$search->view_link = 'index.php?module=bbmodule&action=view_thread&id='.$post->id;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>

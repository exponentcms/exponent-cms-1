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
# $Id$
##################################################

class weblogmodule {
	function name() { return 'Weblog / Online Journal'; }
	function author() { return 'James Hunt'; }
	function description() { return 'Manages an online journal.'; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','weblogmodule');
		if ($internal == '') {
			return array(
				'administrate'=>TR_WEBLOGMODULE_PERM_ADMIN,
				'configure'=>TR_WEBLOGMODULE_PERM_CONFIG,
				'post'=>TR_WEBLOGMODULE_PERM_POST,
				'edit'=>TR_WEBLOGMODULE_PERM_EDIT,
				'delete'=>TR_WEBLOGMODULE_PERM_DELETE,
				'comment'=>TR_WEBLOGMODULE_PERM_COMMENT,
				'edit_comments'=>TR_WEBLOGMODULE_PERM_EDITCOMMENT,
				'delete_comments'=>TR_WEBLOGMODULE_PERM_DELCOMMENT,
				'view_private'=>TR_WEBLOGMODULE_PERM_VIEWPRIVATE
			);
		} else {
			return array(
				'administrate'=>TR_WEBLOGMODULE_PERM_ADMIN,
				'edit'=>TR_WEBLOGMODULE_PERM_EDITONE,
				'delete'=>TR_WEBLOGMODULE_PERM_DELETEONE,
				'comment'=>TR_WEBLOGMODULE_PERM_COMMENT,
				'edit_comments'=>TR_WEBLOGMODULE_PERM_EDITCOMMENT,
				'delete_comments'=>TR_WEBLOGMODULE_PERM_DELETECOMMENT,
				'view_private'=>TR_WEBLOGMODULE_PERM_VIEWPRIVEONE
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') return array($loc);
		else {
			return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$loc = null, $title = '') {
		$template = new template('weblogmodule',$view,$loc);
		
		global $db;
		$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->allow_comments = 1;
			$config->items_per_page = 10;
		}
		
		$viewconfig = array('type'=>'default');
		if (is_readable($template->viewdir."/$view.config")) {
			$viewconfig = include($template->viewdir."/$view.config");
		}
		
		if ($viewconfig['type'] == 'monthlist') {
			$months = array();
			$where = "location_data='".serialize($loc)."'";
			if (!pathos_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';
			
			$min_date = $db->min('weblog_post','posted','location_data',$where);
			$max_date = $db->max('weblog_post','posted','location_data',$where);
			
			$months = array();
			if (!defined('SYS_DATETIME')) include_once(BASE.'subsystems/datetime.php');
			$start_month = pathos_datetime_startOfMonthTimestamp($min_date);
			$end_month = pathos_datetime_endOfMonthTimestamp($min_date)+86399;
			do {
				$count = $db->countObjects('weblog_post',$where . ' AND posted >= '.$start_month . ' AND posted <= ' . $end_month);
				if ($count) {
					$months[$start_month] = $count;
				}
				$start_month = $end_month + 1;
				$end_month = pathos_datetime_endOfMonthTimestamp($start_month)+86399;
			} while ($start_month < $max_date);
			$template->assign('months',array_reverse($months,true));
		} else {
			$where = ' AND is_private = 0';
			if (pathos_permissions_check('view_private',$loc)) $where = '';
			
			$total = $db->countObjects('weblog_post',"location_data='".serialize($loc)."'".$where);
			$posts = $db->selectObjects('weblog_post',"location_data='".serialize($loc)."'".$where . ' ORDER BY posted DESC '.$db->limit($config->items_per_page,0));
			if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
			for ($i = 0; $i < count($posts); $i++) {
				$ploc = pathos_core_makeLocation($loc->mod,$loc->src,$posts[$i]->id);
				
				$posts[$i]->permissions = array(
					'administrate'=>pathos_permissions_check('administrate',$ploc),
					'edit'=>pathos_permissions_check('edit',$ploc),
					'delete'=>pathos_permissions_check('delete',$ploc),
					'comment'=>pathos_permissions_check('comment',$ploc),
					'edit_comments'=>pathos_permissions_check('edit_comments',$ploc),
					'delete_comments'=>pathos_permissions_check('delete_comments',$ploc),
					'view_private'=>pathos_permissions_check('view_private',$ploc),
				);
				$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id);
				usort($comments,'pathos_sorting_byPostedDescending');
				$posts[$i]->comments = $comments;
			}
			usort($posts,'pathos_sorting_byPostedDescending');
			$template->assign('posts',$posts);
			$template->assign('total_posts',$total);
		}
		
		$template->register_permissions(
			array('administrate','configure','post','edit','delete','comment','edit_comments','delete_comments','view_private'),
			$loc);
		$template->assign('config',$config);
		$template->assign('moduletitle',$title);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		foreach ($db->selectObjects('weblog_post',"location_data='".serialize($loc)."'") as $post) {
			$db->delete('weblog_comment','parent_id='.$post->id);
		}
		$db->delete('weblog_post',"location_data='".serialize($loc)."'");
		$db->delete('weblogmodule_config',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects('weblog_post',"location_data='".serialize($oloc)."'") as $post) {
			$oldid = $post->id;
			unset($post->id);
			$post->location_data = serialize($nloc);
			$newid = $db->insertObject($post,'weblog_post');
			
			foreach ($db->selectObjects('weblog_comment','parent_id='.$oldid) as $comment) {
				$comment->parent_id = $newid;
				unset($comment->id);
				$db->insertObject($comment,'weblog_comment');
			}
		}
		$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($oloc)."'");
		unset($config->id);
		$config->location_data = serialize($nloc);
		$db->insertObject($config,'weblogmodule_config');
	}
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');
		
		pathos_lang_loadDictionary('modules','weblogmodule');
		
		$search = null;
		$search->category = TR_WEBLOGMODULE_SEARCHTYPE;
		$search->view_link = ""; // FIXME : need a view action
		$search->ref_module = 'weblogmodule';
		$search->ref_type = 'weblog_post';
		
		if ($item) {
			$db->delete('search',"ref_module='weblogmodule' AND ref_type='weblog_post' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = " " . pathos_search_removeHTML($item->body) . " ";
			$search->title = " " . $item->title . " ";
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='weblogmodule' AND ref_type='weblog_post'");
			foreach ($db->selectObjects('weblog_post') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . pathos_search_removeHTML($item->body) . ' ';
				$search->title = ' ' . $item->title . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>
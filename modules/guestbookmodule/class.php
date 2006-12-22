<?php

#############################################################
# GUESTBOOKMODULE
#############################################################
# Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
#
# version 0.5:	Developement-Version
# version 1.0:	1st release for Exponent v0.93.3
# version 1.2:	Captcha added
# version 2.0:	now compatible to Exponent v0.93.5
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

class guestbookmodule {
	function name() { return exponent_lang_loadKey('modules/guestbookmodule/class.php','module_name'); }
	function author() { return 'Dirk Olten (extrabyte.de)'; }
	function description() { return exponent_lang_loadKey('modules/guestbookmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/guestbookmodule/class.php');
		
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete'],
				'comment'=>$i18n['perm_comment'],
				'edit_comments'=>$i18n['perm_edit_comments'],
				'delete_comments'=>$i18n['perm_delete_comments'],
				'view_private'=>$i18n['perm_view_private']
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'edit'=>$i18n['perm_edit_one'],
				'delete'=>$i18n['perm_delete_one'],
				'comment'=>$i18n['perm_comment'],
				'edit_comments'=>$i18n['perm_edit_comments'],
				'delete_comments'=>$i18n['perm_delete_comments'],
				'view_private'=>$i18n['perm_view_private_one']
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,exponent_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$loc = null, $title = '') {
		$template = new template('guestbookmodule',$view,$loc);
		
		global $db;
		global $user;
		
		$user_id = ($user ? $user->id : -1);

		$config = $db->selectObject('guestbookmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->allow_comments = 1;
			$config->items_per_page = 10;
			$config->wysiwyg = 0;
		}
		
		$where = "location_data='".serialize($loc)."'";
		$total = $db->countObjects('guestbook_post',$where);
		$posts = $db->selectObjects('guestbook_post',$where . ' ORDER BY posted DESC '.$db->limit($config->items_per_page,0));
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		for ($i = 0; $i < count($posts); $i++) {
			$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$posts[$i]->id);
			$posts[$i]->permissions = array(
				'administrate'=>exponent_permissions_check('administrate',$ploc),
				'edit'=>exponent_permissions_check('edit',$ploc),
				'delete'=>exponent_permissions_check('delete',$ploc),
				'comment'=>exponent_permissions_check('comment',$ploc),
				'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
				'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
			);
			$comments = $db->selectObjects('guestbook_comment','parent_id='.$posts[$i]->id);
			usort($comments,'exponent_sorting_byPostedDescending');
			$posts[$i]->comments = $comments;
		}
		usort($posts,'exponent_sorting_byPostedDescending');
		$template->assign('posts',$posts);
		$template->assign('total_posts',$total);
		
		$template->register_permissions(
			array('administrate','configure','post','edit','delete','comment','edit_comments','delete_comments','view_private'),
			$loc);
		$template->assign('config',$config);
		$template->assign('moduletitle',$title);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		foreach ($db->selectObjects('guestbook_post',"location_data='".serialize($loc)."'") as $post) {
			$db->delete('guestbook_comment','parent_id='.$post->id);
		}
		$db->delete('guestbook_post',"location_data='".serialize($loc)."'");
		$db->delete('guestbookmodule_config',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects('guestbook_post',"location_data='".serialize($oloc)."'") as $post) {
			$oldid = $post->id;
			unset($post->id);
			$post->location_data = serialize($nloc);
			$newid = $db->insertObject($post,'guestbook_post');
			
			foreach ($db->selectObjects('guestbook_comment','parent_id='.$oldid) as $comment) {
				$comment->parent_id = $newid;
				unset($comment->id);
				$db->insertObject($comment,'guestbook_comment');
			}
		}
		$config = $db->selectObject('guestbookmodule_config',"location_data='".serialize($oloc)."'");
		unset($config->id);
		$config->location_data = serialize($nloc);
		$db->insertObject($config,'guestbookmodule_config');
	}
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = exponent_lang_loadKey('modules/guestbookmodule/class.php','search_category');
		$search->view_link = ""; 
		$search->ref_module = 'guestbookmodule';
		$search->ref_type = 'guestbook_post';
		
		$view_link = array(
			'module'=>'guestbookmodule',
			'action'=>'view',
			'id'=>0
		);
		
		if ($item && $item->is_draft == 0) {
			$db->delete('search',"ref_module='guestbookmodule' AND ref_type='guestbook_post' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = " " . exponent_search_removeHTML($item->body) . " ";
			$search->title = " " . $item->title . " ";
			$search->location_data = $item->location_data;
			
			$view_link['id'] = $item->id;
			//$search->view_link = exponent_core_makeLink($view_link,true);
			$search->view_link = 'index.php?module=guestbookmodule&action=view&id='.$item->id;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='guestbookmodule' AND ref_type='guestbook_post'");
			foreach ($db->selectObjects('guestbook_post','is_private=0 AND is_draft=0') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->title = ' ' . $item->title . ' ';
				$search->location_data = $item->location_data;
				
				$view_link['id'] = $item->id;
				//$search->view_link = exponent_core_makeLink($view_link,true);
				$search->view_link = 'index.php?module=guestbookmodule&action=view&id='.$item->id;
				
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>

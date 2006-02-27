<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class newsmodule {
	function name() { return pathos_lang_loadKey('modules/newsmodule/class.php','module_name'); }
	function author() { return 'James Hunt'; }
	function description() { return pathos_lang_loadKey('modules/newsmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return true; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/newsmodule/class.php');
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
				'add_item'=>$i18n['perm_add_item'],
				'delete_item'=>$i18n['perm_delete_item'],
				'edit_item'=>$i18n['perm_edit_item'],
				'view_unpublished'=>$i18n['perm_view_unpublished'],
				'approve'=>$i18n['perm_approve'],
				'manage_approval'=>$i18n['perm_manage_approval']
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'delete_item'=>$i18n['perm_delete_item'],
				'edit_item'=>$i18n['perm_edit_item']
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function deleteIn($location) {
		global $db;
		$items = $db->selectObjects('newsitem',"location_data='".serialize($location)."'");
		foreach ($items as $i) {
			$db->delete('newsitem_wf_revision','wf_original='.$i->id);
			$db->delete('newsitem_wf_info','real_id='.$i->id);
		}
		$db->delete('newsitem',"location_data='".serialize($location)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects('newsitem',"location_data='".serialize($oloc)."'") as $n) {
			$revs = $db->selectObjects('newsitem_wf_revision','wf_original='.$n->id);
			
			$n->location_data = serialize($nloc);
			unset($n->id);
			$n->id = $db->insertObject($n,'newsitem');
			
			foreach ($revs as $rev) {
				unset($rev->id);
				$rev->wf_original = $n->id;
				$rev->location_data = serialize($nloc);
				$db->insertObject($rev,'newsitem_wf_revision');
			}
		}
	}
	
	function show($view,$loc = null,$title = '') {
		global $db, $user;
		
		$config = $db->selectObject('newsmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->sortorder = 'ASC';
			$config->sortfield = 'posted';
			$config->item_limit = 10;
		}
		// Check permissions for AP link
		$canviewapproval = false;
		if ($user) $canviewapproval = pathos_permissions_check('approve',$loc) || pathos_permissions_check('manage_approval',$loc);
		if (!$canviewapproval) { // still not able to view
			foreach($db->selectObjects('newsitem',"location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ') AND approved != 0') as $post) {
				if ($user && $user->id == $post->poster) {
					$canviewapproval = true;
					break;
				}
			}
		}
		
	
		$template = new template('newsmodule',$view,$loc);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure','add_item','delete_item','edit_item','manage_approval','view_unpublished'),
			$loc
		);
		
		$news = $db->selectObjects('newsitem',"location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.$config->sortfield.' ' . $config->sortorder . $db->limit($config->item_limit,0));
		for ($i = 0; $i < count($news); $i++) {
			$news[$i]->real_posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
			$nloc = pathos_core_makeLocation($loc->mod,$loc->src,$news[$i]->id);
			$news[$i]->permissions = array(
				'edit_item'=>((pathos_permissions_check('edit_item',$loc) || pathos_permissions_check('edit_item',$nloc)) ? 1 : 0),
				'delete_item'=>((pathos_permissions_check('delete_item',$loc) || pathos_permissions_check('delete_item',$nloc)) ? 1 : 0),
				'administrate'=>((pathos_permissions_check('administrate',$loc) || pathos_permissions_check('administrate',$nloc)) ? 1 : 0)
			);
		}
		
		// EVIL WORKFLOW
		$in_approval = $db->countObjects('newsitem_wf_info',"location_data='".serialize($loc)."'");
		$template->assign('canview_approval_link',$canviewapproval);
		$template->assign('in_approval',$in_approval);
		$template->assign('news',$news);
		
		$template->assign('morenews',count($news) < $db->countObjects('newsitem',"location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0'));
		
		$template->output();
	}
	
	function spiderContent($item = null) {
		global $db;
		
		$i18n = pathos_lang_loadFile('modules/newsmodule/class.php');
		
		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = $i18n['search_category'];
		$search->ref_module = 'newsmodule';
		$search->ref_type = 'newsitem';
		
		if ($item) {
			$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->title . ' ';
			$search->view_link = 'index.php?module=newsmodule&action=view&id='.$item->id;
			$search->body = ' ' . pathos_search_removeHTML($item->body) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem'");
			foreach ($db->selectObjects('newsitem') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->title . ' ';
				$search->view_link = 'index.php?module=newsmodule&action=view&id='.$item->id;
				$search->body = ' ' . pathos_search_removeHTML($item->body) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}

}

?>

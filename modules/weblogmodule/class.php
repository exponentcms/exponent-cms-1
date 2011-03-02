<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

class weblogmodule {
	function name() { return exponent_lang_loadKey('modules/weblogmodule/class.php','module_name'); }
	function author() { return 'OIC Group, Inc'; }
	function description() { return exponent_lang_loadKey('modules/weblogmodule/class.php','module_description'); }

	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return true; }

	function getRSSContent($loc) {
		global $db;

		//Get this modules configuration data
		$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");

		//If this module was configured as an aggregator, then add those their location_data
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
		$locsql .= ")";
		$locsql .= " AND (publish = 0 or publish <= " . time() . 
			') AND (unpublish = 0 or unpublish > ' . time() . ') '; 

		if ($config->rss_limit > 0) {
			 $rsslimit = $db->limit($config->rss_limit,0);
		} else {
			$rsslimit = "";
		}

		//Get this modules items
		$items = array();
//		$items = $db->selectObjects("weblog_post", $locsql." AND is_private != 1", 'posted DESC');
//		$items = $db->selectObjects("weblog_post", $locsql." AND is_private != 1 AND approved != 0", 'publish DESC');
		$items = $db->selectObjects("weblog_post", $locsql." AND is_private != 1 AND is_draft != 1 ORDER BY publish DESC".$rsslimit);

		//Convert the blog posts to rss items
		$rssitems = array();
		foreach ($items as $key => $item) {
//			$item->posted = ($item->publish != 0 ? $item->publish : $item->posted);
			if ($item->publish == 0) {$item->publish = $item->posted;}			
			$rss_item = new FeedItem();
			$rss_item->title = $item->title;
			$rss_item->description = $item->body;
//			$rss_item->date = date('r', $item->posted);
			$rss_item->date = date('r', $item->publish);
			//$rss_item->link = "http://".HOSTNAME.PATH_RELATIVE."index.php?module=weblogmodule&action=view&id=".$item->id."&src=".$loc->src;
			$rss_item->link = exponent_core_makeLink(array('module'=>'weblogmodule', 'action'=>'view', 'id'=>$item->id));
			$rssitems[$key] = $rss_item;
		}
		return $rssitems;
	}

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/weblogmodule/class.php');

		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete'],
				'comment'=>$i18n['perm_comment'],
				'approve_comments'=>$i18n['perm_approve_comments'],
				'edit_comments'=>$i18n['perm_edit_comments'],
				'delete_comments'=>$i18n['perm_delete_comments'],
				'view_private'=>$i18n['perm_view_private'],
				'manage_approval'=>$i18n['perm_manage_approval']
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'edit'=>$i18n['perm_edit_one'],
				'delete'=>$i18n['perm_delete_one'],
				'comment'=>$i18n['perm_comment'],
				'approve_comments'=>$i18n['perm_approve_comments'],
				'edit_comments'=>$i18n['perm_edit_comments'],
				'delete_comments'=>$i18n['perm_delete_comments'],
				'view_private'=>$i18n['perm_view_private_one'],
				'manage_approval'=>$i18n['perm_manage_approval']
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
		global $db;
		global $user;
		$user_id = ($user ? $user->id : -1);

		$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->allow_comments = 1;
			$config->items_per_page = 10;
			$config->enable_rss = false;
			$config->collections = serialize(array());
		}

		$template = new template('weblogmodule',$view,$loc);
		
		//If rss is enabled tell the view to show the RSS button
		if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
		$template->assign('enable_rss', $config->enable_rss);

		//Get the tags that have been selected to be shown in the grouped by tag views
		//if (isset($config->show_tags)) {
	//			$available_tags = unserialize($config->show_tags);
	//		} else {
	//			$available_tags = array();
	//		}

		$viewparams = $template->viewparams;
		if ($viewparams === null) {
			$viewparams = array('type'=>"default");
		}
		$viewconfig = $template->viewconfig;
		if ($viewconfig === null) {
			$viewconfig = array('num_posts'=>"0");
		}
		
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
			$where = '(is_draft = 0 OR poster = '.$user_id.") AND ".$locsql;
		}
		if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';
		
		if ($viewparams['type'] == 'monthlist') {
			$months = array();

			$min_date = $db->min('weblog_post','posted','location_data',$where);
			$max_date = $db->max('weblog_post','posted','location_data',$where);

			$months = array();
			if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');
			$start_month = exponent_datetime_startOfMonthTimestamp($min_date);
//			$end_month = exponent_datetime_endOfMonthTimestamp($min_date)+86399;
			$end_month = exponent_datetime_endOfMonthTimestamp($min_date);
			do {
//				$count = $db->countObjects('weblog_post',$where . ' AND posted >= '.$start_month . ' AND posted <= ' . $end_month);
				$count = $db->countObjects('weblog_post',$where . ' AND publish >= '.$start_month . ' AND publish <= ' . $end_month);
				if ($count) {
					$months[$start_month] = $count;
				}
				$start_month = $end_month + 1;
				$end_month = exponent_datetime_endOfMonthTimestamp($start_month)+86399;
			} while ($start_month < $max_date);
			$template->assign('months',array_slice(array_reverse($months,true),0,$config->items_per_page,true));
		} else if ($viewparams['type'] == 'byauthor') {
			$users = $db->selectColumn('weblog_post', 'distinct(poster)', $where);
			$authors = $db->selectObjectsInArray('user', $users, 'username');
			for($i=0; $i < count($authors); $i++) {
				$authors[$i]->count = $db->countObjects('weblog_post', 'poster='.$authors[$i]->id);
			}
			$template->assign('authors', $authors);
		} else if ($viewparams['type'] == 'bytag') {
			$post_tags = $db->selectColumn('weblog_post', 'tags', $where);
			$all_tags = $db->selectObjects('tags');
			for ($i = 0; $i < count($post_tags); $i++) {
				if (!empty($post_tags[$i])) $tag_ids = unserialize($post_tags[$i]);
				for ($j = 0; $j < count($tag_ids); $j++) {
					for ($k = 0; $k < count($all_tags); $k++) {
						if ($all_tags[$k]->id == $tag_ids[$j]) {
							if (empty($all_tags[$k]->cnt)) $all_tags[$k]->cnt = 1;
							else $all_tags[$k]->cnt++;
						}
						//if (empty($all_tags[$k]->cnt)) $all_tags[$k]->cnt = 1;
					}
				}
			}
			
			// Let's group the tags by usage into 5 groupings for the Tag Cloud
			
			//First, we collect all of the counts for the tags into an array. Check as we go to prevent duplicates
			$tag_counts = array();
			foreach($all_tags as $tag){
				if(isset($tag->cnt) && !in_array($tag->cnt, $tag_counts))
					$tag_counts[] = $tag->cnt;
			}
			sort($tag_counts);
			
			//Next, we divide the array into 5 groupings
			$group_size = floor(count($tag_counts) / 5);
			$group1 = array_slice($tag_counts,0,$group_size);
			$group2 = array_slice($tag_counts,$group_size,$group_size);
			$group3 = array_slice($tag_counts,$group_size*2,$group_size);
			$group4 = array_slice($tag_counts,$group_size*3,$group_size);
			$group5 = array_slice($tag_counts,$group_size*4);
			
			//Now, we mark the all_tags array with their group
			foreach($all_tags as $tag){
				if(isset($tag->cnt)){
					if(in_array($tag->cnt, $group1))
						$tag->group = 1;
					if(in_array($tag->cnt, $group2))
						$tag->group = 2;
					if(in_array($tag->cnt, $group3))
						$tag->group = 3;
					if(in_array($tag->cnt, $group4))
						$tag->group = 4;
					if(in_array($tag->cnt, $group5))
						$tag->group = 5;
				}
			}
			
			usort($all_tags, "exponent_sorting_byNameAscending");
			$template->assign('tags', $all_tags);
		} else if ($viewparams['type'] == 'calendar') {
			if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');
			$time = (isset($_GET['month']) ? $_GET['month'] : time());
			$month_days = exponent_datetime_monthlyDaysTimestamp($time);
			$endofmonth = date('t', $time);
			for ($i = 0; $i < count($month_days); $i++) {
				foreach ($month_days[$i] as $mday=>$timestamp) {
					if ( ($mday > 0) && ($mday <= $endofmonth) ) {
						// Got a valid one.	 Go with it.
						$month_days[$i][$mday]['number'] = ($month_days[$i][$mday]['ts'] !=-1) ? $db->countObjects('weblog_post',$where.' AND posted >= '.$timestamp['ts'] .' AND posted < '.strtotime('+1 day',$timestamp['ts'])) : -1;
						if ($month_days[$i][$mday]['number']) $posted = true;
					}
				}
			}

			$info = getdate($time);
			$timefirst = mktime(12,0,0,$info['mon'],1,$info['year']);
			$prevmonth = mktime(0, 0, 0, date("m",$timefirst)-1, date("d",$timefirst)+10,   date("Y",$timefirst));
			$nextmonth = mktime(0, 0, 0, date("m",$timefirst)+1, date("d",$timefirst)+10,   date("Y",$timefirst));
			$template->assign("now",$timefirst);
			$template->assign("posted",$posted);
			$template->assign("prevmonth",$prevmonth);
			$template->assign("thismonth",$timefirst);
			$template->assign("nextmonth",$nextmonth);
			$template->assign('days',$month_days);
		} else {	// default view
			$limit = '';
			if (isset($viewconfig) && isset($viewconfig['num_posts']) && $viewconfig['num_posts'] != 0) {
				$limit = $db->limit($viewconfig['num_posts'],0);
			}	
			$total = $db->countObjects('weblog_post',$where);
//			$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY posted DESC '.$db->limit($config->items_per_page,0));
			$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY posted DESC '.$limit);			
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
					'manage_approval'=>exponent_permissions_check('manage_approval',$ploc),
				);
				if (!exponent_permissions_check('approve_comments',$ploc) && $config->approve_comments) {
					$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id." AND approved=1");
				} else {
					$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id);
				}
				usort($comments,'exponent_sorting_byPostedDescending');
				$posts[$i]->comments = $comments;
				$posts[$i]->total_comments = count($comments);

				//Get the tags for this weblogitem
				$selected_tags = array();
				$tag_ids = unserialize($posts[$i]->tags);
				if(is_array($tag_ids) && count($tag_ids)>0) {
					$selected_tags = $db->selectObjectsInArray('tags', $tag_ids, 'name');
				}
				$posts[$i]->tags = $selected_tags;
//				$posts[$i]->selected_tags = $selected_tags;
			}
//			usort($posts,'exponent_sorting_byPostedDescending');
			usort($posts,'exponent_sorting_byPublishedDescending');
			$template->assign('posts',$posts);
			$template->assign('total_posts',$total);
		}

//		if (!empty($config->collections)) $template->assign('tag_collections', ($db->selectObjectsInArray('tag_collections', unserialize($config->collections))));

		$monitoring = false;
		if ($user && ($user->id!=0)) {
			$weblog_monitor = null;
			$weblog_monitor = $db->selectObject("weblog_monitor","weblog_id=".$config->id." AND user_id=".$user->id);
			if ($weblog_monitor != null) $monitoring = true;
		}
		$template->assign("monitoring", $monitoring);
		
		$template->register_permissions(
			array('administrate','configure','post','edit','delete','comment','approve_comments','edit_comments','delete_comments','view_private','manage_approval'),
			$loc);
		$template->assign('config',$config);
//		$template->assign('viewconfig',$template->viewconfig);
//		$template->assign('num_posts',$viewconfig['num_posts']);
//		$template->assign('paging',$viewconfig['paging']);		
//		$template->assign('show_author',$viewconfig['show_author']);		
		$template->assign('logged_in', exponent_users_isLoggedIn());
		$template->assign('moduletitle',$title);
		$template->output();
	}

	function deleteIn($loc) {
		global $db;
		$refcount = $db->selectValue('sectionref', 'refcount', "source='".$loc->src."'");
				if ($refcount <= 0) {
			foreach ($db->selectObjects('weblog_post',"location_data='".serialize($loc)."'") as $post) {
				$db->delete('weblog_comment','parent_id='.$post->id);
			}
			$db->delete('weblog_post',"location_data='".serialize($loc)."'");
			$db->delete('weblogmodule_config',"location_data='".serialize($loc)."'");
		}
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

	function searchName() {
				return "Weblogs";
		}

	function spiderContent($item = null) {
		global $db;

		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');

		$search = null;
		$search->category = exponent_lang_loadKey('modules/weblogmodule/class.php','search_category');
		$search->view_link = ""; // FIXME : need a view action
		$search->ref_module = 'weblogmodule';
		$search->ref_type = 'weblog_post';

		$view_link = array(
			'module'=>'weblogmodule',
			'action'=>'view',
			'id'=>0
		);

		if ($item && $item->is_draft == 0) {
			$db->delete('search',"ref_module='weblogmodule' AND ref_type='weblog_post' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = " " . exponent_search_removeHTML($item->body) . " ";
			$search->title = " " . $item->title . " ";
			$search->location_data = $item->location_data;

			$view_link = 'index.php?module=weblogmodule&amp;action=view&amp;id='.$item->id;
			// $search->view_link = exponent_core_makeLink($view_link,true);
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='weblogmodule' AND ref_type='weblog_post'");
			foreach ($db->selectObjects('weblog_post','is_private=0 AND is_draft=0') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->title = ' ' . $item->title . ' ';
				$search->location_data = $item->location_data;

				$view_link = 'index.php?module=weblogmodule&amp;action=view&amp;id='.$item->id;
				// $search->view_link = exponent_core_makeLink($view_link,true);

				$db->insertObject($search,'search');
			}
		}

		return true;
	}
}

?>
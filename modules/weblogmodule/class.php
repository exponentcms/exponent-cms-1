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

class weblogmodule {
	function name() { return exponent_lang_loadKey('modules/weblogmodule/class.php','module_name'); }
	function author() { return 'OIC Group, Inc'; }
	function description() { return exponent_lang_loadKey('modules/weblogmodule/class.php','module_description'); }

	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }

	function getRSSContent($loc) {
                global $db;

                //Get this modules items
                $items = array();
                $items = $db->selectObjects("weblog_post", "location_data='".serialize($loc)."'");

                //Convert the newsitems to rss items
                $rssitems = array();
                foreach ($items as $key => $item) {
                        $rss_item = new FeedItem();
                        $rss_item->title = $item->title;
                        $rss_item->description = $item->body;
                        $rss_item->date = date('r', $item->posted);
                        $rss_item->link = "http://".HOSTNAME.PATH_RELATIVE."index.php?module=weblogmodule&action=view&id=".$item->id."&src=".$loc->src;
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
		$template = new template('weblogmodule',$view,$loc);

		global $db;
		global $user;

		$user_id = ($user ? $user->id : -1);

		$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->allow_comments = 1;
			$config->items_per_page = 10;
			$config->enable_rss = false;
		}

		//If rss is enabled tell the view to show the RSS button
                if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
		$template->assign('enable_rss', $config->enable_rss);

		$viewconfig = array('type'=>'default');
		if (is_readable($template->viewdir."/$view.config")) {
			$viewconfig = include($template->viewdir."/$view.config");
		}

		$where = '(is_draft = 0 OR poster = '.$user_id.") AND location_data='".serialize($loc)."'";
		if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';

		if ($viewconfig['type'] == 'monthlist') {
			$months = array();

			$min_date = $db->min('weblog_post','posted','location_data',$where);
			$max_date = $db->max('weblog_post','posted','location_data',$where);

			$months = array();
			if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');
			$start_month = exponent_datetime_startOfMonthTimestamp($min_date);
			$end_month = exponent_datetime_endOfMonthTimestamp($min_date)+86399;
			do {
				$count = $db->countObjects('weblog_post',$where . ' AND posted >= '.$start_month . ' AND posted <= ' . $end_month);
				if ($count) {
					$months[$start_month] = $count;
				}
				$start_month = $end_month + 1;
				$end_month = exponent_datetime_endOfMonthTimestamp($start_month)+86399;
			} while ($start_month < $max_date);
			$template->assign('months',array_reverse($months,true));
		} else if ($viewconfig['type'] == 'calendar') {
			if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');
			$month_days = exponent_datetime_monthlyDaysTimestamp(time());
			for ($i = 0; $i < count($month_days); $i++) {
				foreach ($month_days[$i] as $mday=>$timestamp) {
					if ($mday > 0) {
						// Got a valid one.  Go with it.
						$month_days[$i][$mday] = array(
							'number'=>$db->countObjects('weblog_post',$where.' AND posted >= '.$timestamp .' AND posted < '.strtotime('+1 day',$timestamp)),
							'ts'=>$timestamp
						);
					}
				}
			}

			$template->assign('days',$month_days);
			$template->assign('now',time());
		} else {
			$total = $db->countObjects('weblog_post',$where);
			$posts = $db->selectObjects('weblog_post',$where . ' ORDER BY posted DESC '.$db->limit($config->items_per_page,0));
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
					'view_private'=>exponent_permissions_check('view_private',$ploc),
				);
				$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id);
				usort($comments,'exponent_sorting_byPostedDescending');
				$posts[$i]->comments = $comments;
				$posts[$i]->total_comments = count($comments);
			}
			usort($posts,'exponent_sorting_byPostedDescending');
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

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

class newsmodule {
	function name() { return exponent_lang_loadKey('modules/newsmodule/class.php','module_name'); }
	function author() { return 'OIC Group, Inc'; }
	function description() { return exponent_lang_loadKey('modules/newsmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	function getRSSType() { return "news"; }
	
	function supportsWorkflow() { return true; }

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/newsmodule/class.php');
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

	function getRSSContent($loc) {
		global $db;
	
		//Get this modules configuration data
		$config = $db->selectObject('newsmodule_config',"location_data='".serialize($loc)."'");

		//If this module was configured as an aggregant, then turn off check for the location_data
                if (isset($config->aggregate) && $config->aggregate == true) {
                        $ifloc = '';
                } else {
                        $ifloc = "location_data='" . serialize($loc) . "' AND ";
                }

		//Get this modules items
		$items = array();
		//$items = $db->selectObjects("newsitem", "location_data='".serialize($loc)."'");
		$items = $db->selectObjects('newsitem',$ifloc."(publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.$config->sortfield.' ' . $config->sortorder);
		
		//Convert the newsitems to rss items
		$rssitems = array();
		foreach ($items as $key => $item) {	
			$rss_item = new FeedItem();
			$rss_item->title = $item->title;
			$rss_item->description = $item->body;
			$rss_item->date = date('r',$item->posted);
			$rss_item->link = "http://".HOSTNAME.PATH_RELATIVE."index.php?module=newsmodule&action=view&id=".$item->id."&src=".$loc->src;
			$rssitems[$key] = $rss_item;
		}
		return $rssitems;
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,exponent_core_makeLocation($loc->mod,$loc->src));
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
		if (empty($config)) {
			$config->sortorder = 'DESC';
			//FJD - changed from posted to edited:
			$config->sortfield = 'edited';
			$config->item_limit = 10;
			$config->enable_rss = false;
			$config->group_by_tags = false;
			$config->pull_rss = 0;
			$config->aggregate = array();
			$config->collections = serialize(array());
		}
	
		$locsql = "(location_data='".serialize($loc)."'";
                if (!empty($config->aggregate)) {
                        $locations = unserialize($config->aggregate);
                        foreach ($locations as $source) {
                                $tmploc = null;
                                $tmploc->mod = 'newsmodule';
                                $tmploc->src = $source;
                                $tmploc->int = '';
                                $locsql .= " OR location_data='".serialize($tmploc)."'";
                        }
                }
                $locsql .= ')';
	
		// Check permissions for AP link
		$canviewapproval = false;
		if ($user) $canviewapproval = exponent_permissions_check('approve',$loc) || exponent_permissions_check('manage_approval',$loc);
		if (!$canviewapproval) { // still not able to view
			foreach($db->selectObjects('newsitem',"location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ") AND (unpublish = 0 or unpublish > " . time() . ') AND approved != 0') as $post) {
				if ($user && $user->id == $post->poster) {
					$canviewapproval = true;
					break;
				}
			}
		}

		if ($config->group_by_tags == true) {	
			$view = "_group_by_tags";	
		} 

		$template = new template('newsmodule',$view,$loc);

		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure','add_item','delete_item','edit_item','manage_approval','view_unpublished'),
			$loc
		);
	
		//If rss is enabled tell the view to show the RSS button	
		if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
		$template->assign('enable_rss', $config->enable_rss);
		
		//Get the tags that have been selected to be shown in the grouped by tag views
		if (isset($config->show_tags)) {
        		$available_tags = unserialize($config->show_tags);
        	} else {
        		$available_tags = array();
        	}

		//If this module was configured as an aggregant, then turn off check for the location_data
		/*if (isset($config->aggregate) && $config->aggregate == true) {
			$ifloc = '';
		} else {
			$ifloc = "location_data='" . serialize($loc) . "' AND ";
		}*/			
		
		//Get the news items.
		$news = $db->selectObjects('newsitem',$locsql." AND (publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.$config->sortfield.' ' . $config->sortorder ); //. $db->limit($config->item_limit,0));
		$featured = $db->selectObjects('newsitem',$locsql." AND is_featured AND (publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0 ORDER BY '.$config->sortfield.' ' . $config->sortorder ); //. $db->limit($config->item_limit,0));

		for ($i = 0; $i < count($news); $i++) {
			$news[$i]->real_posted = ($news[$i]->publish != 0 ? $news[$i]->publish : $news[$i]->posted);
			$nloc = exponent_core_makeLocation($loc->mod,$loc->src,$news[$i]->id);
			$news[$i]->permissions = array(
				'edit_item'=>((exponent_permissions_check('edit_item',$loc) || exponent_permissions_check('edit_item',$nloc)) ? 1 : 0),
				'delete_item'=>((exponent_permissions_check('delete_item',$loc) || exponent_permissions_check('delete_item',$nloc)) ? 1 : 0),
				'administrate'=>((exponent_permissions_check('administrate',$loc) || exponent_permissions_check('administrate',$nloc)) ? 1 : 0)
			);
			
			//Get the image file if there is one.
                       	if (isset($news[$i]->file_id) && $news[$i]->file_id > 0) {
                               	$file = $db->selectObject('file', 'id='.$news[$i]->file_id);
                                $news[$i]->image_path = $file->directory.'/'.$file->filename;
       	                }
	
			//Get the tags for this newsitem
			$selected_tags = array();
	        	$tag_ids = unserialize($news[$i]->tags);
	        	if(is_array($tag_ids)) {$selected_tags = $db->selectObjectsInArray('tags', $tag_ids, 'name');}
			$news[$i]->tags = $selected_tags;
	
			//If this module was configured to group the newsitems by tags, then we need to change the data array a bit
			if ($config->group_by_tags == true) {
				$grouped_news = array();
				foreach($news[$i]->tags as $tag) {
					if (in_array($tag->id, $available_tags) || count($available_tags) == 0) {
						if (!isset($grouped_news[$tag->name])) { $grouped_news[$tag->name] = array();} 
						array_push($grouped_news[$tag->name],$news[$i]);
					}
				}
			}	
		}

		foreach ($news as $item){
			$file = $db->selectObject("file","id=".$item->file_id);
			if(!empty($file)){
				$item->image = $file->directory.'/'.$file->filename;
				//$item->image = URL_FULL.$file->directory.'/'.$file->filename;
			}
		}

// Pull in RSS feeds. -RAM
        if ($config->pull_rss == 1) {	
            if ($config->rss_cachetime != 3600) {
                define('MAGPIE_CACHE_AGE', $config->rss_cachetime);
            }
            $rssFeeds = array();
	    	$RSS = new rssfeed();
            foreach(unserialize($config->rss_feed) as $key=>$val) {
                $RSS->setURL($val);
                $rssFeeds[] = $RSS->fetch();
            }
            foreach ($rssFeeds as $feed) {
                foreach ($feed->items as $rssItem) {
                    $rssObject = new stdClass();
                    $rssObject->title = $rssItem['title'];
                    $rssObject->body = $rssItem['description'];
                    $rssObject->approved = 1;
                    $rssObject->rss_link = $rssItem['link'];
                    $rssObject->posted = $rssItem['date_timestamp'];
                    $rssObject->edited = $rssItem['date_timestamp'];
                    $rssObject->published = $rssItem['date_timestamp'];
                    $news[] = $rssObject;
                }
            }
        }
       
	switch($config->sortfield) {
        	case "posted":
                    $field = "Posted";
                break;
                case "publish":
                    $field = "Posted";
                break;
                case "edited":
                    $field = "Edited";
                break;
                default:
                    $field = "Posted";
                break;
        }
        
	if ($config->sortorder == "ASC") {
        	$order = "Ascending";
        } else {
        	$order = "Descending";
        }
        
	$sortFunc = 'exponent_sorting_by'.$field.$order;
        
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
        usort($news,$sortFunc);
        usort($featured,$sortFunc);
        $news = array_slice($news, 0, $config->item_limit);
		// EVIL WORKFLOW
		$in_approval = $db->countObjects('newsitem_wf_info',"location_data='".serialize($loc)."'");
		$template->assign('canview_approval_link',$canviewapproval);
		$template->assign('in_approval',$in_approval);
		if($config->group_by_tags == true) {$template->assign('news',$grouped_news);} else {$template->assign('news',$news);}

		$template->assign('tag_collections', ($db->selectObjectsInArray('tag_collections', unserialize($config->collections))));
		$template->assign('featured', $featured);
		$template->assign('config', $config);		
		$template->assign('morenews',count($news) < $db->countObjects('newsitem',"location_data='" . serialize($loc) . "' AND (publish = 0 or publish <= " . time() . ') AND (unpublish = 0 or unpublish > ' . time() . ') AND approved != 0'));
		
		$template->output();
	}

	function searchName() {
                return "News Listings";
        }
	
	function spiderContent($item = null) {
		global $db;
		
		$i18n = exponent_lang_loadFile('modules/newsmodule/class.php');
		
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
			$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem'");
			foreach ($db->selectObjects('newsitem') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->title . ' ';
				$search->view_link = 'index.php?module=newsmodule&action=view&id='.$item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}

}

?>

<?php

##################################################
#
# Copyright (c) 2005 Chris Hastie.
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
# $Id: class.php
##################################################

class feedlistmodule {
    function name() { return exponent_lang_loadKey('modules/feedlistmodule/class.php','module_name'); }
    function description() { return  exponent_lang_loadKey('modules/feedlistmodule/class.php','module_description');}
    function author() { return "Chris Hastie"; }
    
    function hasSources() { return false; }
    function hasContent() { return false; }
    function hasViews() { return true; }
    
    function supportsWorkflow() { return false; }
    
    function permissions($internal = '') {
        $i18n = exponent_lang_loadFile('modules/feedlistmodule/class.php');
        return array(
            'administrate'=>$i18n['perm_administrate'],
            'configure'=>$i18n['perm_configure'],           
            'view' => $i18n['perm_view'],
        );
    }

    function getLocationHierarchy($loc) {
        return array($loc);        
    }
    
    function getRSSContent($loc) {        
        
        //Get this modules items
        $items = array();
        $items = feedlistmodule::getFeedList();

        // order by module ID descending, best effort at putting
        // most recently added feed first
		if (!function_exists("exponent_sort_feeds_modid_desc")) {
			function exponent_sort_feeds_modid_desc ($a, $b) {
			  return $a->modid < $b->modid ? 1 : -1;
			}
		}
        usort($items, 'exponent_sort_feeds_modid_desc');        
		
        //Convert the newsitems to rss items
        $rssitems = array();
        foreach ($items as $key => $item) { 
            $rss_item = new FeedItem();            
            $rss_item->title = $item->title;            
            
            // strip HTML, leaving a bit of extra space so that paragraphs etc don't 
            // run together and truncate body
            $rss_item->descriptionTruncSize = 300;            
            $rss_item->description = $item->description;
            $rss_item->date = date('r',time());
            // Note: we have to decode the URL as FeedGenerator will encode it
            $rss_item->link = html_entity_decode($item->link);
			$rss_item->category = array($item->module_name);
            $rssitems[$key] = $rss_item;
        }
        return $rssitems;
    }
    
    function show($view,$loc = null,$title = '') {
        global $db;
        
        $config = $db->selectObject('feedlistmodule_config',"location_data='".serialize($loc)."'");
        $feeds = feedlistmodule::getFeedList();
        
		if (!function_exists("exponent_sort_feeds_title_asc")) {
			function exponent_sort_feeds_title_asc ($a, $b) {
			  return strcasecmp($a->title ,$b->title);
			}
		}
        usort($feeds, 'exponent_sort_feeds_title_asc');        
        
        $categories = array();
        foreach ($feeds as $key => $feed) {
          //array_push($categories[$feed->module_name], $key);   
          $categories[$feed->module_name][] = $key;
        }    
        ksort($categories, SORT_STRING);        
            
        $template = new template('feedlistmodule',$view,$loc);
        
        $template->assign('feeds',$feeds);
        $template->assign('categories', $categories);
        $template->assign('moduletitle',$title);        
        $template->register_permissions(
            array('administrate','configure', 'view'),
            $loc
        );

        //If rss is enabled tell the view to show the RSS button    
        $template->assign('enable_rss', $config->enable_rss);        
        
        $template->output();
    }
    
    function deleteIn($loc) {
        return false;
    }
    
    function copyContent($oloc,$nloc) {
        return false;
    }
    
    function getFeedList() {
		global $db;
		$modules = $db->selectObjects("sectionref", "refcount > 0");
		$feeds = array();

		foreach ($modules as $module) {       
			if (isset($feeds[$module->source])) continue;
			$location->mod = $module->module;
			$location->src = $module->source;
			$location->int = $module->internal;

			//get the module's config data
//			$config = $db->selectObject($module->module."_config", "location_data='".serialize($location)."'");     
			$feed = $db->selectObject($module->module."_config", "location_data='".serialize($location)."'");     

//			if (!empty($config->enable_rss)) {
			if (!empty($feed->enable_rss)) {
//				$feed->title = empty($config->feed_title) ? 'Untitled' : $config->feed_title;
				$feed->title = empty($feed->feed_title) ? 'Untitled' : $feed->feed_title;
				$feed->modid = $module->id;
				$params['module'] = $module->module;
				$params['src'] = $module->source;
				if (!empty($module->internal)) $params['int'] = $module->internal;
				$feed->link = exponent_core_makeRSSLink($params);
//				$feed->description = $config->feed_desc;
				$feed->description = $feed->feed_desc;
				include_once(BASE . "modules/" . $module->module . "/class.php");
				$mod = new $module->module;
				$feed->module_name = $mod->name();
				$feeds[$module->source] = $feed;
			}      
		}     
		return $feeds;   
    }
    
    function spiderContent($item = null) {
        // Do nothing
        return false;
    }
}

?>
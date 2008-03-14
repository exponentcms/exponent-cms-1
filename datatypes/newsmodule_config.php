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

class newsmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/newsmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
	
		global $db;
		$tc_list = array();
                $tag_collections = $db->selectObjects("tag_collections");
                foreach ($tag_collections as $tag_collections => $collection) {
                        $tc_list[$collection->id] = $collection->name;
                }
	
		$form = new form();
		if (!isset($object->id)) {
			$object->sortorder = 'ASC';
			$object->sortfield = 'posted';
			$object->item_limit = 10;
			$object->enable_rss = false;
			$object->enable_tags = false;
			$object->group_by_tags = false;
			$object->aggregate = array();
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->collections = array();
			$object->show_tags = array();
			$object->pull_rss = 0;
			$object->rss_feed = serialize(array());
			$object->rss_cachetime = 3600;
		} else {
			$cols = unserialize($object->collections);
			$object->collections = array();
			$available_tags = array();
			foreach ($cols as $col_id) {
				$collection = $db->selectObject('tag_collections', 'id='.$col_id);
				$object->collections[$collection->id] = $collection->name;

				//while we're here we will get he list of available tags.
				$tmp_tags = $db->selectObjects('tags', 'collection_id='.$col_id);
				foreach ($tmp_tags as $tag) {
					$available_tags[$tag->id] = $tag->name;
				}
			}
			$feeds = array();
			foreach(unserialize($object->rss_feed) as $key=>$val) {
			    $feeds[$val] = $val;
			}
			$object->rss_feed = $feeds;
			//Get the tags the user chose to show in the group by views
			$stags = unserialize($object->show_tags);
			$object->show_tags = array();
			
			if (is_array($stags)) {
				foreach ($stags as $stag_id) {
        	                        $show_tag = $db->selectObject('tags', 'id='.$stag_id);
                	                $object->show_tags[$show_tag->id] = $show_tag->name;
                        	}
			}

		}

		// setup the listbuilder arrays for news aggregation.       
                $loc = unserialize($object->location_data);
                $news = exponent_modules_getModuleInstancesByType('newsmodule');
                $saved_aggregates = empty($object->aggregate) ? array() : unserialize($object->aggregate);
                $all_news = array();
                $selected_news = array();
                foreach ($news as $src => $cal) {
                        $news_name = (empty($cal[0]->title) ? 'Untitled' : $cal[0]->title).' on page '.$cal[0]->section;
                        if ($src != $loc->src) {
                                if (in_array($src, $saved_aggregates)) {
                                        $selected_news[$src] = $news_name;
                                } else {
                                        $all_news[$src] =  $news_name;
                                }
                        }
                }

		$opts  = array('ASC'=>$i18n['ascending'],'DESC'=>$i18n['descending']);
		$fields = array('posted'=>$i18n['posteddate'],'publish'=>$i18n['publishdate'],'edited'=>'Date of Last Edit');
		$form->register(null,'',new htmlcontrol('<h1>General Configuration</h1><hr size="1" />'));
		$form->register('item_limit',$i18n['item_limit'],new textcontrol($object->item_limit));
		$form->register('sortorder',$i18n['sortorder'], new dropdowncontrol($object->sortorder,$opts));
		$form->register('sortfield',$i18n['sortfield'], new dropdowncontrol($object->sortfield,$fields));
	
	 	$form->register(null,'',new htmlcontrol('<h1>Merge News</h1><hr size="1" />'));
                $form->register('aggregate','Pull Events from These Other News Module',new listbuildercontrol($selected_news,$all_news));
	
		$form->register(null,'',new htmlcontrol('<h1>Tagging</h1><hr size="1" />'));
		$form->register('enable_tags',$i18n['enable_tags'], new checkboxcontrol($object->enable_tags));
		$form->register('collections',$i18n['tag_collections'],new listbuildercontrol($object->collections,$tc_list));
		//$form->register('group_by_tags',$i18n['group_by_tags'], new checkboxcontrol($object->group_by_tags));
		//$form->register(null,'',new htmlcontrol($i18n['show_tags_desc']));
		//$form->register('show_tags','',new listbuildercontrol($object->show_tags,$available_tags));
		
		$form->register(null,'',new htmlcontrol('<h1>RSS Configuration</h1><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
		$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('pull_rss',$i18n['pull_rss'], new checkboxcontrol($object->pull_rss));
		$form->register('rss_feed',$i18n['rss_feed'], new listbuildercontrol($object->rss_feed,null));
		$form->register('rss_cachetime', $i18n['rss_cachetime'], new textcontrol($object->rss_cachetime));
		$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
                exponent_forms_initialize();
		$object->sortorder = $values['sortorder'];
		$object->sortfield = $values['sortfield'];
		$object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
		$object->enable_tags = (isset($values['enable_tags']) ? 1 : 0);
		$object->group_by_tags = (isset($values['group_by_tags']) ? 1 : 0);
		$object->aggregate = serialize(listbuildercontrol::parseData($values,'aggregate'));
		$object->show_tags = serialize(listbuildercontrol::parseData($values,'show_tags'));
		$object->collections = serialize(listbuildercontrol::parseData($values,'collections'));
		$object->aggregate = serialize(listbuildercontrol::parseData($values,'aggregate'));
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		if ($values['item_limit'] > 0) {
			$object->item_limit = $values['item_limit'];
		} else {
			$object->item_limit = 10;
		}
		if ( $values['pull_rss'] == 1 ) {
		    $object->pull_rss = 1;
		    $object->rss_feed = serialize(listbuildercontrol::parseData($values,'rss_feed'));
		} else {
		    $object->pull_rss = 0;
		    $object->rss_feed = serialize(array());
		}
		$object->rss_cachetime = $values['rss_cachetime'];
		
		return $object;
	}
}

?>

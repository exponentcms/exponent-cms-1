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
			$object->item_limit = 10;
			$object->enable_pagination = 0;
			$object->sortorder = 'DESC';
			$object->sortfield = 'posted';
			$object->show_poster = 1;
			$object->aggregate = array();
			$object->pull_rss = 0;
			$object->rss_feed = null;
			$object->enable_rss = false;
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->rss_limit = 25;
			$object->rss_cachetime = 1440;
			$object->enable_tags = false;
			$object->collections = array();
			$object->group_by_tags = false;
			$object->show_tags = array();
		} else {
			$form->meta('id',$object->id);

			$feeds = array();
			if(!empty($object->rss_feed)) {
    			foreach(unserialize($object->rss_feed) as $key=>$val) {
    			    $feeds[$val] = $val;
    			}
			}
			$object->rss_feed = $feeds;
			
			$cols = unserialize($object->collections);
			$object->collections = array();
			$available_tags = array();
			foreach ($cols as $col_id) {
				$collection = $db->selectObject('tag_collections', 'id='.$col_id);
				$object->collections[$collection->id] = $collection->name;

				//while we're here we will get the list of available tags.
				$tmp_tags = $db->selectObjects('tags', 'collection_id='.$col_id);
				foreach ($tmp_tags as $tag) {
					$available_tags[$tag->id] = $tag->name;
				}
			}
			//Get the tags the user chose to show in the group by views
			$stags = unserialize($object->show_tags);
			$object->show_tags = array();

//			if (is_array($stags)) {
			if (!empty($stags)) {
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
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['general_conf'].'</h3><hr size="1" />'));
		$form->register('item_limit',$i18n['item_limit'],new textcontrol($object->item_limit));
		$form->register('enable_pagination',$i18n['enable_pagination'],new checkboxcontrol($object->enable_pagination,true));
		$form->register('sortorder',$i18n['sortorder'], new dropdowncontrol($object->sortorder,$opts));
		$form->register('sortfield',$i18n['sortfield'], new dropdowncontrol($object->sortfield,$fields));
		$form->register('show_poster',$i18n['show_poster'],new checkboxcontrol($object->show_poster));

	 	$form->register(null,'',new htmlcontrol('<h3>'.$i18n['merge_news'].'</h3><hr size="1" />'));
		$form->register('aggregate',$i18n['pull_news'],new listbuildercontrol($selected_news,$all_news));
		$form->register('pull_rss',$i18n['pull_rss'], new checkboxcontrol($object->pull_rss));
		$form->register('rss_feed',$i18n['rss_feed'], new listbuildercontrol($object->rss_feed,null));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['rss_configuration'].'</h3><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
		$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('rss_cachetime', $i18n['rss_cachetime'], new textcontrol($object->rss_cachetime));
		$form->register('rss_limit', $i18n['rss_limit'], new textcontrol($object->rss_limit));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['tagging'].'</h3><hr size="1" />'));
		$form->register('enable_tags',$i18n['enable_tags'], new checkboxcontrol($object->enable_tags));
		$form->register('collections',$i18n['tag_collections'],new listbuildercontrol($object->collections,$tc_list));
		$form->register('group_by_tags',$i18n['group_by_tags'], new checkboxcontrol($object->group_by_tags));
		$form->register(null,'',new htmlcontrol($i18n['show_tags_desc']));
		$form->register('show_tags','',new listbuildercontrol($object->show_tags,$available_tags));

		$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}

	function update($values,$object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		if ($values['item_limit'] > 0) {
			$object->item_limit = $values['item_limit'];
		} else {
			$object->item_limit = 10;
		}
		$object->enable_pagination = (isset($values['enable_pagination']) ? 1 : 0);
		$object->sortorder = $values['sortorder'];
		$object->sortfield = $values['sortfield'];
		$object->show_poster = (isset($values['show_poster']) ? 1 : 0);

		$object->aggregate = serialize(listbuildercontrol::parseData($values,'aggregate'));
		$object->pull_rss = (isset($values['pull_rss']) ? 1 : 0);
	    if (!empty($values['rss_feed'])) {
			$object->rss_feed = serialize(listbuildercontrol::parseData($values,'rss_feed'));
   		} else {
			$object->rss_feed = null;
		}
		
		$object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		$object->rss_cachetime = $values['rss_cachetime'];
		$object->rss_limit = $values['rss_limit'];

		$object->enable_tags = (isset($values['enable_tags']) ? 1 : 0);
		$object->collections = serialize(listbuildercontrol::parseData($values,'collections'));
		$object->group_by_tags = (isset($values['group_by_tags']) ? 1 : 0);
		$object->show_tags = serialize(listbuildercontrol::parseData($values,'show_tags'));

		return $object;
	}
}

?>

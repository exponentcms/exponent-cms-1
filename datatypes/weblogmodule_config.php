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

class weblogmodule_config {

	function form($object) {
		global $db;
		$i18n = exponent_lang_loadFile('datatypes/weblogmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
        $tc_list = array();
                $tag_collections = $db->selectObjects("tag_collections");
                foreach ($tag_collections as $tag_collections => $collection) {
                        $tc_list[$collection->id] = $collection->name;
                }

		$form = new form();
		if (!isset($object->id)) {
			$object->allow_comments = 1;
			$object->use_captcha = 1;
			$object->require_login = 0;
			$object->items_per_page = 10;
			$object->enable_rss = false;
			$object->enable_tags = false;
      $object->feed_title = "";
      $object->feed_desc = "";
			$object->collections = array();
			//$object->show_tags = array();
			$object->comments_notify = serialize(array());
			$object->aggregate = array();
		} else {
			$form->meta('id',$object->id);						$cols = unserialize($object->collections);
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
			//$stags = unserialize($object->show_tags);
			//$object->show_tags = array();
			
			//if (is_array($stags)) {
			//	foreach ($stags as $stag_id) {
       // 	                        $show_tag = $db->selectObject('tags', 'id='.$stag_id);
      //          	                $object->show_tags[$show_tag->id] = $show_tag->name;
       //                 	}
		//	}

		}
		
		$selected_users = array();
		foreach(unserialize($object->comments_notify) as $i) {
			$selected_users[$i] = $db->selectValue('user', 'username', 'id='.$i);
		}
	
		$userlist = array();
		$list = exponent_users_getAllUsers();
		foreach ($list as $i) {
			if(!array_key_exists($i->id, $selected_users)) {
				$userlist[$i->id] = $i->username;
			}
		}

		// setup the listbuilder arrays for calendar aggregation.       
                $loc = unserialize($object->location_data);
                $blogs = exponent_modules_getModuleInstancesByType('weblogmodule');
                $saved_aggregates = empty($object->aggregate) ? array() : unserialize($object->aggregate);
                $all_blogs = array();
                $selected_blogs = array();
                foreach ($blogs as $src => $blog) {
                        $blog_name = (empty($blog[0]->title) ? 'Untitled' : $blog[0]->title).' on page '.$blog[0]->section;
                        if ($src != $loc->src) {
                                if (in_array($src, $saved_aggregates)) {
                                        $selected_blogs[$src] = $blog_name;
                                } else {
                                        $all_blogs[$src] =  $blog_name;
                                }
                        }
                }

		$form->register(null,'',new htmlcontrol('<h1>General Configuration</h1><hr size="1" />'));	
		$form->register('comments_notify',$i18n['comments_notify'],new listbuildercontrol($selected_users, $userlist));
		$form->register('items_per_page',$i18n['items_per_page'],new textcontrol($object->items_per_page));

		$form->register(null,'',new htmlcontrol('<h1>Comments</h1><hr size="1" />'));	
		$form->register('allow_comments',$i18n['allow_comments'],new checkboxcontrol($object->allow_comments));
		$form->register('use_captcha',exponent_lang_getText('Require CAPTCHA for comments?'),new checkboxcontrol($object->use_captcha));		
		$form->register('require_login',exponent_lang_getText('Require users to be logged in to post comments?'),new checkboxcontrol($object->require_login));		

        $form->register(null,'',new htmlcontrol('<h1>Tagging</h1><hr size="1" />'));
		$form->register('enable_tags',$i18n['enable_tags'], new checkboxcontrol($object->enable_tags));
		$form->register('collections',$i18n['tag_collections'],new listbuildercontrol($object->collections,$tc_list));
		//$form->register('group_by_tags',$i18n['group_by_tags'], new checkboxcontrol($object->group_by_tags));
		//$form->register(null,'',new htmlcontrol($i18n['show_tags_desc']));
		//$form->register('show_tags','',new listbuildercontrol($object->show_tags,$available_tags));


		$form->register(null,'',new htmlcontrol('<h1>Merge Blogs</h1><hr size="1" />'));
                $form->register('aggregate','Pull Events from These Other Blog Module',new listbuildercontrol($selected_blogs,$all_blogs));

		$form->register(null,'',new htmlcontrol('<h1>RSS Configuration</h1><hr size="1" />'));
       	 	$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
        	$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
        	$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->allow_comments = (isset($values['allow_comments']) ? 1 : 0);
		$object->use_captcha = (isset($values['use_captcha']) ? 1 : 0);
		$object->require_login = (isset($values['require_login']) ? 1 : 0);
    $object->enable_tags = (isset($values['enable_tags']) ? 1 : 0);
		//$object->show_tags = serialize(listbuildercontrol::parseData($values,'show_tags'));
		$object->collections = serialize(listbuildercontrol::parseData($values,'collections'));
		$object->comments_notify = serialize(listbuildercontrol::parseData($values,'comments_notify'));
	  $object->aggregate = serialize(listbuildercontrol::parseData($values,'aggregate'));
    $object->items_per_page = ($values['items_per_page'] > 0 ? $values['items_per_page'] : 10);
		$object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
		$object->enable_tags = (isset($values['enable_rss']) ? 1 : 0);
    $object->feed_title = $values['feed_title'];
    $object->feed_desc = $values['feed_desc'];
		return $object;
	}
}

?>

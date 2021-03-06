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
			$object->items_per_page = 10;
			$object->show_poster = 1;
			$object->allow_comments = 1;
			$object->approve_comments = 0;
			$object->comments_notify = serialize(array());
			$object->allow_replys = 0;
			$object->reply_title = "Response to Posting-";
			$object->final_message = $i18n['default_final_message'];
			$object->use_captcha = 1;
			$object->require_login = 0;
			$object->require_login_comments = 0;
			$object->email_title_post = "Exponent : New Weblog Post Added";
			$object->email_from_post = "Weblog Manager";
			$object->email_address_post = "weblog@".HOSTNAME;
			$object->email_reply_post = "weblog@".HOSTNAME;
			$object->email_showpost_post = 0;
			$object->email_signature = "--\nThanks, Webmaster";
			$object->aggregate = array();
			$object->enable_rss = false;
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->rss_limit = 25;
			$object->rss_cachetime = 1440;
			$object->enable_tags = false;
			$object->collections = array();
			$object->group_by_tags = 0;
			$object->show_tags = array();
		} else {
			$form->meta('id',$object->id);
			
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

		$selected_users = array();
		foreach(unserialize($object->comments_notify) as $i) {
			$selected_users[$i] = $db->selectValue('user', 'firstname', 'id='.$i) . ' ' . $db->selectValue('user', 'lastname', 'id='.$i) . ' (' . $db->selectValue('user', 'username', 'id='.$i) . ')';
		}

		$userlist = array();
		$list = exponent_users_getAllUsers();
		foreach ($list as $i) {
			if(!array_key_exists($i->id, $selected_users)) {
				$userlist[$i->id] = $i->firstname . ' ' . $i->lastname . ' (' . $i->username . ')';
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

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['general_conf'].'</h3><hr size="1" />'));
		$form->register('items_per_page',$i18n['items_per_page'],new textcontrol($object->items_per_page));
		$form->register('show_poster',$i18n['show_poster'],new checkboxcontrol($object->show_poster));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['comments'].'</h3><hr size="1" />'));
		$form->register('allow_comments',$i18n['allow_comments'],new checkboxcontrol($object->allow_comments));
		$form->register('approve_comments',$i18n['approve_comments'],new checkboxcontrol($object->approve_comments));
		$form->register('comments_notify',$i18n['comments_notify'],new listbuildercontrol($selected_users, $userlist));
		$form->register('allow_replys',$i18n['allow_replys'],new checkboxcontrol($object->allow_replys));
		$form->register('reply_title',$i18n['reply_subject_prefix'],new textcontrol($object->reply_title,45));
		$form->register('final_message',$i18n['final_message'],new htmleditorcontrol($object->final_message));
		$form->register('use_captcha',$i18n['use_captcha'],new checkboxcontrol($object->use_captcha));
		$form->register('require_login',$i18n['require_login'],new checkboxcontrol($object->require_login));
		$form->register('require_login_comments',$i18n['require_login_comments'],new checkboxcontrol($object->require_login_comments));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['new_post_monitor'].'</h3><hr size="1" />'));
		$form->register('email_title_post',$i18n['message_subject_prefix'],new textcontrol($object->email_title_post,45));
		$form->register('email_from_post',$i18n['from_display'],new textcontrol($object->email_from_post,45));
		$form->register('email_address_post',$i18n['from_email'],new textcontrol($object->email_address_post,45));
		$form->register('email_reply_post',$i18n['reply_to'],new textcontrol($object->email_reply_post,45));
		$form->register('email_showpost_post',$i18n['show_post_in_message'],new checkboxcontrol($object->email_showpost_post));
		$form->register('email_signature',$i18n['email_signature'],new texteditorcontrol($object->email_signature,5,30));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['merge_blogs'].'</h3><hr size="1" />'));
		$form->register('aggregate',$i18n['pull_posts'],new listbuildercontrol($selected_blogs,$all_blogs));

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

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}

	function update($values,$object) {
		$object->items_per_page = ($values['items_per_page'] > 0 ? $values['items_per_page'] : 10);
		$object->show_poster = (isset($values['show_poster']) ? 1 : 0);

		$object->allow_comments = (isset($values['allow_comments']) ? 1 : 0);
		$object->approve_comments = (isset($values['approve_comments']) ? 1 : 0);
		$object->comments_notify = serialize(listbuildercontrol::parseData($values,'comments_notify'));
		$object->allow_replys = (isset($values['allow_replys']) ? 1 : 0);
		$object->reply_title = $values['reply_title'];
		$object->final_message = htmleditorcontrol::parseData('final_message',$values);
		$object->use_captcha = (isset($values['use_captcha']) ? 1 : 0);
		$object->require_login = (isset($values['require_login']) ? 1 : 0);
		$object->require_login_comments = (isset($values['require_login_comments']) ? 1 : 0);
		
		$object->email_title_post = $values['email_title_post'];
		$object->email_from_post = $values['email_from_post'];
		$object->email_address_post = $values['email_address_post'];
		$object->email_reply_post = $values['email_reply_post'];
		$object->email_showpost_post = (isset($values['email_showpost_post']) ? 1 : 0);	
		$object->email_signature = $values['email_signature'];
		
		$object->aggregate = serialize(listbuildercontrol::parseData($values,'aggregate'));

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

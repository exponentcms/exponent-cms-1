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
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->collections = array();
		} else {
			$cols = unserialize($object->collections);
			$object->collections = array();
			foreach ($cols as $col_id) {
				$collection = $db->selectObject('tag_collections', 'id='.$col_id);
				$object->collections[$collection->id] = $collection->name;
			}
		}
		$opts  = array('ASC'=>$i18n['ascending'],'DESC'=>$i18n['descending']);
		$fields = array('posted'=>$i18n['posteddate'],'publish'=>$i18n['publishdate']);
		$form->register(null,'',new htmlcontrol('<div class="moduletitle">General Configuration</div><hr size="1" />'));
		$form->register('item_limit',$i18n['item_limit'],new textcontrol($object->item_limit));
		$form->register('sortorder',$i18n['sortorder'], new dropdowncontrol($object->sortorder,$opts));
		$form->register('sortfield',$i18n['sortfield'], new dropdowncontrol($object->sortfield,$fields));
		
		$form->register(null,'',new htmlcontrol('<div class="moduletitle">Tagging</div><hr size="1" />'));
		$form->register('enable_tags',$i18n['enable_tags'], new checkboxcontrol($object->enable_tags));
		$form->register('group_by_tags',$i18n['group_by_tags'], new checkboxcontrol($object->group_by_tags));
		$form->register('collections',$i18n['tag_collections'],new listbuildercontrol($object->collections,$tc_list));
		
		$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">RSS Configuration</div><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
		$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
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
		$object->collections = serialize(listbuildercontrol::parseData($values,'collections'));
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		if ($values['item_limit'] > 0) {
			$object->item_limit = $values['item_limit'];
		} else {
			$object->item_limit = 10;
		}
		//eDebug($object);exit();
		return $object;
	}
}

?>

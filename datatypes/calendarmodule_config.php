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

class calendarmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/calendarmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->enable_feedback = 0;
			$object->enable_rss = false;
      		$object->feed_title = "";
      		$object->feed_desc = "";
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register(null,'',new htmlcontrol('<div class="moduletitle">General Configuration</div><hr size="1" />'));
		$form->register('enable_categories',$i18n['enable_categories'],new checkboxcontrol($object->enable_categories,true));
		$form->register('enable_feedback',$i18n['enable_feedback'],new checkboxcontrol($object->enable_feedback,true));				
		$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">RSS Configuration</div><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
   		$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
   		$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = (isset($values['enable_categories']) ? 1 : 0);
		$object->enable_feedback = (isset($values['enable_feedback']) ? 1 : 0);
		$object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
    	$object->feed_title = $values['feed_title'];
    	$object->feed_desc = $values['feed_desc'];
		return $object;
	}
}

?>

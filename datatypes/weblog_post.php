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

class weblog_post {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/weblog_post.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->internal_name = '';
			$object->body = '';
			$object->publish = null;
			$object->unpublish = null;			
			global $user;
			$object->poster = $user->id;
			$object->is_private = 0;
			$object->is_draft = 0;
		} else {
			$form->meta('id',$object->id);
			if ($object->publish == 0) $object->publish = null;
			if ($object->unpublish == 0) $object->unpublish = null;
		}
		
		$form->register('title',$i18n['title'], new textcontrol($object->title));
		$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['publish_information'].'</h3><hr size="1" />'));
		//$form->register('publish',$i18n['publish'],new popupdatetimecontrol($object->publish,$i18n['nopublish']));
		$checked = empty($object->publish) ? true : false;
		$form->register('publish',$i18n['publish'],new yuidatetimecontrol($object->publish,$i18n['nopublish'], true, true, false, $checked));
		//$form->register('unpublish',$i18n['unpublish'],new popupdatetimecontrol($object->unpublish,$i18n['nounpublish']));
		$checked = empty($object->unpublish) ? true : false;
		$form->register('unpublish',$i18n['unpublish'],new yuidatetimecontrol($object->unpublish,$i18n['nounpublish'], true, true, false, $checked));

		$form->register('tag_header','',new htmlcontrol('<h3>'.$i18n['tags'].'</h3><hr size="1" />'));
		$form->register('is_private',$i18n['is_private'], new checkboxcontrol($object->is_private));
		$form->register('is_draft',$i18n['is_draft'], new checkboxcontrol($object->is_draft));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$values['internal_name']));
		$object->body = $values['body'];
		//$object->publish = popupdatetimecontrol::parseData('publish',$values);
		$object->publish = yuidatetimecontrol::parseData('publish',$values);
		if ($object->publish == null || $object->publish == 0) {
			$object->publish = $object->posted;
		}
		//$object->unpublish = popupdatetimecontrol::parseData('unpublish',$values);
		$object->unpublish = yuidatetimecontrol::parseData('unpublish',$values);
		$object->is_private = (isset($values['is_private']) ? 1 : 0);
		$object->is_draft = (isset($values['is_draft']) ? 1 : 0);
		return $object;
	}
}

?>

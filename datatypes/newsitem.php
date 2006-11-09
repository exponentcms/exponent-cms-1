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

class newsitem {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/newsitem.php');
	
		global $user;
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->internal_name = '';
			$object->body = '';
			$object->publish = null;
			$object->unpublish = null;
		} else {
			$form->meta('id',$object->id);
			if ($object->publish == 0) $object->publish = null;
			if ($object->unpublish == 0) $object->unpublish = null;
		}
	
		$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">News Content</div><hr size="1" />'));	
		$form->register('title',$i18n['title'],new textcontrol($object->title));
		$form->register('body',$i18n['body'],new htmleditorcontrol($object->body));
	
		$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">Publish Information</div><hr size="1" />'));
		$form->register('publish',$i18n['publish'],new popupdatetimecontrol($object->publish,$i18n['nopublish']));
		$form->register('unpublish',$i18n['unpublish'],new popupdatetimecontrol($object->unpublish,$i18n['nounpublish']));

		$form->register('tag_header','',new htmlcontrol('<br /><div class="moduletitle">Tags</div><hr size="1" />'));

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$object->title = $values['title'];
		$object->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$values['int']));
		$object->body = $values['body'];
		$object->publish = popupdatetimecontrol::parseData('publish',$values);
		$object->unpublish = popupdatetimecontrol::parseData('unpublish',$values);
		
		return $object;
	}
}

?>

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


class guestbook_post {
	function form($object) {
		global $db, $loc;
		#$config = $db->selectObject('guestbookmodule_config',"location_data='".serialize($loc)."'");
		#print_r($config);
		$i18n = exponent_lang_loadFile('datatypes/guestbook_post.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->body = '';
			$object->name = '';
			$object->email = '';
			$object->url = 'http://';
		} else {
			$form->meta('id',$object->id);
		}

		$form->register('name',$i18n['name'], new textcontrol($object->name));
		$form->register('email',$i18n['email'], new textcontrol($object->email));
		$form->register('url',$i18n['url'], new textcontrol($object->url));
		$form->register('title',$i18n['title'], new textcontrol($object->title));
		//This ist not working yet because I'm not able to read the config-Data into the form
			#if ($config->wysiwyg)
			#	$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
			#else
			#	$form->register('body',$i18n['body'], new texteditorcontrol($object->body));
		
		//use this so long: uncomment the Line you want...
		// ...for plain-Text editor
		#	$form->register('body',$i18n['body'], new texteditorcontrol($object->body));
		// ... or a WYSIWYG-Editor
		$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		exponent_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->email = $values['email'];
		$object->url = $values['url'];
		$object->title = $values['title'];
		$object->body = $values['body'];
		return $object;
	}
}
?>
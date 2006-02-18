<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class weblog_comment {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/weblog_comment.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			global $db;
			// Sanitize the parent_id parameter, to protect against injection attacks.
			$_GET['parent_id'] = intval($_GET['parent_id']);
			$post = $db->selectObject('weblog_post','id='. intval($_GET['parent_id']));
			
			$object->title = sprintf($i18n['re'],$post->title);
			$object->body = '';
			$form->meta('parent_id',intval($_GET['parent_id']));
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('title',$i18n['title'],new textcontrol($object->title));
		$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->body = $values['body'];
		return $object;
	}
}

?>
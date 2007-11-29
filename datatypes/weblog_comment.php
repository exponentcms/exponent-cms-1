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

class weblog_comment {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/weblog_comment.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			global $db;
			// Sanitize the parent_id parameter, to protect against injection attacks.
			//$_REQUEST['parent_id'] = intval($_REQUEST['parent_id']);
			//$post = $db->selectObject('weblog_post','id='. intval($_REQUEST['parent_id']));
			//$form->meta('parent_id',intval($_REQUEST['parent_id']));
			$object->name = '';
			$object->email = '';
			$object->body = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('email',$i18n['email'],new textcontrol($object->email));
		$form->register('body',$i18n['body'], new texteditorcontrol($object->body,8,60));
		if (SITE_USE_CAPTCHA && EXPONENT_HAS_GD) {
                	$form->register(null,'',new htmlcontrol(sprintf($i18n['captcha_description'],'<img src="'.PATH_RELATIVE.'captcha.php" />'),false));
                	$form->register('captcha_string','',new textcontrol('',6));
        	}
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',''));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->email = $values['email'];
		$object->body = $values['body'];
		return $object;
	}
}

?>

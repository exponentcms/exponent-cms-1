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

class guestbookmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/guestbookmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->allow_comments = 1;
			$object->items_per_page = 10;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('allow_comments',$i18n['allow_comments'],new checkboxcontrol($object->allow_comments));
		$form->register('items_per_page',$i18n['items_per_page'],new textcontrol($object->items_per_page));
		$form->register('wysiwyg',$i18n['wysiwyg'],new checkboxcontrol($object->wysiwyg));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->allow_comments = (isset($values['allow_comments']) ? 1 : 0);
		$object->items_per_page = ($values['items_per_page'] > 0 ? $values['items_per_page'] : 10);
		$object->wysiwyg = (isset($values['wysiwyg']) ? 1 : 0);
		return $object;
	}
}

?>
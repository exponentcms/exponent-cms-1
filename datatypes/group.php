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

class group {
	function form($object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$i18n = exponent_lang_loadFile('datatypes/group.php');
		
		$form = new form();
		if (!isset($object->id)) {
			// If the user object has no id, then this is a new user form.
			// Populate the empty user object with default attributes,
			// so that the calls to $form->register can confidently dereference
			// thes attributes.
			$object->name = '';
			$object->description = '';
			$object->inclusive = 1;
		} else {
			$form->meta('id',$object->id);
		}
		
		// Register the basic user profile controls.
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'],new texteditorcontrol($object->description));
		$form->register('inclusive',$i18n['inclusive'],new checkboxcontrol($object->inclusive));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->inclusive = (isset($values['inclusive']) ? 1 : 0);
		return $object;
	}
}

?>
<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

class group {
	function form($object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$i18n = pathos_lang_loadFile('datatypes/group.php');
		
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
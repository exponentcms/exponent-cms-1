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

class addressbookmodule_config {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->sort_type = 'lastname_asc';
		} else {
			$form->meta('id',$object->id);
		}
		
		$sort = array(
			'lastname_asc'=>'by Last Name, Alphabetical',
			'lastname_desc'=>'by Last Name, Reverse Alphabetical',
			'firstname_asc'=>'by First Name, Alphabetical',
			'firstname_desc'=>'by First Name, Reverse Alphabetical'
		);
		
		$form->register('sort_type','Sort Entries',new dropdowncontrol($object->sort_type,$sort));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->sort_type = $values['sort_type'];
		return $object;
	}
}

?>
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
		$i18n = pathos_lang_loadFile('datatypes/addresssbookmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->sort_type = 'lastname_asc';
		} else {
			$form->meta('id',$object->id);
		}
		
		$sort = array(
			'lastname_asc'=>$i18n['sort_last_asc'],
			'lastname_desc'=>$i18n['sort_last_desc'],
			'firstname_asc'=>$i18n['sort_first_asc'],
			'firstname_desc'=>$i18n['sort_first_desc']
		);
		
		$form->register('sort_type',$i18n['sort_entries'],new dropdowncontrol($object->sort_type,$sort));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->sort_type = $values['sort_type'];
		return $object;
	}
}

?>
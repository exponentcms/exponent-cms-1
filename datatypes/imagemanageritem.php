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

class imagemanageritem {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/imagemanageritem.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->scale = 20;
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'], new texteditorcontrol($object->description));
		if (!isset($object->id)) {
			$form->register(null,'',new htmlcontrol(exponent_core_maxUploadSizeMessage()));
			$form->register('file',$i18n['file'],new uploadcontrol());
		}
		$form->register('scale',$i18n['scale'],new textcontrol($object->scale));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->scale = intval($values['scale']);
		return $object;
	}
}

?>
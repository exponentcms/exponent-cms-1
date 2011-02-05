<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

class resourceitem {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/resourceitem.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
	
		$form = new form();
		if ($object == null) {
			$object->name = '';
			$object->description = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'],new htmleditorcontrol($object->description));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['posting_information'].'</h3><hr size="1" />'));
		$checked = empty($object->posted) ? true : false;
		$form->register('posted',$i18n['posted'],new yuidatetimecontrol($object->posted,$i18n['nopublish'], true, true, false, $checked));
		
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->posted = yuidatetimecontrol::parseData('posted',$values);
		if (empty($object->posted)) {
			$object->posted = time();
		}
		return $object;
	}
}

?>

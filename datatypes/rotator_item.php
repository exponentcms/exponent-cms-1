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

class rotator_item {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/rotator_item.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!$object) {
			$object->text = '';
			$form->meta('id',0);
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('text',$i18n['text'],new htmleditorcontrol($object->text));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($formvalues,$object = null) {
		$object->text = $formvalues['text'];
		return $object;
	}
}

?>
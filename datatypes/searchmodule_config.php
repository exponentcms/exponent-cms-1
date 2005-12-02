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

class searchmodule_config {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/searchmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->is_categorized = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('is_categorized',$i18n['is_categorized'],new checkboxcontrol($object->is_categorized,true));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->is_categorized = (isset($values['is_categorized']) ? 1 : 0);
		return $object;
	}
}

?>
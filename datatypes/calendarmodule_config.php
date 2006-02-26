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

class calendarmodule_config {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/calendarmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->enable_feedback = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('enable_categories',$i18n['enable_categories'],new checkboxcontrol($object->enable_categories,true));
		$form->register('enable_feedback',$i18n['enable_feedback'],new checkboxcontrol($object->enable_feedback,true));				
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = (isset($values['enable_categories']) ? 1 : 0);
		$object->enable_feedback = (isset($values['enable_feedback']) ? 1 : 0);
		return $object;
	}
}

?>

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

class contactmodule_config {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/contactmodule_config.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->subject = $i18n['default_subject'];
			$object->replyto_address = '';
			$object->from_name = $i18n['default_from_name'];
			$object->from_address = 'info@'.HOSTNAME;
			$object->final_message = $i18n['default_final_message'];
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('subject',$i18n['subject'],new textcontrol($object->subject));
		$form->register('from_name',$i18n['from_name'],new textcontrol($object->from_name));
		$form->register('from',$i18n['from'],new textcontrol($object->from_address));
		$form->register('replyto',$i18n['replyto'],new textcontrol($object->replyto_address));
		$form->register('final_message',$i18n['final_message'],new htmleditorcontrol($object->final_message));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$object->subject = $values['subject'];
		$object->from_name = $values['from_name'];
		$object->from_address = $values['from'];
		$object->replyto_address = $values['replyto'];
		$object->final_message = htmleditorcontrol::parseData('final_message',$values);
		return $object;
	}
}

?>
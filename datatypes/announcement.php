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

class announcement {
	function form($object = null) {
        $i18n = exponent_lang_loadFile('datatypes/announcement.php');
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!$object) {
			$object->text = '';
			$object->id = 0;
			$object->type = 'message';
			$object->publish = null;
			$object->unpublish = null;				
		} else {
			if ($object->publish == 0) $object->publish = null;
			if ($object->unpublish == 0) $object->unpublish = null;
		}
		$form->meta('id',$object->id);
		
		$opts  = array('message'=>$i18n['message'],'error'=>$i18n['error'],'hint'=>$i18n['hint']);
		$form->register('type',$i18n['type'], new dropdowncontrol($object->type,$opts));
		$form->register('text',$i18n['caption_text'],new htmleditorcontrol($object->text));
		//$form->register('text',$i18n['caption_text'],new yuieditorcontrol($object->text));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['publish_information'].'</h3><hr size="1" />'));
		$checked = empty($object->publish) ? true : false;
		$form->register('publish',$i18n['publish'],new yuidatetimecontrol($object->publish,$i18n['nopublish'], true, true, false, $checked));
		$checked = empty($object->unpublish) ? true : false;
		$form->register('unpublish',$i18n['unpublish'],new yuidatetimecontrol($object->unpublish,$i18n['nounpublish'], true, true, false, $checked));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		exponent_forms_cleanup();
		return $form;
	}
	
	function update($values,$object = null) {
		$object->type = $values['type'];
		$object->text = $values['text'];
		$object->publish = yuidatetimecontrol::parseData('publish',$values);
//		if ($object->publish == null || $object->publish == 0) {
//			$object->publish = $object->posted;
//		}
		$object->unpublish = yuidatetimecontrol::parseData('unpublish',$values);		
		
		return $object;
	}
}

?>

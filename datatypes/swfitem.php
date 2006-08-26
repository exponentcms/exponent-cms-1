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

class swfitem {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/swfitem.php');
	
		global $user;
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->bgcolor = '#FFFFFF';
			$object->height = 100;
			$object->width = 100;
			$object->alignment = 0;
			$object->swf_id = 0;
			$object->alt_image_id = 0;
			$object->unpublish = null;
			$object->loop_movie = 1;
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('bgcolor',$i18n['bgcolor'],new textcontrol($object->bgcolor),10,false,10);
		$form->register('height',$i18n['height'],new textcontrol($object->height,5,false,5,'integer'));
		$form->register('width',$i18n['width'],new textcontrol($object->width,5,false,5,'integer'));
		$align = array($i18n['center'],$i18n['left'],$i18n['right']);
		$form->register('alignment', $i18n['alignment'], new dropdowncontrol($object->alignment,$align));
		$form->register('loop_movie',$i18n['loop'],new checkboxcontrol($object->loop_movie,true));
		
		$form->register('swf_name',$i18n['swf_name'], new uploadcontrol());
		if ($object->swf_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.$i18n['keep_old_flash'].'<br />'));
		}
		$form->register('alt_image_name',$i18n['alt_image_name'],new uploadcontrol());
		if ($object->alt_image_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.$i18n['keep_old_image'].'<br />'));
		}
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		
		$object->name = $values['name'];
		$object->bgcolor = $values['bgcolor'];
		$object->height = $values['height'] + 0;
		$object->width = $values['width'] + 0;
		$object->alignment = $values['alignment'];
		$object->loop_movie = (isset($values['loop_movie']) ? 1 : 0);
		return $object;
	}
}
?>
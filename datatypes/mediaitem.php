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

class mediaitem {
	function form($object) {
	
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
			$object->media_id = 0;
			$object->loop_media = 0;
			$object->auto_rewind = 1;
			$object->autoplay = 0;
			$object->hide_controls = 0;
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('name','Media Name',new textcontrol($object->name));
		$form->register('bgcolor','Background Color of Media Player',new textcontrol($object->bgcolor),10,false,10);
		$form->register('width','Width of Media Player',new textcontrol($object->width,5,false,5,'integer'));
		$form->register('height','Height of Media Player',new textcontrol($object->height,5,false,5,'integer'));
		$align = array('Center','Left','Right');
		$form->register('alignment', 'Alignment', new dropdowncontrol($object->alignment,$align));
		
		$form->register('media_name','Media File', new uploadcontrol());
		if ($object->media_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.'Leave Media File blank to keep existing.'.'<br />'));
		}
		$form->register('loop_media', 'Auto-loop this video?', new checkboxcontrol($object->loop_media, true));
		$form->register('auto_rewind', 'Automatically "rewind" this video?', new checkboxcontrol($object->auto_rewind, true));
		$form->register('autoplay', 'Automatically play this video when the page loads?', new checkboxcontrol($object->autoplay,true));
		$form->register('hide_controls','Hide the controls on this player?', new checkboxcontrol($object->hide_controls,true));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		return $form;
	}
	
	function update($values,$object) {
		
		$object->name = $values['name'];
		$object->bgcolor = $values['bgcolor'];
		$object->height = $values['height'] + 0;
		$object->width = $values['width'] + 0;
		$object->alignment = $values['alignment'];
		$object->loop_media = (isset($values['loop_media']) ? 1 : 0);
		$object->auto_rewind = (isset($values['auto_rewind']) ? 1 : 0);
		$object->autoplay = (isset($values['autoplay']) ? 1 : 0);	
		$object->hide_controls = (isset($values['hide_controls']) ? 1 : 0);	
		return $object;
	}
}
?>
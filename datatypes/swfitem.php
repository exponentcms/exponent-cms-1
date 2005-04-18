<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

// GREP:HARDCODEDTEXT

class swfitem {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','swfmodule');
	
		global $user;
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
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
			$object->loop = 1;
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('name',TR_SWFMODULE_NAME,new textcontrol($object->name));
		$form->register('bgcolor',TR_SWFMODULE_BGCOLOR,new textcontrol($object->bgcolor),10,false,10);
		$form->register('height',TR_SWFMODULE_HEIGHT,new textcontrol($object->height,5,false,5,'integer'));
		$form->register('width',TR_SWFMODULE_WIDTH,new textcontrol($object->width,5,false,5,'integer'));
		$align = array(TR_CORE_CENTER,TR_CORE_LEFT,TR_CORE_RIGHT);
		$form->register('alignment', TR_SWFMODULE_ALIGNMENT, new dropdowncontrol($object->alignment,$align));
		$form->register('loop','Loop Animation',new checkboxcontrol($object->loop,true));
		
		$form->register('swf_name',TR_SWFMODULE_FLASHFILE, new uploadcontrol());
		if ($object->swf_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.TR_SWFMODULE_KEEPOLDFLASH.'<br>'));
		}
		$form->register('alt_image_name',TR_SWFMODULE_ALTIMAGE,new uploadcontrol());
		if ($object->alt_image_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.TR_SWFMODULE_KEEPOLDIMAGE.'<br>'));
		}
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		return $form;
	}
	
	function update($values,$object) {
		
		$object->name = $values['name'];
		$object->bgcolor = $values['bgcolor'];
		$object->height = $values['height'] + 0;
		$object->width = $values['width'] + 0;
		$object->alignment = $values['alignment'];
		$object->loop = (isset($values['loop']) ? 1 : 0);
		return $object;
	}
}
?>
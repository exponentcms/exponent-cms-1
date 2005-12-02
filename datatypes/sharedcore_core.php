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

class sharedcore_core {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/sharedcore_core.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->path = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('path',$i18n['path'],new textcontrol($object->path));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		
		$object->path = $values['path'];
		if ($object->path{0} != '/') {
			$object->path = '/'.$object->path;
		}
		if (substr($object->path,-1,1) != '/') {
			$object->path = $object->path.'/';
		}
		
		return $object;
	}
}

?>
<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

class imagemanageritem {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/imagemanageritem.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->scale = 20;
		} else {
			$form->meta('id',$object->id);
		}
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'], new texteditorcontrol($object->description));
		if (!isset($object->id)) {
			$form->register(null,'',new htmlcontrol(pathos_core_maxUploadSizeMessage()));
			$form->register('file',$i18n['file'],new uploadcontrol());
		}
		$form->register('scale',$i18n['scale'],new textcontrol($object->scale));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->scale = $values['scale'];
		return $object;
	}
}

?>
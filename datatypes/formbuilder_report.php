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

class formbuilder_report {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/formbuilder_report.php');
	
		global $db;
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->text = '';
			$object->column_names = '';
		}
		
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'],new texteditorcontrol($object->description));
		$form->register(null,'', new htmlcontrol('<br /><br />'.$i18n['blank_report_message'].'<br /><br />'));
		$form->register('text',$i18n['text'],new htmleditorcontrol($object->text));
		
		$fields = array();
		$column_names = array();
		$cols = array();
		if ($object->column_names != '') {
			$cols = explode('|!|',$object->column_names);
		}
		if (isset($object->form_id)) {
			foreach ($db->selectObjects('formbuilder_control','form_id='.$object->form_id.' and is_readonly=0') as $control) {
				$ctl = unserialize($control->data);
				$control_type = get_class($ctl);
				$def = call_user_func(array($control_type,'getFieldDefinition'));
				if ($def != null) {
					$fields[$control->name] = $control->caption;
					if (in_array($control->name,$cols)) {
						$column_names[$control->name] = $control->caption;
					}
				}
			}
			$fields['ip'] = $i18n['field_ip'];
			if (in_array('ip',$cols)) $column_names['ip'] = $i18n['field_id'];
			$fields['user_id'] = $field['field_user_id'];
			if (in_array('user_id',$cols)) $column_names['user_id'] = $i18n['field_user_id'];
			$fields['timestamp'] = $i18n['field_timestamp'];
			if (in_array('timestamp',$cols)) $column_names['timestamp'] = $i18n['field_timestamp'];
		}

		$form->register('column_names',$i18n['column_names'], new listbuildercontrol($column_names,$fields));
		$form->register(null,'', new htmlcontrol('<br /><br /><br />'));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values, $object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->text = htmleditorcontrol::parseData('text',$values);
		$object->column_names = $values['column_names'];
		return $object;
	}
}
?>
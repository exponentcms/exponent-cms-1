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

class wizard_form {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/formbuilder_form.php');
		
		global $db;
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
		//global $user;
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
		} else {
			$form->meta('id',$object->id);
			$form->meta('wizard_page_id',$object->wizard_page_id);
			$form->meta('table_name',$object->table_name);
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->wizard_page_id = $values['wizard_page_id'];
		$object->table_name = $values['table_names'];
		return $object;
	}
	
	function updateTable($object) {
		global $db;
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		//if ($object->is_saved == 1) {
			$datadef =  array(
				'id'=>array(
					DB_FIELD_TYPE=>DB_DEF_ID,
					DB_PRIMARY=>true,
					DB_INCREMENT=>true),
				'ip'=>array(
					DB_FIELD_TYPE=>DB_DEF_STRING,
					DB_FIELD_LEN=>25),
				'timestamp'=>array(
					DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
				'user_id'=>array(
					DB_FIELD_TYPE=>DB_DEF_ID),
				'optional_value_1'=>array(
					DB_FIELD_TYPE=>DB_DEF_ID),
				'optional_value_2'=>array(
					DB_FIELD_TYPE=>DB_DEF_ID)
			);
			 
			if (!isset($object->id)) {
				$object->table_name = preg_replace('/[^A-Za-z0-9]/','_',"wiz_".$object->wizard_id."_".$object->name);
				//$tablename = 'wizard_'.$object->table_name;
				$tablename = $object->table_name;
				$index = '';
				while ($db->tableExists($tablename . $index)) {
					$index++;
				}
				//$tablename = $tablename.$index;
				$db->createTable($tablename,$datadef,array());
				$object->table_name .= $index; 
			} else {
				if ($object->table_name == '') {
					$tablename = preg_replace('/[^A-Za-z0-9]/','_',"wiz_".$object->wizard_id."_".$object->name);
					$index = '';
					while ($db->tableExists($tablename . $index)) {
						$index++;
					}
					$object->table_name = $tablename . $index;
				}
				
				$tablename = $object->table_name;
				
				//If table is missing, create a new one.
				if (!$db->tableExists($tablename)) {
					$db->createTable($tablename,$datadef,array());
				}

				$ctl = null;
				$control_type = '';
				$tempdef = array();
				foreach ($db->selectObjects('wizard_control','form_id='.$object->id) as $control) {
					if ($control->is_readonly == 0) {
						$ctl = unserialize($control->data);
						$ctl->identifier = $control->name;
						$ctl->caption = $control->caption;
						$ctl->id = $control->id;
						$control_type = get_class($ctl);
						$def = call_user_func(array($control_type,'getFieldDefinition'));
						if ($def != null) {
							$tempdef[$ctl->identifier] = $def;
						}
					}
				}
				$datadef = array_merge($datadef,$tempdef);
				$db->alterTable($tablename,$datadef,array(),true);
			}
		//}
		return $object->table_name;
	}
	
}

?>

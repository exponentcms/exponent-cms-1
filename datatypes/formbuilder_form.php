<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

class formbuilder_form {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','formbuilder');
		
		global $db;
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
		//global $user;
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->is_email = 0;
			$object->is_saved = 1;
			$object->response = 'Your form has been submitted.';
			$object->resetbtn = 'Reset';
			$object->submitbtn = 'Submit';
			$object->subject = 'Submitted form from site.';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name',TR_FORMBUILDER_NAME,new textcontrol($object->name));
		$form->register('description',TR_FORMBUILDER_DESCRIPTION,new texteditorcontrol($object->description));
		$form->register('response',TR_FORMBUILDER_RESPONSE, new htmleditorcontrol($object->response));
		
		$form->register(null,'', new htmlcontrol('<br><br><b>'.TR_FORMBUILDER_BUTTONHEADER.'</b><br><hr><br>'));
		$form->register('submitbtn',TR_FORMBUILDER_SUBMITTEXT, new textcontrol($object->submitbtn));
		$form->register('resetbtn',TR_FORMBUILDER_RESETTEXT, new textcontrol($object->resetbtn));
		$form->register(null,'', new htmlcontrol('<br><br><b>'.TR_FORMBUILDER_EMAILHEADER.'</b><br><hr><br>'));
		$form->register('is_email',TR_FORMBUILDER_EMAILFORM,new checkboxcontrol($object->is_email,false));
		
		$userlist = array();
		$users = pathos_users_getAllUsers();
		foreach ($users as $locuser) {
			$userlist[$locuser->id] = $locuser->username;
		}
		$defaults = array();
		foreach ($db->selectObjects('formbuilder_address','form_id='.$object->id.' and user_id != 0') as $address) {
			$locuser =  pathos_users_getUserById($address->user_id);
			$defaults[$locuser->id] = $locuser->username;
		} 
		
		$form->register('users',TR_FORMBUILDER_USERS,new listbuildercontrol($defaults,$userlist));
		$groups = pathos_users_getAllGroups();
		$grouplist = array();
		$defaults = array();
		foreach ($groups as $group) {
			$grouplist[$group->id] = $group->name;
		}
		if ($grouplist != null) {
			foreach ($db->selectObjects('formbuilder_address','form_id='.$object->id.' and group_id != 0') as $address) {
				$group =  pathos_users_getGroupById($address->group_id);
				$defaults[$group->id] = $group->name;
			}
			
			$form->register('groups',TR_FORMBUILDER_GROUPS,new listbuildercontrol($defaults,$grouplist));
		}
		
		$defaults = array();
		foreach ($db->selectObjects('formbuilder_address','form_id='.$object->id." and email != ''") as $address) {
			$defaults[$address->email] = $address->email;
		}
		
		$form->register('addresses',TR_FORMBUILDER_OTHERADDRESSES,new listbuildercontrol($defaults,array()));
		$form->register('subject',TR_FORMBUILDER_EMAILSUBJECT,new textcontrol($object->subject));
		$form->register(null,'', new htmlcontrol('<br><br><b>'.TR_FORMBUILDER_DBHEADER.'</b><br><hr size="1"><br>'));
		$form->register('is_saved',TR_FORMBUILDER_SAVETODB,new checkboxcontrol($object->is_saved,false));
		$form->register(null,'', new htmlcontrol('<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.TR_FORMBUILDER_DATALOSSWARNING.'<br>'));
		if ($object->is_saved) {
			$form->controls['is_saved']->disabled = true;
			$form->meta('is_saved','1');
		}
		$form->register(null,'', new htmlcontrol('<br><br><br>'));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->is_email = isset($values['is_email']);
		$object->is_saved = isset($values['is_saved']);
		$object->response = $values['response'];
		$object->submitbtn = $values['submitbtn'];
		$object->resetbtn = $values['resetbtn'];
		$object->subject = $values['subject'];
		return $object;
	}
	
	function updateTable($object) {
		global $db;
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		if ($object->is_saved) {
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
					DB_FIELD_TYPE=>DB_DEF_ID)
			);
			 
			if (!isset($object->id)) {
				$object->table_name = preg_replace('/[^A-Za-z0-9]/','_',$object->name);
				$tablename = 'formbuilder_'.$object->table_name;
				$index = '';
				while ($db->tableExists($tablename . $index)) {
					$index++;
				}
				$tablename = $tablename.$index;
				$db->createTable($tablename,$datadef,array());
				$object->table_name .= $index; 
			} else {
				if ($object->table_name == '') {
					$tablename = preg_replace('/[^A-Za-z0-9]/','_',$object->name);
					$index = '';
					while ($db->tableExists('formbuilder_' . $tablename . $index)) {
						$index++;
					}
					$object->table_name = $tablename . $index;
				}
				
				$tablename = 'formbuilder_'.$object->table_name;
				
				//If table is missing, create a new one.
				if (!$db->tableExists($tablename)) {
					$db->createTable($tablename,$datadef,array());
				}
			
				$ctl = null;
				$control_type = '';
				$tempdef = array();
				foreach ($db->selectObjects('formbuilder_control','form_id='.$object->id) as $control) {
					if (!$control->is_readonly) {
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
		}
		return $object->table_name;
		pathos_forms_cleanup();
	}
	
}

?>
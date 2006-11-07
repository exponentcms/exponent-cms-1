<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: faqmodule_config.php,v 1.4 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class wizardmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/formbuilder_form.php');

		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
		exponent_forms_initialize();
	
		global $db;	
		$wizards = $db->selectObjects("wizard");
		foreach ($wizards as $wizards => $wizard) {
			$wiz_list[$wizard->id] = $wizard->name;
		} 
	
		//eDebug($wiz_list); exit();

		$form = new form();
		if (!isset($object->id)) {
			$object->id = 1;
			$object->is_email = 0;
                        $object->is_saved = 0;
                        $object->wizard_id = '';
                        $object->response = $i18n['default_response'];
                        $object->resetbtn = $i18n['default_resetbtn'];
                        $object->submitbtn = $i18n['default_submitbtn'];
                        $object->subject = $i18n['default_subject'];
		} else {
			$form->meta('id',$object->id);
		}

		$form->meta("src", $object->src);

		if ( isset($wiz_list) ) {
			$form->register('wizard_id','Choose a Wizard: ',new dropdowncontrol($object->wizard_id,$wiz_list));
		} else {
			$form->register(null,'', new htmlcontrol('No Wizards have been created yet.  You must first create one or more wizards.'));
			$form->register(null,'', new htmlcontrol('<br><br>To create wizards go to the "Manage Wizard" section of the Administration Control Panel.'));
			return $form;
		}
		
		$form->register('response',$i18n['response'], new htmleditorcontrol($object->response));
		
                $form->register(null,'', new htmlcontrol('<br><br><b>'.$i18n['button_header'].'</b><br><hr><br>'));
                $form->register('submitbtn',$i18n['submitbtn'], new textcontrol($object->submitbtn));
                $form->register('resetbtn',$i18n['resetbtn'], new textcontrol($object->resetbtn));
                $form->register(null,'', new htmlcontrol('<br><br><b>'.$i18n['email_header'].'</b><br><hr><br>'));
                $form->register('is_email',$i18n['is_email'],new checkboxcontrol($object->is_email,false));

                $userlist = array();
                $users = exponent_users_getAllUsers();
                foreach ($users as $locuser) {
                        $userlist[$locuser->id] = $locuser->username;
                }
                $defaults = array();
                foreach ($db->selectObjects('wizard_address','wizard_id='.$object->wizard_id.' and user_id != 0') as $address) {
                        $locuser =  exponent_users_getUserById($address->user_id);
                        $defaults[$locuser->id] = $locuser->username;
                }

                $form->register('users',$i18n['users'],new listbuildercontrol($defaults,$userlist));
                $groups = exponent_users_getAllGroups();
                $grouplist = array();
                $defaults = array();
                foreach ($groups as $group) {
                        $grouplist[$group->id] = $group->name;
                }
                if ($grouplist != null) {
                        foreach ($db->selectObjects('wizard_address','wizard_id='.$object->wizard_id.' and group_id != 0') as $address) {
                                $group =  exponent_users_getGroupById($address->group_id);
                                $defaults[$group->id] = $group->name;
                        }

                        $form->register('groups',$i18n['groups'],new listbuildercontrol($defaults,$grouplist));
                }

                $defaults = array();
                foreach ($db->selectObjects('wizard_address','wizard_id='.$object->wizard_id." and email != ''") as $address) {
                        $defaults[$address->email] = $address->email;
                }

                $form->register('addresses',$i18n['addresses'],new listbuildercontrol($defaults,null));
                $form->register('subject',$i18n['subject'],new textcontrol($object->subject));
                $form->register(null,'', new htmlcontrol('<br /><br /><b>'.$i18n['database_header'].'</b><br /><hr size="1" /><br />'));
                $form->register('is_saved',$i18n['is_saved'],new checkboxcontrol($object->is_saved,false));
                $form->register(null,'', new htmlcontrol('<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i18n['warning_data_loss'].'<br />'));
                if ($object->is_saved == 1) {
                        $form->controls['is_saved']->disabled = true;
                        $form->meta('is_saved','1');
                }
                $form->register(null,'', new htmlcontrol('<br /><br /><br />'));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->wizard_id= $values['wizard_id'];
		$object->is_email = (isset($values['is_email']) ? 1 : 0);
                $object->is_saved = (isset($values['is_saved']) ? 1 : 0);
                $object->response = $values['response'];
                $object->submitbtn = $values['submitbtn'];
                $object->resetbtn = $values['resetbtn'];
                $object->subject = $values['subject'];
		return $object;
	}
}

?>

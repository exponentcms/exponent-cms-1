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

class approvalpolicy {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/approvalpolicy.php');
	
		if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
	
		$form = new form();
		if (isset($object->id)) {
			$form->meta('id',$object->id);
		} else {
			$object->name = '';
			$object->description = '';
			$object->max_approvers = 0;
			$object->required_approvals = 0;
			$object->on_deny = SYS_WORKFLOW_REVOKE_NONE;
			$object->on_edit = SYS_WORKFLOW_REVOKE_NONE;
			$object->on_approve = SYS_WORKFLOW_REVOKE_NONE;
			$object->delete_on_deny = 0;
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('description',$i18n['description'],new texteditorcontrol($object->description));
		$form->register('max_approvers',$i18n['max_approvers'],new textcontrol($object->max_approvers));
		$form->register('required_approvals',$i18n['required_approvals'],new textcontrol($object->required_approvals));
		
		$list = array(
			SYS_WORKFLOW_REVOKE_NONE=>$i18n['revoke_none'],
			SYS_WORKFLOW_REVOKE_ALL=>$i18n['revoke_all'],
			SYS_WORKFLOW_REVOKE_POSTER=>$i18n['revoke_poster'],
			SYS_WORKFLOW_REVOKE_APPROVERS=>$i18n['revoke_approvers'],
			SYS_WORKFLOW_REVOKE_OTHERS=>$i18n['revoke_others']
		);
		
		$form->register('on_approve',$i18n['on_approve'],new dropdowncontrol($object->on_approve,$list));
		$form->register('on_edit',$i18n['on_edit'],new dropdowncontrol($object->on_edit,$list));
		$form->register('on_deny',$i18n['on_deny'],new dropdowncontrol($object->on_deny,$list));
		$form->register('delete_on_deny',$i18n['delete_on_deny'],new checkboxcontrol($object->delete_on_deny));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->max_approvers = $values['max_approvers'];
		$object->required_approvals = $values['required_approvals'];
		$object->on_deny = $values['on_deny'];
		$object->on_edit = $values['on_edit'];
		$object->on_approve = $values['on_approve'];
		$object->delete_on_deny = (isset($values['delete_on_deny']) ? 1 : 0);
		return $object;
	}
}

?>
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

class approvalpolicy {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','workflow');
	
		if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
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
		
		$form->register('name',TR_WORKFLOW_POLICYNAME,new textcontrol($object->name));
		$form->register('description',TR_WORKFLOW_POLICYDESC,new texteditorcontrol($object->description));
		$form->register('max_approvers',TR_WORKFLOW_MAXAPPROVERS,new textcontrol($object->max_approvers));
		$form->register('required_approvals',TR_WORKFLOW_REQUIREDAPPROVALS,new textcontrol($object->required_approvals));
		
		$list = array(
			SYS_WORKFLOW_REVOKE_NONE=>TR_WORKFLOW_REVOKENONE,
			SYS_WORKFLOW_REVOKE_ALL=>TR_WORKFLOW_REVOKEALL,
			SYS_WORKFLOW_REVOKE_POSTER=>TR_WORKFLOW_REVOKEPOSTER,
			SYS_WORKFLOW_REVOKE_APPROVERS=>TR_WORKFLOW_REVOKEAPPROVERS,
			SYS_WORKFLOW_REVOKE_OTHERS=>TR_WORKFLOW_REVOKEOTHERS
		);
		
		$form->register('on_approve',TR_WORKFLOW_ONAPPROVE,new dropdowncontrol($object->on_approve,$list));
		$form->register('on_edit',TR_WORKFLOW_ONEDIT,new dropdowncontrol($object->on_edit,$list));
		$form->register('on_deny',TR_WORKFLOW_ONDENY,new dropdowncontrol($object->on_deny,$list));
		$form->register('delete_on_deny',TR_WORKFLOW_DELETEONDENY,new checkboxcontrol($object->delete_on_deny));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		
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
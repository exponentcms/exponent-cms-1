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
		if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (isset($object->id)) {
			$form->meta("id",$object->id);
		} else {
			$object->name = "";
			$object->description = "";
			$object->max_approvers = 0;
			$object->required_approvals = 0;
			$object->on_deny = SYS_WORKFLOW_REVOKE_NONE;
			$object->on_edit = SYS_WORKFLOW_REVOKE_NONE;
			$object->on_approve = SYS_WORKFLOW_REVOKE_NONE;
			$object->delete_on_deny = 0;
		}
		
		$form->register("name","Policy Name",new textcontrol($object->name));
		$form->register("description","Description",new texteditorcontrol($object->description));
		$form->register("max_approvers","Maximum Number of Approvers",new textcontrol($object->max_approvers));
		$form->register("required_approvals","Number of Approvals Required for Publication",new textcontrol($object->required_approvals));
		
		$list = array(
			SYS_WORKFLOW_REVOKE_NONE=>"Revoke None",
			SYS_WORKFLOW_REVOKE_ALL=>"Revoke All Approvals",
			SYS_WORKFLOW_REVOKE_POSTER=>"Revoke Poster's Approval",
			SYS_WORKFLOW_REVOKE_APPROVERS=>"Revoke Approvers' Approvals",
			SYS_WORKFLOW_REVOKE_OTHERS=>"Revoke Poster and Approver's Approvals"
		);
		
		$form->register("on_approve","When approved 100%",new dropdowncontrol($object->on_approve,$list));
		$form->register("on_edit","When edited and approved",new dropdowncontrol($object->on_edit,$list));
		$form->register("on_deny","When not approved",new dropdowncontrol($object->on_deny,$list));
		$form->register("delete_on_deny","Delete content if not approved?",new checkboxcontrol($object->delete_on_deny));
		$form->register("submit","",new buttongroupcontrol("Save"));
		
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
		$object->delete_on_deny = isset($values['delete_on_deny']);
		return $object;
	}
}

?>
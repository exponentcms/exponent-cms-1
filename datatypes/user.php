<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt
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

class user {
	function form($object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$i18n = pathos_lang_loadFile('datatypes/user.php');
		
		$form = new form();
		if (!isset($object->id)) {
			// If the user object has no id, then this is a new user form.
			// Populate the empty user object with default attributes,
			// so that the calls to $form->register can confidently dereference
			// thes attributes.
			$object->firstname = '';
			$object->lastname = '';
			$object->email = '';
			// Username and Password can only be specified for a new user.  To change the password,
			// a different form is used (part of the loginmodule)
			$form->register('username',$i18n['desired_username'],new textcontrol());
			$form->register('pass1',$i18n['pass1'], new passwordcontrol());
			$form->register('pass2',$i18n['pass2'],new passwordcontrol());
			$form->register(null,'',new htmlcontrol('<br />'));
		} else {
			$form->meta("id",$object->id);
		}
		
		// Register the basic user profile controls.
		$form->register('firstname',$i18n['firstname'],new textcontrol($object->firstname));
		$form->register('lastname',$i18n['lastname'],new textcontrol($object->lastname));
		$form->register(null,'',new htmlcontrol('<br />'));
		$form->register('email',$i18n['email'],new textcontrol($object->email));
		$form->register(null,'',new htmlcontrol('<br />'));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->firstname = $values['firstname'];
		$object->lastname = $values['lastname'];
		$object->email = $values['email'];
		return $object;
	}
}

?>
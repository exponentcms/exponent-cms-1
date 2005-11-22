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

class contact_contact {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/contact_contact.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$type = 0;
		$default = 0;
		
		$form = new form();
		if (!isset($object->id)) {
			$object->user_id = 0;
			$object->addressbook_contact_id = 0;
			$object->email = '';
			$object->contact_info = '';
		} else {
			$form->meta('id',$object->id);
			if ($object->user_id != 0) {
				$type = 0;
				$default = $object->user_id;
			} else {
				$type = 1;
				$default = $object->email;
			}
		}
		
		$form->register('contact',$i18n['contact'],new contactcontrol($default,$type));
		
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		switch ($values['contact_type']) {
			case 0:
				$object->user_id = $values['contact'][0];
				$object->email = '';
				break;
			case 1:
				$object->user_id = 0;
				$object->email = $values['contact'][1];
				break;
		}
		return $object;
	}
}

?>
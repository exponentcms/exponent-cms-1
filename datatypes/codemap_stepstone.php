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
# $Id: codemap_stepstone.php,v 1.4 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class codemap_stepstone {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->status = 0;
			$object->developer = '';
			$object->contact = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name','Name',new textcontrol($object->name));
		$form->register('description','Description',new texteditorcontrol($object->description));
		$form->register('developer','Developer',new textcontrol($object->developer));
		$form->register('contact','Contact',new textcontrol($object->contact));
		
		$form->register(uniqid(''),'',new htmlcontrol('How far along is this task?'));
		$form->register(uniqid(''),'Not Yet Started',new radiocontrol(($object->status == 0 ? 1 : 0),0,'status',true));
		$form->register(uniqid(''),'In Progress',new radiocontrol(($object->status == 1 ? 1 : 0),1,'status',true));
		$form->register(uniqid(''),'Completed',new radiocontrol(($object->status == 2 ? 1 : 0),2,'status',true));
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->contact = $values['contact'];
		$object->developer = $values['developer'];
		$object->status = $values['status'];
		return $object;
	}
}

?>
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
# $Id: tasklist_task.php,v 1.2 2005/04/25 19:02:17 filetreefrog Exp $
##################################################

class tasklist_task {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->description = '';
			$object->priority = 5;
			$object->completion = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$priorities = array(
			9=>'9 - Highest',
			8=>'8',
			7=>'7',
			6=>'6',
			5=>'5 - Medium',
			4=>'4',
			3=>'3',
			2=>'2',
			1=>'1 - Lowest',
		);
		
		$completion = array();
		for ($i = 0; $i < 100; $i+= 5) {
			$completion[$i] = $i.' %';
		}
		$completion[0] = '0% - Not Started';
		$completion[100] = '100% - Finished';
		
		$form->register('name','Name',new textcontrol($object->name));
		$form->register('description','Description',new texteditorcontrol($object->description,10,40));
		$form->register('priority','Priority',new dropdowncontrol($object->priority,$priorities));
		$form->register('completion','Progress',new dropdowncontrol($object->completion,$completion));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->priority = $values['priority'];
		$object->completion = $values['completion'];
		return $object;
	}
}

?>
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
# $Id: articlemodule_config.php,v 1.3 2005/04/25 19:02:15 filetreefrog Exp $
##################################################

class articlemodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->allow_submissions = 0;
			$object->require_login = 0;
		} else {
			$form->meta('id',$object->id);
		}
	
		$form->register(null,'', new htmlcontrol('<h2>User Article Submission</h2>'));	
		$form->register('allow_submissions','Allow users to submit articles?',new checkboxcontrol($object->allow_submissions,true));
		$form->register('require_login','Require user to create account before submitting an article?',new checkboxcontrol($object->require_login,true));
		$form->register(null,'', new htmlcontrol('<h2>Categories</h2>'));	
		$form->register('enable_categories','Enable Categories?',new checkboxcontrol($object->enable_categories,true));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = empty($values['enable_categories']) ? 0 : 1;
		$object->allow_submissions = empty($values['allow_submissions']) ? 0 : 1;
		$object->require_login = empty($values['require_login']) ? 0 : 1;
		// Change this later to do some better recalculation detection (more efficient)
		$object->recalc = 1;
		return $object;
	}
}

?>

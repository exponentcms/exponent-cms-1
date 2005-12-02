<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class CHANGEME {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			
		} else {
			$form->meta('id',$object->id);
		}
		// GREP:I18N in sdk/blanks files
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		return $form;
	}
	
	function update($values,$object) {
		
		return $object;
	}
}

?>
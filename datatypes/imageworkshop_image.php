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
# $Id: imageworkshop_image.php,v 1.2 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class imageworkshop_image {
	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register('name','Image Name',new textcontrol());
		$form->register('file','Upload Image',new uploadcontrol());
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		return $object;
	}
}

?>
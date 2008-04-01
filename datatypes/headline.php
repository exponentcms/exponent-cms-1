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
# License, or (at your option) any later version.A
#A
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
# $Id: listing.php,v 1.4 2005/05/10 18:32:14 filetreefrog Exp $
##################################################

class headline {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->headline = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('headline','headline',new textcontrol($object->name,50,false,200));
		return $form;
	}
	
	// function update($values,$object) {
	// 	$object->name = $values['name'];
	// 	$object->summary = $values['summary'];
	// 	$object->body = $values['body'];
	// 	return $object;
	// }
}

?>
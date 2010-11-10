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
# $Id: imagegallery_image.php,v 1.7 2005/04/26 03:07:22 filetreefrog Exp $
##################################################

class imagegallery_image {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$form->meta('parent',$object->gallery_id);
			$object->name = '';
			$object->alt = '';
			$object->description = '';
			$object->newwindow = 1;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name','Name',new textcontrol($object->name));
		$form->register('alt','Alt Tag',new textcontrol($object->alt));
		
		global $db;
		$imagenames = array();
		if (!isset($object->rank) || $object->rank != 0) $imagenames[0] = 'At the Beginning';
		foreach ($db->selectObjects('imagegallery_image','gallery_id='.$object->gallery_id . ' ORDER BY rank ASC') as $i) {
			if ($object->rank == $i->rank) $imagenames[$i->rank+1] = '(current place)';
			else if ($object->rank != $i->rank+1) $imagenames[$i->rank+1] = 'After "' . $i->name . '"';
		}
		if (!isset($object->id)) $object->rank = count($imagenames)-1;
		
		$form->register('rank','Place Image',new dropdowncontrol($object->rank+1,$imagenames));
		$form->register('description','Description',new htmleditorcontrol($object->description));
		$form->register('newwindow','Open in New Window',new checkboxcontrol($object->newwindow,true));
		if (!isset($object->id)) $form->register('file','Image',new uploadcontrol());
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->alt = $values['alt'];
		$object->description = $values['description'];
		$object->newwindow = (isset($values['newwindow']) ? 1 : 0);
		return $object;
	}
}

?>

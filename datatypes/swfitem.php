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

class swfitem {
	function form($object) {
		global $user;
	
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->description = "";
			$object->bgcolor = "#FFFFFF";
			$object->height = 100;
			$object->width = 100;
			$object->alignment = 0;
			$object->swf_id = 0;
			$object->alt_image_id = 0;
			$object->unpublish = null;
		} else {
			$form->meta("id",$object->id);
		}
		$form->register("name","Name",new textcontrol($object->name));
		$form->register("bgcolor","Background Color",new textcontrol($object->bgcolor),10,false,10);
		$form->register("height","Flash Height",new textcontrol($object->height,5,false,5,"integer"));
		$form->register("width","Flash Width",new textcontrol($object->width,5,false,5,"integer"));
		$align = array("Center","Left","Right");
		$form->register("alignment", "Alignment", new dropdowncontrol($object->alignment,$align));
		
		$form->register("swf_name","Flash File", new uploadcontrol());
		if ($object->swf_id != 0) {
			$form->register(uniqid(""),"", new htmlcontrol("&nbsp;&nbsp;&nbsp;*Leave Flash File blank to keep existing.<br>"));
		}
		$form->register("alt_image_name","No Flash<br>Alternative Image",new uploadcontrol());
		if ($object->alt_image_id != 0) {
			$form->register(uniqid(""),"", new htmlcontrol("&nbsp;&nbsp;&nbsp;*Leave Alternative Image blank to keep existing.<br>"));
		}
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		
		$object->name = $values['name'];
		$object->bgcolor = $values['bgcolor'];
		$object->height = $values['height'] + 0;
		$object->width = $values['width'] + 0;
		$object->alignment = $values['alignment'];
		return $object;
	}
}
?>
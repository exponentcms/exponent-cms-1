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

class contactmodule_config {
	function form($object) {
	
		pathos_lang_loadDictionary('modules','contactmodule');
		pathos_lang_loadDictionary('standard','core');
		
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->subject = "Email Communication From Site";
			$object->replyto_address = "";
			$object->from_name = "Webmaster";
			$object->from_address = "info@".$_SERVER["HTTP_HOST"];
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("subject",TR_CONTACTMODULE_MESSAGETITLE,new textcontrol($object->subject));
		$form->register("from_name",TR_CONTACTMODULE_FROMNAME,new textcontrol($object->from_name));
		$form->register("from",TR_CONTACTMODULE_FROMADDRESS,new textcontrol($object->from_address));
		$form->register("replyto",TR_CONTACTMODULE_REPLYTO,new textcontrol($object->replyto_address));
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,"",TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->subject = $values['subject'];
		$object->from_name = $values['from_name'];
		$object->from_address = $values['from'];
		$object->replyto_address = $values['replyto'];
		return $object;
	}
}

?>
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

class addressbook_contact {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->firstname = "";
			$object->lastname = "";
			$object->address1 = "";
			$object->address2 = "";
			$object->city = "";
			$object->state = "";
			$object->zip = "";
			$object->country = "";
			$object->email = "";
			$object->phone = "";
			$object->cell = "";
			$object->fax = "";
			$object->pager = "";
			$object->notes = "";
			$object->webpage = "";
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("firstname","First Name",new textcontrol($object->firstname));
		$form->register("lastname","Last Name",new textcontrol($object->lastname));
		
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1' />"));
		
		$form->register("address1","Address",new textcontrol($object->address1,30));
		$form->register("address2","",new textcontrol($object->address2,30));
		$form->register("city","City",new textcontrol($object->city));
		$form->register("state","State",new textcontrol($object->state));
		$form->register("zip","Zip Code",new textcontrol($object->zip));
		
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1' />"));
		
		$form->register("email","Email",new textcontrol($object->email));
		$form->register("webpage","Home Page",new textcontrol($object->webpage));
		
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1' />"));
		
		$form->register("phone","Phone",new textcontrol($object->phone));
		$form->register("cell","Mobile",new textcontrol($object->cell));
		$form->register("fax","Fax",new textcontrol($object->fax));
		$form->register("pager","Pager",new textcontrol($object->pager));
		
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1' />"));
		
		$form->register("notes","Notes",new texteditorcontrol($object->notes,12,50));
		
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1' />"));
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}

	function update($values,$object) {
		$object->firstname = $values['firstname'];
		$object->lastname = $values['lastname'];
		$object->address1 = $values['address1'];
		$object->address2 = $values['address2'];
		$object->city = $values['city'];
		$object->state = $values['state'];
		$object->zip = $values['zip'];
		$object->email = $values['email'];
		$object->webpage = $values['webpage'];
		$object->phone = $values['phone'];
		$object->cell = $values['cell'];
		$object->pager = $values['pager'];
		$object->fax = $values['fax'];
		$object->notes = $values['notes'];
		return $object;
	}
}

?>
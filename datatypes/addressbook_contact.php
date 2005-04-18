<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','addressbookmodule');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->firstname = '';
			$object->lastname = '';
			$object->address1 = '';
			$object->address2 = '';
			$object->city = '';
			$object->state = '';
			$object->zip = '';
			$object->country = '';
			$object->email = '';
			$object->phone = '';
			$object->cell = '';
			$object->fax = '';
			$object->pager = '';
			$object->notes = '';
			$object->webpage = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('firstname',TR_ADDRESSBOOKMODULE_FIRSTNAME,new textcontrol($object->firstname));
		$form->register('lastname',TR_ADDRESSBOOKMODULE_LASTNAME,new textcontrol($object->lastname));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('address1',TR_ADDRESSBOOKMODULE_ADDRESS,new textcontrol($object->address1,30));
		$form->register('address2',TR_ADDRESSBOOKMODULE_ADDRESS2,new textcontrol($object->address2,30));
		$form->register('city',TR_ADDRESSBOOKMODULE_CITY,new textcontrol($object->city));
		$form->register('state',TR_ADDRESSBOOKMODULE_STATE,new textcontrol($object->state));
		$form->register('zip',TR_ADDRESSBOOKMODULE_ZIPCODE,new textcontrol($object->zip));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('email',TR_ADDRESSBOOKMODULE_EMAIL,new textcontrol($object->email));
		$form->register('webpage',TR_ADDRESSBOOKMODULE_HOMEPAGE,new textcontrol($object->webpage));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('phone',TR_ADDRESSBOOKMODULE_PHONE,new textcontrol($object->phone));
		$form->register('cell',TR_ADDRESSBOOKMODULE_MOBILE,new textcontrol($object->cell));
		$form->register('fax',TR_ADDRESSBOOKMODULE_FAX,new textcontrol($object->fax));
		$form->register('pager',TR_ADDRESSBOOKMODULE_PAGER,new textcontrol($object->pager));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('notes',TR_ADDRESSBOOKMODULE_NOTES,new texteditorcontrol($object->notes,12,50));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
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
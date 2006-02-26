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
 
class addressbook_contact {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/addressbook_contact.php');
	
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
		
		$form->register('firstname',$i18n['firstname'],new textcontrol($object->firstname));
		$form->register('lastname',$i18n['lastname'],new textcontrol($object->lastname));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('address1',$i18n['address1'],new textcontrol($object->address1,30));
		$form->register('address2',$i18n['address2'],new textcontrol($object->address2,30));
		$form->register('city',$i18n['city'],new textcontrol($object->city));
		$form->register('state',$i18n['state'],new textcontrol($object->state));
		$form->register('zip',$i18n['zip'],new textcontrol($object->zip));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('email',$i18n['email'],new textcontrol($object->email));
		$form->register('webpage',$i18n['webpage'],new textcontrol($object->webpage));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('phone',$i18n['phone'],new textcontrol($object->phone));
		$form->register('cell',$i18n['cell'],new textcontrol($object->cell));
		$form->register('fax',$i18n['fax'],new textcontrol($object->fax));
		$form->register('pager',$i18n['pager'],new textcontrol($object->pager));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('notes',$i18n['notes'],new texteditorcontrol($object->notes,12,50));
		
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));
		
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
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
		if (!pathos_core_URLisValid($object->webpage)) {
			$object->webpage = 'http://'.$object->webpage;
		}
		$object->phone = $values['phone'];
		$object->cell = $values['cell'];
		$object->pager = $values['pager'];
		$object->fax = $values['fax'];
		$object->notes = $values['notes'];
		return $object;
	}
}

?>

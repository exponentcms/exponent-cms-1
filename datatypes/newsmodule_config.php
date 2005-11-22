<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

class newsmodule_config {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/newsmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->sortorder = 'ASC';
			$object->sortfield = 'posted';
			$object->item_limit = 10;
		}
		$opts  = array('ASC'=>$i18n['ascending'],'DESC'=>$i18n['descending']);
		$fields = array('posted'=>$i18n['posteddate'],'publish'=>$i18n['publishdate']);
		$form->register('item_limit',$i18n['item_limit'],new textcontrol($object->item_limit));
		$form->register('sortorder',$i18n['sortorder'], new dropdowncontrol($object->sortorder,$opts));
		$form->register('sortfield',$i18n['sortfield'], new dropdowncontrol($object->sortfield,$fields));
		$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->sortorder = $values['sortorder'];
		$object->sortfield = $values['sortfield'];
		if ($values['item_limit'] > 0) {
			$object->item_limit = $values['item_limit'];
		} else {
			$object->item_limit = 10;
		}
		
		return $object;
	}
}

?>
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

class newsmodule_config {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','newsmodule');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->sortorder = 'ASC';
			$object->sortfield = 'posted';
			$object->item_limit = 10;
		}
		$opts  = array('ASC'=>TR_CORE_ASCENDING,'DESC'=>TR_CORE_DESCENDING);
		$fields = array('posted'=>TR_NEWSMODULE_POSTEDDATE,'publish'=>TR_NEWSMODULE_PUBDATE);
		$form->register('item_limit',TR_NEWSMODULE_ITEMLIMIT,new textcontrol($object->item_limit));
		$form->register('sortorder',TR_NEWSMODULE_SORTORDER, new dropdowncontrol($object->sortorder,$opts));
		$form->register('sortfield',TR_NEWSMODULE_SORTFIELD, new dropdowncontrol($object->sortfield,$fields));
		
		$form->register('submit','', new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
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
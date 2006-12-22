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
# $Id: newsmodule_config.php,v 1.3 2005/02/19 00:39:00 filetreefrog Exp $
##################################################

class rssmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/rssmodule_config.php');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->url='';
			$object->item_limit = 10;
		}
		$form->register('item_limit',$i18n['item_limit'],new textcontrol($object->item_limit));
		$form->register('url','url RSS', new textcontrol($object->url));
		$form->register('age','refresh', new textcontrol($object->age));
		$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		exponent_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->url = $values['url'];
		$object->age = $values['age'];
		if ($values['item_limit'] > 0) $object->item_limit = $values['item_limit'];
		else $object->item_limit = 10;
		return $object;
	}
}

?>
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

class textitem {
	function form($textitem = null) {
		$i18n = pathos_lang_loadSet('datatypes/textitem.php');
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!$textitem) {
			$textitem->text = '';
			$form->meta('id',0);
		} else {
			$form->meta('id',$textitem->id);
		}
		$form->register('text',$i18n['caption_text'],new htmleditorcontrol($textitem->text));
		$form->register('submit','',new buttongroupcontrol($i18n['caption_save'],'',$i18n['caption_cancel']));
		
		pathos_forms_cleanup();
		
		return $form;
	}
	
	function update($values,$object = null) {
		$object->text = $values['text'];
		return $object;
	}
}

?>
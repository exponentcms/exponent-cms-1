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

class textitem {
	function form($textitem = null) {
		//$i18n = pathos_lang_loadSet('datatypes/textitem.php');
		
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

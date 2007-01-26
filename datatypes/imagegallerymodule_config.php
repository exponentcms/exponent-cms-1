<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: faqmodule_config.php,v 1.4 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class imagegallerymodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->multiple_galleries = 0;
			//$object->show_pic_desc = 0;
		} else {
			$form->meta('id',$object->id);
		}
	
		$form->register(null,'',new htmlcontrol('Select whether would like to show the images from all the galleries on one page or if you would prefer '));	
		$form->register(null,'',new htmlcontrol('each gallery to display on it\'s own page.<br /><br />'));	
		$form->register('multiple_galleries','Show each gallery on it\'s own page?',new checkboxcontrol($object->multiple_galleries,true));
		$form->register(null,'',new htmlcontrol('<span style="color: red">This feature is turned off due to bugs</span><br /><br />'));	
		//$form->register('show_pic_desc','Show picture descriptions',new checkboxcontrol($object->show_pic_desc,true));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->multiple_galleries = (isset($values['multiple_galleries']) ? 1 : 0);;
		//$object->show_pic_desc = (isset($values['show_pic_desc']) ? 1 : 0);;
		return $object;
	}
}

?>

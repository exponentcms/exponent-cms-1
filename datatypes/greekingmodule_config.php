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
# $Id: greekingmodule_config.php,v 1.2 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class greekingmodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->num_para = 3; // 1-2 ,3-4 ,5-6
			$object->num_sent = 3; // 3-5,6-8, 9-11
			$object->num_word = 2; // 2-7,8-13,14-19
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register('num_para','How Many Paragraphs', new dropdowncontrol($object->num_para,array(1=>'A few',3=>'Normal',5=>'A Lot')));
		$form->register('num_sent','Sentences per Paragraph', new dropdowncontrol($object->num_sent,array(3=>'A few',6=>'Normal',9=>'A Lot')));
		$form->register('num_word','Sentence Length', new dropdowncontrol($object->num_word,array(2=>'Short',8=>'Normal',14=>'Long')));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->num_para = $values['num_para'];
		$object->num_sent = $values['num_sent'];
		$object->num_word = $values['num_word'];
		return $object;
	}
}

?>
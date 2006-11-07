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
# $Id: pagemodule_config.php,v 1.4 2005/04/25 19:02:17 filetreefrog Exp $
##################################################

class pagemodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->handle_php = 1;
			$object->filepath = '';
			$object->file_id = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register(null,'',new htmlcontrol('How should PHP files be handled / displayed?'));
		$form->register(null,'Show Highlighted Source',new radiocontrol($object->handle_php == 0 ? 1 : 0,0,'handle_php',true));
		$form->register(null,'Execute Code',new radiocontrol($object->handle_php == 1 ? 1 : 0,1,'handle_php',true));
		
		if ($object->file_id != 0) {
			$form->register(null,'',new htmlcontrol('<i>Note: a page has been uploaded for display.  Changing the file path will remove this file from the server.'));
		}
		$form->register('filepath','Path to File',new textcontrol($object->filepath));
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->handle_php = $values['handle_php'];
		$object->filepath = $values['filepath'];
		if (!isset($object->file_id)) $object->file_id = 0;
		return $object;
	}
}

?>
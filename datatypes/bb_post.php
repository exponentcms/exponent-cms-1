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
# $Id: bb_post.php,v 1.4 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class bb_post {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->subject = '';
			$object->body = '';
			$object->is_sticky = '0';
			$object->is_announcement = '0';
		} else {
			$form->meta('id',$object->id);
		}
		
		if (isset($object->board_id)) $form->meta('bb',$object->board_id);
		if (isset($object->parent)) $form->meta('parent',$object->parent);
		$form->register('subject','Subject',new textcontrol($object->subject));
		$form->register('body','Body',new htmleditorcontrol($object->body,'','','','forum')); // Make a bbeditorcontrol ([tt] lang)
		$form->register(null,'', new htmlcontrol('<br><br>'));	
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
    if (!isset($values['subject']) || $values['subject'] == '') {
      $object->subject = 'Untitled Post';
    } else {
		  $object->subject = $values['subject'];
    }

		$object->body = $values['body'];

    if (isset($values['quote'])) {
      $object->quote = $values['quote'];
    }

		if (isset($values['post_type'])) {
			if ($values['post_type'] == "announcement") {
				$object->is_announcement = true;
			} elseif ($values['post_type'] == "sticky") {
				$object->is_sticky = true;
			}
		}

		return $object;
	}
}

?>

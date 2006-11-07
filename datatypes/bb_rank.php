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

class bb_rank {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->is_special = 0;
			$object->minimum_posts = 0;
			$object->file_id = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$yesno = array(1=>'Yes', 0=>'No');	
		$form->register('title','Rank Title: ',new textcontrol($object->title));
		$form->register("is_special",'Set as special rank: ', new radiogroupcontrol($object->is_special,$yesno,false,10,2));
		$form->register('minimum_posts','Minimum posts to achieve rank: ',new textcontrol($object->minimum_posts,4,false,5));
		$form->register('file','Upload image: <br>You may upload a small image to use with the rank. ',new uploadcontrol());
		$form->register(null,'', new htmlcontrol('<br><br>'));	
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->is_special = $values['is_special'];
		$object->minimum_posts = $values['minimum_posts'];

		return $object;
	}
}

?>

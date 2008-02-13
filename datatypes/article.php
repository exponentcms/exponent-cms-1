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
# $Id: article.php,v 1.3 2005/04/25 19:02:15 filetreefrog Exp $
##################################################

class article {
	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = "";
			$object->body = "";
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("title","Article Title",new textcontrol($object->title,80,false,200));
		$form->register("body","Article Body",new htmleditorcontrol($object->body));
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		exponent_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->body = $values['body'];
		return $object;
	}
}

?>
<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

class section_template {
	function form($object=null) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->active = 1;
			$object->public = 1;
			$object->subtheme = "";
			if (!isset($object->parent)) $object->parent = 0;
			// NOT IMPLEMENTED YET
			//$object->subtheme="";
		} else {
			$form->meta("id",$object->id);
		}
		$form->meta("parent",$object->parent);
		$form->register("name","Name",new textcontrol($object->name));
		
		if (!isset($object->id) && $object->parent != 0) { // Add the 'Add' drop down if not a top level
			global $db;
			$sections = $db->selectObjects("section_template","parent=".$object->parent);
			
			if (count($sections)) {
				if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
				usort($sections,"pathos_sorting_byRankAscending");
				
				$dd = array("At the Top");
				foreach ($sections as $s) $dd[] = "After '".$s->name."'";
				
				$form->register("rank","Position",new dropdowncontrol(count($dd)-1,$dd));
			} else $form->meta("rank",0);
		} else $form->meta("rank",0);
		
		if (is_readable(THEME_ABSOLUTE."subthemes")) { // grab sub themes
			$form->register("subtheme","Sub Theme",new dropdowncontrol($object->subtheme,pathos_theme_getSubThemes()));
		}
		
		if (!isset($object->id) && $object->parent != 0) {
			$form->register("active","Active?",new checkboxcontrol($object->active));
			$form->register("public","Public?",new checkboxcontrol($object->public));
		}
		$form->register("submit","",new buttongroupcontrol("Save"));
		return $form;
	}
	
	function update($values,$object=null) {
		$object->parent = $values['parent'];
		$object->name = $values['name'];
		$object->active = isset($values['active'])?1:0;
		$object->public = isset($values['public'])?1:0;
		if (isset($values['subtheme'])) $object->subtheme = $values['subtheme'];
		if (isset($values['rank'])) $object->rank = $values['rank'];
		return $object;
	}
}

?>
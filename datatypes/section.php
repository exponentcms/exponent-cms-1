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

class section {
	function form($object=null) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->active = 1;
			$object->public = 1;
			$object->subtheme = "";
			
			$object->page_title = SITE_TITLE;
			$object->keywords = SITE_KEYWORDS;
			$object->description = SITE_DESCRIPTION;
			
			if (!isset($object->parent)) $object->parent = 0;
			// NOT IMPLEMENTED YET
			//$object->subtheme="";
		} else {
			$form->meta("id",$object->id);
		}
		$form->meta("parent",$object->parent);
		$form->register("name","Name",new textcontrol($object->name));
		
		if (!isset($object->id)) { // Add the 'Add' drop down
			global $db;
			$sections = $db->selectObjects("section","parent=".$object->parent);
			
			if (count($sections)) {
				if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
				usort($sections,"pathos_sorting_byRankAscending");
				
				$dd = array("At the Top");
				foreach ($sections as $s) $dd[] = "After '".$s->name."'";
				#$dd[] = "At the Bottom";
				$form->register("rank","Position",new dropdowncontrol(count($dd)-1,$dd));
			} else $form->meta("rank",0);
		}
		
			$subs = array();
			
		if (!isset($object->id)) {
			if (is_readable(THEME_ABSOLUTE."subthemes")) { // grab sub themes
				$dh = opendir(THEME_ABSOLUTE."subthemes");
				while (($s = readdir($dh)) !== false) {
					if (substr($s,-4,4) == ".php" && is_file(THEME_ABSOLUTE."subthemes/$s") && is_readable(THEME_ABSOLUTE."subthemes/$s")) {
						$subs["blank_".substr($s,0,-4)] = "Blank " . substr($s,0,-4);
					}
				}
			}
			$subs["blank_"] = "Blank Default Page";
			
			foreach ($db->selectObjects("section_template","parent='0'") as $template) {
				$subs["template_".$template->id] = "Pageset : " . $template->name;
			}
			
			uksort($subs,"strnatcmp");
			$form->register("pagetype","Page Type",new dropdowncontrol($object->subtheme,$subs));
		} else if (is_readable(THEME_ABSOLUTE."subthemes")) {
			$form->register("subtheme","Sub Theme",new dropdowncontrol($object->subtheme,pathos_theme_getSubThemes()));
		}
		
		$form->register("active","Active?",new checkboxcontrol($object->active));
		$form->register("public","Public?",new checkboxcontrol($object->public));
		
		$form->register("page_title","Page Title",new textcontrol($object->page_title));
		$form->register("keywords","Keywords",new texteditorcontrol($object->keywords));
		$form->register("description","Page Description",new texteditorcontrol($object->keywords));
		
		// subtheme dd control
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		return $form;
	}
	
	function update($values,$object=null) {
		$object->parent = $values['parent'];
		$object->name = $values['name'];
		$object->active = isset($values['active'])?1:0;
		$object->public = isset($values['public'])?1:0;
		
		$object->page_title = ($values['page_title'] != SITE_TITLE ? $values['page_title'] : "");
		$object->keywords = ($values['keywords'] != SITE_KEYWORDS ? $values['keywords'] : "");
		$object->description = ($values['description'] != SITE_DESCRIPTION ? $values['description'] : "");
		
		if (isset($values['subtheme'])) $object->subtheme = $values['subtheme'];
		else {
			$data = explode("_",$values['pagetype'],2);
			if ($data[0] == "blank") $object->subtheme = $data[1];
		}
		if (isset($values['rank'])) $object->rank = $values['rank'];
		return $object;
	}
}

?>
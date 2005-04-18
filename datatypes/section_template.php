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
# $Id$
##################################################

class section_template {
	function form($object=null) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','navigationmodule');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->active = 1;
			$object->public = 1;
			$object->subtheme = '';
			$object->page_title = SITE_TITLE;
			$object->keywords = SITE_KEYWORDS;
			$object->description = SITE_DESCRIPTION;
			
			if (!isset($object->parent)) $object->parent = 0;
			// NOT IMPLEMENTED YET
			//$object->subtheme='';
		} else {
			$form->meta('id',$object->id);
		}
		$form->meta('parent',$object->parent);
		$form->register('name',TR_NAVIGATIONMODULE_NAME,new textcontrol($object->name));
		
		if (!isset($object->id) && $object->parent != 0) { // Add the 'Add' drop down if not a top level
			global $db;
			$sections = $db->selectObjects('section_template','parent='.$object->parent);
			
			if (count($sections)) {
				if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
				usort($sections,'pathos_sorting_byRankAscending');
				
				$dd = array(TR_NAVIGATIONMODULE_ATTOP);
				foreach ($sections as $s) $dd[] = sprintf(TR_NAVIGATIONMODULE_POSAFTER,$s->name);
				
				$form->register('rank',TR_NAVIGATIONMODULE_POSITION,new dropdowncontrol(count($dd)-1,$dd));
			} else $form->meta('rank',0);
		} else $form->meta('rank',0);
		
		if (is_readable(THEME_ABSOLUTE.'subthemes')) { // grab sub themes
			$form->register('subtheme',TR_NAVIGATIONMODULE_SUBTHEME,new dropdowncontrol($object->subtheme,pathos_theme_getSubThemes()));
		}
		
		#if (!isset($object->id) && $object->parent != 0) {
			$form->register('active',TR_NAVIGATIONMODULE_ISACTIVE,new checkboxcontrol($object->active));
			$form->register('public',TR_NAVIGATIONMODULE_ISPUBLIC,new checkboxcontrol($object->public));
			// Register the Page Meta Data controls.
			$form->register('page_title',TR_NAVIGATIONMODULE_PAGETITLE,new textcontrol($object->page_title));
			$form->register('keywords',TR_NAVIGATIONMODULE_KEYWORDS,new texteditorcontrol($object->keywords,5,25));
			$form->register('description',TR_NAVIGATIONMODULE_PAGEDESC,new texteditorcontrol($object->keywords,5,25));
			
		#}
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		return $form;
	}
	
	function update($values,$object=null) {
		$object->parent = $values['parent'];
		$object->name = $values['name'];
		$object->page_title = ($values['page_title'] != SITE_TITLE ? $values['page_title'] : "");
		$object->keywords = ($values['keywords'] != SITE_KEYWORDS ? $values['keywords'] : "");
		$object->description = ($values['description'] != SITE_DESCRIPTION ? $values['description'] : "");
		$object->active = (isset($values['active']) ? 1 : 0);
		$object->public = (isset($values['public']) ? 1 : 0);
		if (isset($values['subtheme'])) $object->subtheme = $values['subtheme'];
		if (isset($values['rank'])) $object->rank = $values['rank'];
		return $object;
	}
}

?>
<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

class searchmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/searchmodule_config.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->is_categorized = 0;
			$modules = null;
		} else {
			$form->meta('id',$object->id);
			$modules = unserialize($object->modules);
		}
		
		$form->register('is_categorized',$i18n['is_categorized'],new checkboxcontrol($object->is_categorized,true));
		
		$form->register(null,'',new htmlcontrol('<br><br><div class="moduletitle">Searchable Modules</div><hr size="1" />'));	
		$form->register(null,'',new htmlcontrol('Select which parts of your site will be searchable by users using this search module.'));	
		$form->register(null,'',new htmlcontrol('<hr size="1" />'));	
		//eDebug(exponent_modules_list()); exit();
		
		$mod_list = getModuleNames(null);
		$selected_mods = getModuleNames($modules);
		
		$td_count = 0;
		$html = '<table cellpadding="0" cellspacing="0" border="0" class="search_modulelist"><tr>';
		foreach ($mod_list as $key => $mod_name) {
			if ($td_count == 3) {
				$html .= '</tr><tr><td class="search_modulelist">';
				$td_count = 1;
			} else {
				$html .= '<td class="search_modulelist">';
				$td_count += 1;
			}
		
			if (array_key_exists($key, $selected_mods)) { $selected="checked"; } else { $selected=''; }		
			$html .= '<input type="checkbox" name="modules[]" value="'.$key.'"'.$selected.'> &nbsp;&nbsp;'.$mod_name.'</td>';
			
		}
		
		if ($td_count != 3) {
			for ($i=$td_count; $i < 3; $i++) {
				$html .= '<td>&nbsp;</td>';
			}
		}

		$html .= '</tr></table>';
		$form->register(null,'',new htmlcontrol($html));	

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->is_categorized = (isset($values['is_categorized']) ? 1 : 0);
		$object->modules = serialize($values['modules']);
		return $object;
	}

}

?>

<?php

#############################################################
# LINK MODULE
#############################################################
# Written by Eric Lestrade 
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

if (!defined('EXPONENT')) exit('');
if(exponent_permissions_check('import',$loc)) {
	global $db;
	$mode=$_GET['mode'];
	$authorized_modes=array('export','import');
	if(in_array($mode,$authorized_modes)) {
		$i18n = exponent_lang_loadFile('modules/linkmodule/actions/export_import_linklist.php');
		$location = serialize($loc);
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		$form = new form();
		if($mode=='import') 
			$form->meta('action','save_import_from_linklist');
		else
			$form->meta('action','save_export_to_linklist');
		$form->location($loc);
		
		$template = new template('linkmodule','_form_export_import_linklist');
		
		// ***********
		// Get available Link List modules 
		$linklist_title=array();
		$modules=$db->selectObjects('container');
		foreach($modules as $module) {
			$module_loc=unserialize($module->internal);
			if($module_loc->mod=='linklistmodule') {
				if($mode=='import'
					|| ($mode=='export'
						&& exponent_permissions_check('create',$module_loc)
						&& exponent_permissions_check('administrate',$module_loc)))
				{
					$links_in_destination=$db->selectObjects('linklist_link',"location_data='".$module->internal."'");
					if($module->title) $title=$module->title;
					else $title= "(".$i18n['no_title'].")";
					$linklist_title[$module->id]=$title." (".count($links_in_destination)." ".$i18n['resources_unit'].")";
				}
			}
		}
		if($mode=='import') {
			$allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			usort($allcats, "exponent_sorting_byRankAscending");
			$catarray = array();
			$catarray[0]="&lt;".$i18n['top_level']."&gt;";
			foreach ($allcats as $cat) {
			   $catarray[$cat->id] = $cat->name;
			}			
			$form->register('categories', $i18n['select_category'], new dropdowncontrol(0, $catarray));
		}
		if(count($linklist_title)) {
			$form->register('linklistModule',$i18n['source'],new dropdowncontrol("",$linklist_title));
			if($mode=='import') {
				$form->register('import_links',$i18n['import_links'],new checkboxcontrol(true));
				$form->register('import_permissions',$i18n['import_permissions'],new checkboxcontrol(false));
				$form->register('import','',new buttongroupcontrol($i18n['import'],'',$i18n['cancel']));
			}
			else {
				$form->register('export_links',$i18n['export_links'],new checkboxcontrol(true));
				$form->register('export_permissions',$i18n['export_permissions'],new checkboxcontrol(false));
				$form->register('export','',new buttongroupcontrol($i18n['export'],'',$i18n['cancel']));
			}
		}
		else { 
			$form->register('','',new htmlcontrol($i18n['no_module']));
			$form->register('submit','',new buttongroupcontrol('','',$i18n['cancel']));
		}
		$template->assign('form_html',$form->toHTML());
		$template->assign('mode',$mode);
		$template->output();
	}
	else {	echo SITE_404_HTML;}
}
else {	echo SITE_403_HTML;}
?>

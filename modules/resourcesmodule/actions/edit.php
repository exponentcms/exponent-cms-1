<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');

$resource = null;
$iloc = null;
$i18n = exponent_lang_loadFile('modules/resourcesmodule/actions/edit.php');

if (isset($_GET['id'])) {
	$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
	if ($resource) {
		$loc = unserialize($resource->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	}
}

if (($resource == null && exponent_permissions_check('post',$loc)) ||
	($resource != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
) {
	$config = $db->selectObject('resourcesmodule_config',"location_data='".serialize($loc)."'");
	if ($config == null) {
		//do nothing here yes.  
	}
	$form = resourceitem::form($resource);
	$form->location($loc);
	$form->meta('action','save');
	
	$template = new template('resourcesmodule','_form_edit',$loc);
	
	if ($config->enable_categories) {
		$allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		usort($allcats, "exponent_sorting_byRankAscending");
		$catarray = array();
		$catarray[0] = $i18n['no_category'];
		foreach ($allcats as $cat) {
			$catarray[$cat->id] = $cat->name;
		}			
		$form->registerBefore('name', 'categories', 'Select Category', new dropdowncontrol($resource->category_id, $catarray));
	}	
	
	// if (!isset($resource->id)) {
		// $ranks = array();
		// foreach ($db->selectObjects('resourceitem',"location_data='".serialize($loc)."'") as $item) {
			// $ranks[$item->rank+1] = 'After "'.$item->name.'"';
		// }
		// $ranks[0] = 'At The Top';
		// ksort($ranks);
		// $form->registerBefore('submit','rank','Position',new dropdowncontrol(count($ranks)-1,$ranks));
	// }
	
	if (!isset($resource->file_id)) {
		$form->registerBefore('submit','file',"Upload a New ".$i18n['file'],new uploadcontrol());
		$form->registerBefore('submit', 'fileexists', '(or) Select an Existing File', new customcontrol("<input class=\"kfm\" id=\"fileexists\" type=\"text\" name=\"fileexists\" size=\"80\" maxlength=\"200\">"));	
		
		$dir = 'files/resourcesmodule/'.$loc->src;
		if (!is_really_writable(BASE.$dir)) {
			$template->assign('dir_not_readable',1);
			$form->controls['submit']->disabled = true;
		} else {
			$template->assign('dir_not_readable',0);
		}
	}
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>

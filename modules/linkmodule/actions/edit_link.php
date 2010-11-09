<?php

#############################################################
# LINKMODULE
#############################################################
# Copyright (c) 2006 Eric Lestrade 
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

if (!defined("EXPONENT")) exit("");
	$link = null;
	$link_is_new=true;
	if (isset($_GET['id'])) {
		$link = $db->selectObject("link","id=".$_GET['id']);
		if ($link != null) {
			$loc = unserialize($link->location_data);
			$link_is_new=false;
		} else {
			echo SITE_404_HTML;
		}
	}
	
	if (!$link) {
		$link->category_id = 0;
	}
	
	if (($link_is_new && exponent_permissions_check("add",$loc))
		|| (!$link_is_new && exponent_permissions_check("edit",$loc))) 
	{
		$config = $db->selectObject('linkmodule_config',"location_data='".serialize($loc)."'");

		$form = link::form($link);
		$form->location($loc);
		$form->meta("action","save_link");
				
		$template = new template("linkmodule","_form_edit",$loc);

		if ($config->enable_categories == true){
			$allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			usort($allcats, "exponent_sorting_byRankAscending");
			$catarray = array();
			$catarray[0]="&lt;".exponent_lang_loadKey('modules/linkmodule/actions/edit_link.php','top_level')."&gt;";
			foreach ($allcats as $cat) {
			   $catarray[$cat->id] = $cat->name;
			}			
			$form->registerBefore('name', 'categories', exponent_lang_loadKey('modules/linkmodule/actions/edit_link.php','select_category'), new dropdowncontrol($link->category_id, $catarray));
		}
		$template->assign("form_html",$form->toHTML());
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
	
?>

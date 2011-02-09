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

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('configure',$loc)) {
	if (exponent_template_getModuleViewFile($loc->mod,'_configure',false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template('common','_configure',$loc);
	} else {
		$template = new template('common','_configure',$loc);
		//$template = new template($loc->mod,'_configure',$loc);
	}
	
	$hasConfig = 0;
	
	$submit = null;
	$form = null;
	
	if ($db->tableExists($_GET['module'].'_config') && class_exists($_GET['module'].'_config')) {
		$config = $db->selectObject($_GET['module'].'_config',"location_data='".serialize($loc)."'");	
		if (empty($config->location_data)) $config->location_data = serialize($loc);
		$form = call_user_func(array($_GET['module'].'_config','form'),$config);
			
		if (isset($form->controls['submit'])) {
			$submit = $form->controls['submit'];
			$form->unregister('submit');
		}
		$hasConfig = 1; //We have a configuration stored in its own table
	}

	$container = $db->selectObject('container',"internal='".serialize($loc)."'");
// replace here down to	
	// Get all containers for module
	$containers = $db->selectObjects("container","internal='".serialize($loc)."'");
	if (count($containers) != 1) {
		// Get current section (page) for locating specific module on that page
		global $sectionObj;
		$sectionid = $sectionObj->id;
		$current = null;
		foreach ($containers as $container1) {
			$containerext = unserialize($container1->external);
			$sectionref = $db->selectObject("sectionref","source = '".$containerext->src."' AND section='".$sectionid."'");
			if ($sectionref) {
				$current = $container1;
				break;
			}
		}		
		if (empty($current)) {	
	// well, we didnt' find it yet, must be in a nested or static container
	// so try a kludge to look for modules in static containers
	// doesn't NOT work with nestedcontainers!
			foreach ($containers as $container1) {
				$containerext = unserialize($container1->external);
				if ((substr($containerext->src,0,7) != "@random") && (intval(substr($containerext->src,-1)) == 0)){
					$sectionref = $db->selectObject("sectionref","source = '".$containerext->src."'");
					if ($sectionref) {
						$current = $container1;
						break;
					}
				}
			}
		}
		if (empty($current)) {	
	// well, we didnt' find it at all with our kludge, so pick the first container 
			$container = $db->selectObject("container","internal='".serialize($loc)."'");
		}
		$container = $current; 	
	}
// here

	if ($container) {
		$values = ($container->view_data != '' ? unserialize($container->view_data) : array());
		$form = exponent_template_getViewConfigForm($loc->mod,$container->view,$form,$values);
		
		if (isset($form->controls['submit'])) { // Still have a submit button.
			$submit = $form->controls['submit'];
			$form->unregister('submit');
		}
		$hasConfig = 1; //We have a per-view, per-container configuration stored in the container data
	}
	//PLEASE EVALUATE: since exponent_template_getViewConfigForm is called only here, is it necessary to make it add
	//the submit button to the config form just to unregister and re-register it down here?

	if ($hasConfig) {
		$form->location($loc);
		$form->meta('action','saveconfig');
		$form->meta('_common','1');
	}
	
	if ($submit !== null) {
		$form->register('submit','',$submit);
	}
	
	if ($hasConfig) {
		$template->assign('form_html',$form->toHTML());
	}
	$template->assign('hasConfig',$hasConfig);
	$template->assign('name',call_user_func(array($_GET['module'],"name")));
	$template->assign('title',$container->title);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>

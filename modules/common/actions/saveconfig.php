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
	$config = $db->selectObject($_POST['module'].'_config',"location_data='".serialize($loc)."'");
	$config = call_user_func(array($_POST['module'].'_config','update'),$_POST,$config);
	$config->location_data = serialize($loc);
	
	if (isset($config->id)) {
		$db->updateObject($config,$_POST['module'].'_config');
	} else {
		$db->insertObject($config,$_POST['module'].'_config');
	}
	
	$container = $db->selectObject('container',"internal='".serialize($loc)."' AND id='".$_POST['id']."'");
// replace with here down to
	// Get all containers for module
	$containers = $db->selectObjects("container","internal='".serialize($loc)."' AND id='".$_POST['id']."'");
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
	
	$vconfig = array();
	if (isset($_POST['_viewconfig'])) {
		$opts = exponent_template_getViewConfigOptions($loc->mod,$container->view);
		foreach (array_keys($opts) as $o) {
			$vconfig[$o] = (isset($_POST['_viewconfig'][$o]) ? $_POST['_viewconfig'][$o] : 0);
		}
	}
	$container->view_data = serialize($vconfig);
	$db->updateObject($container,'container');
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
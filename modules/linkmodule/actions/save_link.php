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

	$link = null;$link_is_new=true;		
	if (isset($_POST['id'])) {
		$link = $db->selectObject('link', 'id='.$_POST['id']);
		if ($link != null) {
			$loc = unserialize($link->location_data);
			$link_is_new=false;
		} 
	} else {
		$link->rank = $db->max('link', 'rank', 'location_data', "location_data='".serialize($loc)."'");
		if ($link->rank == null) {
			$link->rank = 0;
		} else {
			$link->rank += 1;
		}
	} 
	
	if (($link_is_new && exponent_permissions_check("add",$loc))
		|| (!$link_is_new && exponent_permissions_check("edit",$loc)))
	{
		$link = link::update($_POST, $link);
		$link->location_data = serialize($loc);
		if (isset($_POST['categories'])) {
			$link->category_id = $_POST['categories'];
		}
		if (isset($link->id)) {
			$link->id = $db->updateObject($link,"link");
		} else {
			$link->id = $db->insertObject($link,"link");
		}
		
		linkmodule::spiderContent($link);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	
?>

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
	if (isset($_POST['categories'])) {
		$cat = $_POST['categories'];
	} else {
		$cat = 0;
	}
	if (isset($_POST['id'])) {
		$link = $db->selectObject('link', 'id='.$_POST['id']);
		if ($link != null) {
			$loc = unserialize($link->location_data);
			$link_is_new=false;
		} 
	} else {
		$link->rank = $db->max('link', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$cat);
		if ($link->rank == null) {
			$link->rank = 0;
		} else {
			$link->rank += 1;
		}
	} 
	
	if (($link_is_new && exponent_permissions_check("add",$loc))
		|| (!$link_is_new && exponent_permissions_check("edit",$loc)))
	{
		$oldcatid = $link->category_id;
		$link = link::update($_POST, $link);
		$link->location_data = serialize($loc);
		$link->category_id = $cat;
		if (($oldcatid != $link->category_id) && isset($link->id)) {
			$db->decrement('faq', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$link->rank." AND category_id=".$oldcatid);
			$link->rank = $db->max('faq', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$link->category_id);
			if ($link->rank == null) {
				$link->rank = 0;
			} else { 
				$link->rank += 1;
			}
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

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
	if (isset($_GET['id'])) {
		$link = $db->selectObject('link', 'id='.$_GET['id']);
		if ($link != null) {
			$loc = unserialize($link->location_data);
		}
	}
	
	if ($link) {
		$loc = unserialize($link->location_data);
		if (exponent_permissions_check("edit",$loc)) {
			$db->delete('link', 'id='.$_GET['id']);
			$db->decrement('link', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$link->rank." AND category_id=".$link->category_id);
			exponent_flow_redirect();
		} else {
			echo SITE_403_HTML;
		}
	} else {
		echo SITE_404_HTML;
	}

?>

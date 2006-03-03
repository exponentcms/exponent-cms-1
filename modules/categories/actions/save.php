<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined("EXPONENT")) exit("");

$cat = null;
if (isset($_POST['id'])) $cat = $db->selectObject("category","id=".intval($_POST['id']));
if ($cat) {
	$loc = unserialize($cat->location_data);
} else {
	$loc->mod = $_POST['orig_module']; // Only need to update the module.
	$cat->rank = $db->max('category', 'rank', 'location_data', "location_data='".serialize($loc)."'");
	if ($cat->rank === null) {
		$cat->rank = 0;
	} else { 
		$cat->rank ++;
	}
}
if (exponent_permissions_check('manage_categories',$loc)) {
	$cat = category::update($_POST,$cat);
	$cat->location_data = serialize($loc);
	if (isset($cat->id)) {
		$db->updateObject($cat,"category");
	} else {
		$db->insertObject($cat,"category");
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
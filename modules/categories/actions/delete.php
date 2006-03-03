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

if (!defined('EXPONENT')) exit('');

$cat = null;
if (isset($_GET['id'])) {
	$cat = $db->selectObject('category','id='.intval($_GET['id']));
}

if ($cat) {
	$loc = unserialize($cat->location_data);
	$loc->mod = $_GET['orig_module'];
	if (exponent_permissions_check('manage_categories',$loc)) {
		$db->delete("category","id=".$cat->id);
		$db->decrement('category', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$cat->rank);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
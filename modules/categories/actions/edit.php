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

$cat = null;
if (isset($_GET['id'])) {
	$cat = $db->selectObject('category','id='.intval($_GET['id']));
}

if ($cat) {
	$loc = unserialize($cat->location_data);
} else {
	$loc->mod = $_GET['orig_module']; // Update module in location
}
if (exponent_permissions_check('manage_categories',$loc)) {
	$form = category::form($cat);
	$form->location($loc);
	$form->meta('module','categories');
	$form->meta('orig_module', $_GET['orig_module']);
	$form->meta('action','save');
	echo $form->toHTML();
} else {
	echo SITE_403_HTML;
}

?>

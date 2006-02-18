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

if (!defined('PATHOS')) exit('');

$item = null;
if (isset($_POST['id'])) {
	$item = $db->selectObject('rotator_item','id='.intval($_POST['id']));
}

if ($item) {
	$loc = unserialize($item->location_data);
}

if (pathos_permissions_check('manage',$loc)) {
	$item = rotator_item::update($_POST,$item);
	$item->location_data = serialize($loc);
	
	if (isset($item->id)) {
		$db->updateObject($item,'rotator_item');
	} else {
		$db->insertObject($item,'rotator_item');
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
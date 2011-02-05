<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

if (isset($_POST['id'])) {
	$listingconfig = $db->selectObject('listingmodule_config','id='.intval($_POST['id']));
	if ($listingconfig) {
		$loc = unserialize($listingconfig->location_data);
	}
}

if (exponent_permissions_check('edit',$loc)) {
	$listingconfig = listingmodule_config::update($_POST,$listingconfig);
	$listingconfig->location_data = serialize($loc);
	if (isset($listingconfig->id)) {
		$db->updateObject($listingconfig,'listingmodule_config');
	} else {
		$db->insertObject($listingconfig,'listingmodule_config');
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>

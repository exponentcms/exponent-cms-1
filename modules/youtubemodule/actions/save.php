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

if (isset($_POST['id'])) {
	$youtube = $db->selectObject('youtube','id='.intval($_POST['id']));
	if ($youtube) {
		$loc = unserialize($youtube->location_data);
	}
}

if (exponent_permissions_check('edit',$loc)) {
	$youtube = youtube::update($_POST,$youtube);
	$youtube->location_data = serialize($loc);
	if(!empty($youtube->id)) {
		$db->updateObject($youtube,'youtube');
	} else {
		$db->insertObject($youtube,'youtube');
	}

	exponent_flow_redirect();	
} else {
	echo SITE_403_HTML;
}

?>

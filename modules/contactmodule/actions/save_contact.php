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

$contact = null;
if (isset($_POST['id'])) {
	$contact = $db->selectObject('contact_contact','id='.intval($_POST['id']));
	if ($contact) $loc = unserialize($contact->location_data);
}

if (exponent_permissions_check('configure',$loc)) {
	$contact = contact_contact::update($_POST,$contact);
	$contact->location_data = serialize($loc);
	
	if (isset($contact->id)) {
		$db->updateObject($contact,'contact_contact');
	} else {
		$db->insertObject($contact,'contact_contact');
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>

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
if (isset($_GET['id'])) {
	// Sanitize required _GET variable, to protect against injection attacks
	$contact = $db->selectObject('addressbook_contact','id='.intval($_GET['id']));
}
if ($contact) {
	$loc = unserialize($contact->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$contact->id);
	
	if (exponent_permissions_check('delete',$loc) || exponent_permissions_check('delete',$iloc)) {
		$db->delete('addressbook_contact','id='.$contact->id);
		exponent_flow_redirect(SYS_FLOW_SECTIONAL);
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
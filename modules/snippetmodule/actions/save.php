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
	$textitem = $db->selectObject('textitem','id='.intval($_POST['id']));
	if ($textitem) {
		$loc = unserialize($textitem->location_data);
	}
}

if (exponent_permissions_check('edit',$loc)) {
	$textitem = textitem::update($_POST,$textitem);
	$textitem->location_data = serialize($loc);
	if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	exponent_workflow_post($textitem,'textitem',$loc);
} else {
	echo SITE_403_HTML;
}

?>
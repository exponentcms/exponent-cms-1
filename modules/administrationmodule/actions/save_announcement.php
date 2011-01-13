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

if (exponent_users_isAdmin()) {
	$announcement = $db->selectObject('announcement');
	$announcement = announcement::update($_POST,$announcement);
	if (isset($_POST[id]) && $_POST[id] != 0) {
		$db->updateObject($announcement,'announcement','id='.$_POST[id]);
	} else {
		$db->insertObject($announcement,'announcement');
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>

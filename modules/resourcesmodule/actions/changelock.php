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

$item = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($item ) {
	if ($user) {
		if ($item->flock_owner == 0) {
			$item->flock_owner = $user->id;
		} else if ($item->flock_owner == $user->id || $user->is_acting_admin == 1) {
			$item->flock_owner = 0;
		}
		$db->updateObject($item,'resourceitem');
		unset($_SESSION['resource_cache']);
		exponent_flow_redirect();
	}
} else {
    header("HTTP/1.1 404 Not Found");
    echo SITE_404_HTML;
}

?>
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

$banner = null;
if (isset($_GET['id'])) {
	$banner = $db->selectObject('banner_ad','id='.intval($_GET['id']));
}

if ($banner) {
	$loc = unserialize($banner->location_data);
	if (pathos_permissions_check('manage',$loc)) {
		$db->delete('banner_ad','id='.$banner->id);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
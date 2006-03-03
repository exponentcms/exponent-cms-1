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

if (exponent_permissions_check('manage_af',$loc)) {
	// Sanitize required _GET parameters to prevent injection attacks
	$_GET['id'] = intval($_GET['id']);
	
	$af = $db->selectObject('banner_affiliate','id='.$_GET['id']);
	if ($af) {
		$db->delete('banner_affiliate','id='.$_GET['id']);
		exponent_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>
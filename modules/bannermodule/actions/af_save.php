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

if (exponent_permissions_check('manage_af',$loc)) {
	$af = null;
	if (isset($_POST['id'])) {
		$af = $db->selectObject('banner_affiliate','id='.intval($_POST['id']));
	}
	
	$af = banner_affiliate::update($_POST,$af);
	
	if (isset($af->id)) {
		$db->updateObject($af,'banner_affiliate');
	} else {
		$db->insertObject($af,'banner_affiliate');
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
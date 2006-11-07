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

// Part of the User Management category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('administrate',exponent_core_makeLocation('wizardmodule'))) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$wizards = null;
	$wizards = $db->selectObjects("wizard", null, "id");	
	//eDebug($wizards); exit();
	$template = new template('wizardmodule','_manage_wizards',$loc);
	$template->assign("wizards", $wizards);
	$template->output();
} else {
	echo SITE_403_HTML;
}


?>

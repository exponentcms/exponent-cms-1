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

if (exponent_permissions_check('manage',$loc)) {
	exponent_flow_set(SYS_FLOW_ACTION,SYS_FLOW_PROTECTED);

	$objects = $db->selectObjects('rotator_item',"location_data='".serialize($loc)."'");
	
	$template = new template('rotatormodule','_manage',$loc);
	$template->assign('items',$objects);
	$template->register_permissions(
		array('administrate','manage'),
		$loc);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
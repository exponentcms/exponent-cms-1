<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('manage',$loc)) {
	pathos_flow_set(SYS_FLOW_ACTION,SYS_FLOW_PROTECTED);

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
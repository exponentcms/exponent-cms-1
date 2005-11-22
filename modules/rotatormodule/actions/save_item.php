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

$item = null;
if (isset($_POST['id'])) {
	$item = $db->selectObject('rotator_item','id='.$_POST['id']);
}

if ($item) {
	$loc = unserialize($item->location_data);
}

if (pathos_permissions_check('manage',$loc)) {
	$item = rotator_item::update($_POST,$item);
	$item->location_data = serialize($loc);
	
	if (isset($item->id)) {
		$db->updateObject($item,'rotator_item');
	} else {
		$db->insertObject($item,'rotator_item');
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
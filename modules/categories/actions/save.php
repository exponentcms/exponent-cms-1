<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

if (!defined("PATHOS")) exit("");

$cat = null;
if (isset($_POST['id'])) $cat = $db->selectObject("category","id=".$_POST['id']);
if ($cat) {
	$loc = unserialize($cat->location_data);
} else {
	$loc->mod = $_POST['m']; // Only need to update the module.
}
// PERM CHECK
	$cat = category::update($_POST,$cat);
	$cat->location_data = serialize($loc);
	if (isset($cat->id)) {
		$db->updateObject($cat,"category");
	} else {
		$db->insertObject($cat,"category");
	}
	pathos_flow_redirect();
// END PERM CHECK

?>
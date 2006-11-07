<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: milestone_save.php,v 1.3 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$milestone = null;
if (isset($_POST['id'])) $milestone = $db->selectObject("codemap_milestone","id=".$_POST['id']);
if ($milestone) {
	$loc = unserialize($milestone->location_data);
}

if (exponent_permissions_check("manage_miles",$loc)) {
	$milestone = codemap_milestone::update($_POST,$milestone);
	$milestone->rank = $db->max("codemap_milestone","rank","location_data","location_data='".serialize($loc)."'")+1;
	$milestone->location_data = serialize($loc);
	if (isset($milestone->id)) {
		$db->updateObject($milestone,"codemap_milestone");
	} else {
		$milestone->rank = $db->max("codemap_milestone","rank","location_data","location_data='".serialize($loc)."'")+1;
		$db->insertObject($milestone,"codemap_milestone");
	}
	exponent_flow_redirect();
}

?>
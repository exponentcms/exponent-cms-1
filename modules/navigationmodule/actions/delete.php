<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

$section = null;
if (isset($_GET['id'])) {
	$section = $db->selectObject("section","id=".$_GET['id']);
}

if ($section) {
	if (pathos_permissions_check('manage',pathos_core_makeLocation('navigationmodule','',$section->id))) {
		navigationmodule::deleteLevel($section->id);
		$db->delete("section","id=" . $section->id);
		$db->decrement("section","rank",1,"rank > " . $section->rank . " AND parent=".$section->parent);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
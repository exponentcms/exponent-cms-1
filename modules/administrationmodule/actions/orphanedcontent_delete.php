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

// Part of the Database category

if (pathos_permissions_check('database',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin == 1) {
	$src = urldecode($_GET['delsrc']);
	
	$mod = new $_GET['mod']();
	if ($mod->hasContent()) { // may not need the check, but it doesn't hurt
		$mod->deleteIn(pathos_core_makeLocation($_GET['mod'],$_GET['delsrc']));
	}
	
	$db->delete('locationref',"module='" . $_GET['mod'] . "' AND source='$src' AND refcount=0");
	$db->delete('sectionref',"module='" . $_GET['mod'] . "' AND source='$src' AND refcount=0");
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
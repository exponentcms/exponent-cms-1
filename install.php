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

include_once("pathos.php");
define("SCRIPT_RELATIVE",PATH_RELATIVE);
define("SCRIPT_ABSOLUTE",BASE);
define("SCRIPT_FILENAME","install.php");

$oldu = $user;
$user->is_admin = 1; // hack the installer

$loc = pathos_core_makeLocation("administrationmodule");

include_once("modules/administrationmodule/actions/installtables.php");

// Initial setup to get things going.
if ($db->tableIsEmpty("user")) {
	echo "Creating default administrator account.<br />";
	$user = null;
	$user->username = "admin";
	$user->password = md5("admin");
	$user->is_admin = 1;
	$db->insertObject($user,"user");
}

if ($db->tableIsEmpty("modstate")) {
	echo "Activating Administration Module by default.<br />";
	$modstate = null;
	$modstate->module = "administrationmodule";
	$modstate->active = 1;
	$db->insertObject($modstate,"modstate");
}

if ($db->tableIsEmpty("section")) {
	echo "Creating default 'Home' section.<br />";
	$section = null;
	$section->name = "Home";
	$section->public = 1;
	$section->active = 1;
	$section->rank = 0;
	$section->parent = 0;
	$sid = $db->insertObject($section,"section");
}
//GREP:VIEWIFY
?>
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

$stuff = array(
	"User Management"=>array(
		"useraccounts"=>array(
			"title"=>"User Accounts",
			"module"=>"administrationmodule",
			"action"=>"useraccounts"),
		"usersessions"=>array(
			"title"=>"User Sessions",
			"module"=>"administrationmodule",
			"action"=>"usersessions"),
		"groupaccounts"=>array(
			"title"=>"Group Accounts",
			"module"=>"administrationmodule",
			"action"=>"groupaccounts"),
		"profiledefinitions"=>array(
			"title"=>"Profile Definitions",
			"module"=>"administrationmodule",
			"action"=>"profileext_manage")
	),
	"Extensions"=>array(
		"managemodules"=>array(
			"title"=>"Manage Modules",
			"module"=>"administrationmodule",
			"action"=>"managemodules"),
		"managethemes"=>array(
			"title"=>"Manage Themes",
			"module"=>"administrationmodule",
			"action"=>"managethemes"),
		"managesubsystems"=>array(
			"title"=>"Subsystems",
			"module"=>"administrationmodule",
			"action"=>"managesubsystems"),
		"upload_extension"=>array(
			"title"=>"Upload Extension",
			"module"=>"administrationmodule",
			"action"=>"upload_extension")
	),
	"Database"=>array(
		"orphanedcontent"=>array(
			"title"=>"Archived Modules",
			"module"=>"administrationmodule",
			"action"=>"orphanedcontent"),
		"installdatabase"=>array(
			"title"=>"Install Tables",
			"module"=>"administrationmodule",
			"action"=>"installtables"),
		"trimdatabase"=>array(
			"title"=>"Trim Database",
			"module"=>"administrationmodule",
			"action"=>"trimdatabase"),
		"optimizedatabase"=>array(
			"title"=>"Optimize Database",
			"module"=>"administrationmodule",
			"action"=>"optimizedatabase"),
		"import"=>array(
			"title"=>"Import Data",
			"module"=>"importer",
			"action"=>"list_importers"),
		"export"=>array(
			"title"=>"Export Data",
			"module"=>"exporter",
			"action"=>"list_exporters"),
	),
	"Configuration"=>array(
		"configuresite"=>array(
			"title"=>"Configure Site",
			"module"=>"administrationmodule",
			"action"=>"configuresite"),
		"sysinfo"=>array(
			"title"=>"System Info",
			"module"=>"administrationmodule",
			"action"=>"sysinfo")
	)
);

if (!$user->is_admin) {
	unset($stuff['Database']['import']);
}

return $stuff;

?>
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

if (pathos_permissions_check('database',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin) {
	$exporters = array();
	$idh = opendir(BASE."modules/exporter/exporters");
	while (($imp = readdir($idh)) !== false) {
		if (substr($imp,0,1) != "." && is_readable(BASE."modules/exporter/exporters/$imp/start.php") && is_readable(BASE."modules/exporter/exporters/$imp/info.php")) {
			$exporters[$imp] = include(BASE."modules/exporter/exporters/$imp/info.php");
		}
	}
	
	$template = new template("exporter","_exporters");
	$template->assign("exporters",$exporters);
	$template->output();
	
} else echo SITE_403_HTML;

?>
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

$t = pathos_core_listThemes();
uasort($t,"strnatcmp");

return array(
	"Display Settings",
	array(
		"DISPLAY_THEME_REAL"=>array(
			"title"=>"Theme",
			"description"=>"The current theme layout",
			"control"=>new dropdowncontrol(null,$t)
		),
		"DISPLAY_ATTRIBUTION"=>array(
			"title"=>"Attribution",
			"description"=>"How credit is given to authors for their posts.",
			"control"=>new dropdowncontrol(null,array("firstlast"=>"John Doe","lastfirst"=>"Doe, John","first"=>"John","username"=>"jdoe"))
		),
		"DISPLAY_CACHE"=>array(
			"title"=>"Cache Content?",
			"description"=>"Whether or not caching is enabled for the templating subsystem.",
			"control"=>new checkboxcontrol()
		),
		"DISPLAY_DATETIME_FORMAT"=>array(
			"title"=>"Date and Time Format",
			"description"=>"Default system-wide date format, displaying both date and time.",
			"control"=>new dropdowncontrol(null,pathos_config_dropdownData("datetime_format"))
		),
		"DISPLAY_DATE_FORMAT"=>array(
			"title"=>"Date Format",
			"description"=>"Default system-wide date format, displaying date only",
			"control"=>new dropdowncontrol(null,pathos_config_dropdownData("date_format"))
		),
		"DISPLAY_TIME_FORMAT"=>array(
			"title"=>"Time Format",
			"description"=>"Default system-wide date format, displaying time only",
			"control"=>new dropdowncontrol(null,pathos_config_dropdownData("time_format"))
		)
	)
);

?>

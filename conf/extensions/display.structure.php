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

if (!defined('PATHOS')) exit('');

pathos_lang_loadDictionary('config','display');

$t = pathos_core_listThemes();
uasort($t,'strnatcmp');

return array(
	TR_CONFIG_DISPLAY_TITLE,
	array(
		'DISPLAY_THEME_REAL'=>array(
			'title'=>TR_CONFIG_DISPLAY_THEME_REAL,
			'description'=>TR_CONFIG_DISPLAY_THEME_REAL_DESC,
			'control'=>new dropdowncontrol(null,$t)
		),
		'DISPLAY_ATTRIBUTION'=>array(
			'title'=>TR_CONFIG_DISPLAY_ATTRIBUTION,
			'description'=>TR_CONFIG_DISPLAY_ATTRIBUTION_DESC,
			'control'=>new dropdowncontrol(null,array('firstlast'=>'John Doe','lastfirst'=>'Doe, John','first'=>'John','username'=>'jdoe'))
		),
		'DISPLAY_DATETIME_FORMAT'=>array(
			'title'=>TR_CONFIG_DISPLAY_DATETIME_FORMAT,
			'description'=>TR_CONFIG_DISPLAY_DATETIME_FORMAT_DESC,
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('datetime_format'))
		),
		'DISPLAY_DATE_FORMAT'=>array(
			'title'=>TR_CONFIG_DISPLAY_DATE_FORMAT,
			'description'=>TR_CONFIG_DISPLAY_DATE_FORMAT_DESC,
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('date_format'))
		),
		'DISPLAY_TIME_FORMAT'=>array(
			'title'=>TR_CONFIG_DISPLAY_TIME_FORMAT,
			'description'=>TR_CONFIG_DISPLAY_TIME_FORMAT_DESC,
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('time_format'))
		)
	)
);

?>

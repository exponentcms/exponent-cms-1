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

$themes = array();
if (is_readable(BASE.'themes')) {
	$theme_dh = opendir(BASE.'themes');
	while (($theme_file = readdir($theme_dh)) !== false) {
		if (is_readable(BASE.'themes/'.$theme_file.'/class.php')) {
			// Need to avoid the duplicate theme problem.
			if (!class_exists($theme_file)) {
				include_once(BASE.'themes/'.$theme_file.'/class.php');
			}
			
			if (class_exists($theme_file)) {
				// Need to avoid instantiating non-existent classes.
				$t = new $theme_file();
				$themes[$theme_file] = $t->name();
			}
		}
	}
}
uasort($themes,'strnatcmp');

$i18n = pathos_lang_loadFile('conf/extensions/display.structure.php');

return array(
	$i18n['title'],
	array(
		'DISPLAY_THEME_REAL'=>array(
			'title'=>$i18n['theme_real'],
			'description'=>$i18n['theme_real_desc'],
			'control'=>new dropdowncontrol(null,$themes)
		),
		'DISPLAY_ATTRIBUTION'=>array(
			'title'=>$i18n['attribution'],
			'description'=>$i18n['attribution_desc'],
			'control'=>new dropdowncontrol(null,array('firstlast'=>'John Doe','lastfirst'=>'Doe, John','first'=>'John','username'=>'jdoe'))
		),
		'DISPLAY_DATETIME_FORMAT'=>array(
			'title'=>$i18n['datetime_format'],
			'description'=>$i18n['datetime_format_desc'],
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('datetime_format'))
		),
		'DISPLAY_DATE_FORMAT'=>array(
			'title'=>$i18n['date_format'],
			'description'=>$i18n['date_format_desc'],
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('date_format'))
		),
		'DISPLAY_TIME_FORMAT'=>array(
			'title'=>$i18n['time_format'],
			'description'=>$i18n['time_format_desc'],
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('time_format'))
		)
	)
);

?>

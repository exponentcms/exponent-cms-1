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

define('SYS_LANG',1);

function pathos_lang_list() {
	$dir = BASE.'subsystems/lang';
	$langs = array();
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($f = readdir($dh)) !== false) {
			if (substr($f,-4,4) == '.php') {
				$info = include($dir.'/'.$f);
				$langs[substr($f,0,-4)] = $info['name'] . ' -- ' . $info['author'];
			}
		}
	}
	return $langs;
}

function pathos_lang_initialize() {
	if (!defined('LANG')) {
		if (is_readable(BASE.'subsystems/lang/'.USE_LANG.'.php')) {
			define('LANG',USE_LANG); // Lang file exists.
		} else {
			define('LANG','en'); // Fallback to 'en' if language file not present.
		}
		$info = include(BASE.'subsystems/lang/'.LANG.'.php');
		setlocale(LC_ALL,$info['locale']);
		// For view resolution
		define('DEFAULT_VIEW',$info['default_view']);
	}
}

function pathos_lang_loadDictionary($type,$dictionary) {
	if (is_readable(BASE.'subsystems/lang/'.LANG.'/'.$type.'/'.$dictionary.'.php')) {
		include_once(BASE.'subsystems/lang/'.LANG.'/'.$type.'/'.$dictionary.'.php'); // Will define constants.
	} else {
		include_once(BASE.'subsystems/lang/en/'.$type.'/'.$dictionary.'.php'); // Will define constants.
	}
}

?>
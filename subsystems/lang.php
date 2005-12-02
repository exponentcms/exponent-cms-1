<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

define('SYS_LANG',1);

/*
define('SYS_LANG_MODULE',	1);
define('SYS_LANG_VIEW',		2);
define('SYS_LANG_ACTION',	3);
*/

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
		// For anything related to character sets:
		define('LANG_CHARSET',$info['charset']);
	}
}

/*
 * Load a set of language keys.
 *
 * @param string $filename The name of the file that should be internationalized.  This should
 * not start with a forward slash and well be taken relative to subsystems/lang/
 *
 * @return Array The language set found, or an empty array if no set file was found.
 */
function pathos_lang_loadFile($filename) {
	// Try to load the language set for the preferred language.
	$file = realpath(BASE.'subsystems/lang/'.LANG.'/'.$filename);
	if (is_readable($file)) {
#		// HACK
#		$r = include($file);
#		foreach ($r as $key=>$value) {
#			$r[$key] = '[i18n];'.$value.'[/i18n]';
#		}
#		return $r;
#		// END HACK
		return include($file);
	}
	
	// If we get to this point, the preferred language does not exist.  Try english.
	$file = realpath(BASE.'subsystems/lang/en/'.$filename);
	if (is_readable($file)) {
#		// HACK
#		$r = include($file);
#		foreach ($r as $key=>$value) {
#			$r[$key] = '[i18n]'.$value.'[/i18n]';
#		}
#		return $r;
#		// END HACK
		return include($file);
	}
	// By default, return an empty array.
	return array();
}

/*
 * Return a single key from a language set.
 *
 * @param string $filename The name of the file that should be internationalized.  This should
 * not start with a forward slash and well be taken relative to subsystems/lang/
 * @param string $key The name of the language key to return.
 *
 * @return Array The language set found, or an empty array if no set file was found.
 */
function pathos_lang_loadKey($filename,$key) {
	// First we load the full set.
	$keys = pathos_lang_loadFile($filename);
	// Then we return just the key we were told to.
#	// HACK
#	return '[i18n]'.$keys[$key].'[/i18n]';
#	// END HACK
	return $keys[$key];
}

?>

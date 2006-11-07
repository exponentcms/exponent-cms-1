<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

function exponent_lang_list() {
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

function exponent_lang_initialize() {
	if (!defined('LANG')) {
		if ((is_readable(BASE.'subsystems/lang/'.USE_LANG.'.php')) && (USE_LANG != 'en')) {
			define('LANG',USE_LANG); // Lang file exists.
		} else {
			define('LANG','eng_US'); // Fallback to 'eng_US' if language file not present.
		}
		$info = include(BASE.'subsystems/lang/'.LANG.'.php');
		setlocale(LC_ALL,$info['locale']);
		// For view resolution
		define('DEFAULT_VIEW',$info['default_view']);
		// For anything related to character sets:
		define('LANG_CHARSET',$info['charset']);
	}
}

function exponent_lang_loadLangs() {
	$ret = array();
	if (is_readable(BASE.'subsystems/lang')) {		
		while (($lang_file = readfile(BASE.'subsystems/lang/*.php')) !== false) {
			if (is_readable($lang_file)) {
				$ret = include($lang_file);
			}
		}
	}	
	return $ret;
}

/*
 * Load a set of language keys.
 *
 * @param string $filename The name of the file that should be internationalized.  This should
 * not start with a forward slash and well be taken relative to subsystems/lang/
 *
 * @return Array The language set found, or an empty array if no set file was found.
 */
function exponent_lang_loadFile($filename) {


	//so much for having a private function :(
	//we should convert REALLY convert our API to be OO
	if (!function_exists("loadStrings")) {
		//pass-by-reference to shave off a copy operation
		function loadStrings(&$tr_array, $filepath) {
			if (is_readable($filepath)) {
				$tr_array = array_merge($tr_array, include($filepath));
			}
		}
	}
	

	//initialize the array to be returned
	$_TR = array();


	//set the language directory
	$lang_dir = BASE . 'subsystems/lang/' . LANG;
	
	//check if the requested language is installed
	if (!file_exists($lang_dir)) {

		// If we get to this point, the preferred language does not exist.  Try english.
		$lang_dir = BASE . 'subsystems/lang/eng_US';
	}


	//load the most common strings
	loadStrings($_TR, $lang_dir . "/modules/modules.php");


	//load module specific strings
	$path_components = explode("/", $filename);
	//as the typical path will be something like modules/somemodule/views/someview.php it must be 1
	$module = array();
	if (count($path_components) > 1) {
		$module = $path_components[1];
	}
	
	loadStrings($_TR, $lang_dir . "/modules/" . $module . "/" . $module . ".php");
	

	//load the view specific strings
	loadStrings($_TR, $lang_dir . "/" . $filename);

	return $_TR;
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
function exponent_lang_loadKey($filename,$key) {
	// First we load the full set.
	$keys = exponent_lang_loadFile($filename);
	// Then we return just the key we were told to.
#	// HACK
#	return '[i18n]'.$keys[$key].'[/i18n]';
#	// END HACK
	return $keys[$key];
}

/*
 * Return a short language code from a long one, many external programs use the short ones
 * its a dumb, straight table lookup function, no fancy regexp rules.
 * It should rather be replaced by introducing a short lang code to the language descriptor files
 * and replacing the site wide CONSTANTS by global objects, which then in return
 * could have a multitude of subobjects and properties, such as long and short codes
 * 
 * @param string $long_code something like "eng_US"
 *
 * @return string the short version of the lang code
 */
function exponent_lang_convertLangCode($long_code) {
	switch ($long_code) {
		case "deu_DE":
			$short_code = "de";
		case "eng_US":
			$short_code = "en";
	}
	return $short_code;
}

?>

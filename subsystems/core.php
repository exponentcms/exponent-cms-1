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

/**
 * Core Subsystem
 *
 * The core subsystem provides basic functionality to other
 * parts of Exponent.
 *
 * @package		Subsystems
 * @subpackage	Core
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flasg for Core Subsystem
 *
 * The definition of this constant lets other parts
 * of the system know that the Core Subsystem
 * has been included for use.
 */
define("SYS_CORE",1);

define("CORE_EXT_MODULE",1);
define("CORE_EXT_THEME",2);
define("CORE_EXT_SUBSYSTEM",3);

/**
 * Create a Location
 *
 * Creates a location object, based off of the three arguments passed.
 *
 * @param		$mo	The module component of the location.
 * @param		$src	The source component of the location.
 * @param		$int	The internal component of the location.
 * @return		object	A location object with the given fields set.
 */
function pathos_core_makeLocation($mod=null,$src=null,$int=null) {
	$loc = null;
	$loc->mod = ($mod ? $mod : "");
	$loc->src = ($src ? $src : "");
	$loc->int = ($int ? $int : "");
	return $loc;
}

function pathos_core_resolveDependencies($ext_name,$ext_type) {
	$depfile = "";
	switch ($ext_type) {
		case CORE_EXT_SUBSYSTEM:
			$depfile = "subsystems/$ext_name.deps.php";
			break;
		case CORE_EXT_THEME:
			$depfile = "themes/$ext_name/deps.php";
			break;
		case CORE_EXT_MODULE:
			$depfile = "modules/$ext_name/deps.php";
			break;
	}
	
	$deps = array();
	if (is_readable($depfile)) {
		$deps = include($depfile);
		foreach ($deps as $dep=>$info) {
			$deps = array_merge($deps,pathos_core_resolveDependencies($dep,$info['type']));
		}
	}
	
	return $deps;
}

/**
 * Translate Location Source to User-friendly text.
 *
 * Takes a source string (the src attribute of a location object) and
 * does special translation on it.  Mostly, it just translates "@section$ID"
 * into "Sectional : $SECTION_NAME".
 *
 * @param $src The source to translate.
 * @param string The translated source.
 * @deprecated	0.95beta8
 */
/*
./modules/addressbookmodule/actions/copy.php
./modules/addressbookmodule/actions/reference.php
./modules/workflow/actions/admin_confirmdeletepolicy.php
./modules/workflow/actions/admin_manage_policies.php
./modules/workflow/actions/admin_savepolicy.php
./subsystems/core.php
 */
function pathos_core_translateLocationSource($src) {
	if (substr($src,0,8) == "@section") {
		global $db;
		$sect = $db->selectObject("section","id=" . (substr($src,8)+0));
		return "Sectional: " . $sect->name;
	} else if ($src == "" || $src == null) {
		return "&lt;None&gt;";
	} else if (substr($src,0,7) == "@random") {
		return "&lt;Non-Reusable Content&gt;";
	} else if (substr($src,0,1) == "@") {
		return "&lt;Special Content&gt;";
	}
	else return $src;
}

function pathos_core_makeLink($params) {
	$link = (ENABLE_SSL ? NONSSL_URLBASE : "");
	if (SEF_URLS == 0) {
		$link .= SCRIPT_RELATIVE . SCRIPT_FILENAME . "?";
		foreach ($params as $key=>$value) {
			$value = chop($value);
			$key = chop($key);
			if ($value != "") $link .= urlencode($key)."=".urlencode($value)."&";
		}
		$link = substr($link,0,-1);
		return $link;
	} else {
		$link .= SCRIPT_RELATIVE  . SCRIPT_FILENAME . "/";
		ksort($params);
		foreach ($params as $key=>$value) {
			$value = chop($value);
			$key = chop($key);
			if ($value != "") $link .= urlencode($key)."/".urlencode($value)."/";
		}
		$link = substr($link,0,-1);
		return $link;
	}
}

function pathos_core_makeSecureLink($params) {
	if (!ENABLE_SSL) return pathos_core_makeLink($params);
	if (SEF_URLS == 0) {
		$link = SSL_URL . SCRIPT_FILENAME . "?";
		foreach ($params as $key=>$value) {
			$value = chop($value);
			$key = chop($key);
			if ($value != "") $link .= urlencode($key)."=".urlencode($value)."&";
		}
		$link = substr($link,0,-1);
		return $link;
	} else {
		$link = SSL_URL  . SCRIPT_FILENAME . "/";
		ksort($params);
		foreach ($params as $key=>$value) {
			$value = chop($value);
			$key = chop($key);
			if ($value != "") $link .= urlencode($key)."/".urlencode($value)."/";
		}
		$link = substr($link,0,-1);
		return $link;
	}
}

/**
 * List Themes installed on site.
 *
 * Looks through the themes directory and returns a list
 * of themes.
 *
 * @return array() An associative array. For each key=>value pair, the
 * key is the theme class name, and the value is the
 * user-friendly theme name ("Default Theme")
 */
function pathos_core_listThemes() {
	$themes = array();
	if (is_readable(BASE."themes")) {
		$dh = opendir(BASE."themes");
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."themes/$file/class.php")) {
				include_once(BASE."themes/$file/class.php");
				$t = new $file();
				$themes[$file] = $t->name();
			}
		}
	}
	return $themes;
}


## Remove these two functions if no use is found for them.  They are commented
## and have no references elsewhere in the code.
/*
 * Convert an associatvie array into a stdClass object
 *
 * Given an associative array, this function will convert
 * it into a corresponding object, whose members mimc
 * the key=>value pairs in the array.
 *
 * @deprecated	0.95beta8
 function pathos_core_arrayToObject($array) {
	$obj = null;
	foreach ($array as $key=>$value) {
		$obj->$key = $value;
	}
	return $obj;
}
*/

/*
 * Convert a stdClass object into an associative array.
 *
 * Given an object, this function will convert it into a
 * corresponding associatve array, whose key=>value
 * pairs mimc the members in the object.
 *
 * @deprecated	0.95beta8
function pathos_core_objectToArray($obj) {
	$array = array();
	foreach (get_object_vars($obj) as $key=>$value) {
		$array[$key] = $value;
	}
	return $array;
}
 */

/*
 * Create a duplicate copy of an object.
 *
 * Put in place to get around the strange assignment
 * semantics in PHP5 (assign by ref not value)
 */
function pathos_core_copyObject($o) {
	$new = null;
	foreach (get_object_vars($o) as $var=>$val) $new->$var = $val;
	return $new;
}

function pathos_core_decrementLocationReference($loc,$section) {
	global $db;
	$oldLocRef = $db->selectObject("locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	$oldSecRef = $db->selectObject("sectionref", "module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
	
	$oldLocRef->refcount -= 1;
	$oldSecRef->refcount -= 1;
	
	$db->updateObject($oldLocRef,"locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	$db->updateObject($oldSecRef,"sectionref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
}

function pathos_core_incrementLocationReference($loc,$section) {
	global $db;
	$newLocRef = $db->selectObject("locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	if ($newLocRef != null) {
		// Pulled an existing source.  Update refcount
		$newLocRef->refcount += 1;
		$db->updateObject($newLocRef,"locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	} else {
		// New source.  Populate reference
		$newLocRef->module   = $loc->mod;
		$newLocRef->source   = $loc->src;
		$newLocRef->internal = $loc->int;
		$newLocRef->refcount = 1;
		$db->insertObject($newLocRef,"locationref");
		
		// Go ahead and assign permissions on contained module.
		$perms = call_user_func(array($loc->mod,"permissions"));
		global $user;
		foreach (array_keys($perms) as $perm) {
			pathos_permissions_grant($user,$perm,$loc);
		}
		pathos_permissions_triggerSingleRefresh($user);
	}
	
	$newSecRef = $db->selectObject("sectionref", "module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
	if ($newSecRef != null) {
		// Pulled an existing source for this section.  Update refcount
		$newSecRef->refcount += 1;
		$db->updateObject($newSecRef,"sectionref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
	} else {
		// New source for this section.  Populate reference
		$newSecRef->module   = $loc->mod;
		$newSecRef->source   = $loc->src;
		$newSecRef->internal = $loc->int;
		$newSecRef->section = $section;
		$newSecRef->refcount = 1;
		$db->insertObject($newSecRef,"sectionref");
	}
}

function pathos_core_version($full = false, $build = false) {
	if (!defined("PATHOS_VERSION_MAJOR")) include_once(BASE."pathos_version.php");
	$vers = PATHOS_VERSION_MAJOR.".".PATHOS_VERSION_MINOR;
	if ($full) {
		$vers .= ".".PATHOS_VERSION_REVISION;
		if (PATHOS_VERSION_TYPE != "") $vers .= "-".PATHOS_VERSION_TYPE.PATHOS_VERSION_ITERATION;
	}
	if ($build) {
		$vers .= " (Build Date: ".strftime("%D",PATHOS_VERSION_BUILDDATE).")";
	}
	return $vers;
}

/**
 * Check URL Validity
 *
 * This function checks a full URL against a set of
 * known protocls (like http and https) and determines
 * if the URL is valid.
 *
 * @param string $url The URL to test for validity
 *
 * @return boolean True if the URL is valid, and false if otherwise.
 */
function pathos_core_URLisValid($url) {
	return (
		substr($url,0,7) == "http://" ||
		substr($url,0,8) == "https://" ||
		substr($url,0,7) == "mailto:" ||
		substr($url,0,6) == "ftp://"
	);
}

?>
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

/* exdoc
 * The definition of this constant lets other parts
 * of the system know that the Core Subsystem
 * has been included for use.
 * @node Subsystems:Core
 */
define("SYS_CORE",1);

/* exdoc
 * This constant can (and should) be used by other parts of the system
 * for defining and communicating an extension type of module.
 * @node Subsystems:Core
 */
define("CORE_EXT_MODULE",1);
/* exdoc
 * This constant can (and should) be used by other parts of the system
 * for defining and communicating an extension type of theme.
 * @node Subsystems:Core
 */
define("CORE_EXT_THEME",2);
/* exdoc
 * This constant can (and should) be used by other parts of the system
 * for defining and communicating an extension type of subsystem.
 * @node Subsystems:Core
 */
define("CORE_EXT_SUBSYSTEM",3);

/* exdoc
 * Creates a location object, based off of the three arguments passed, and returns it.
 *
 * @param		$mo	The module component of the location.
 * @param		$src	The source component of the location.
 * @param		$int	The internal component of the location.
 * @node Subsystems:Core
 */
function pathos_core_makeLocation($mod=null,$src=null,$int=null) {
	$loc = null;
	$loc->mod = ($mod ? $mod : "");
	$loc->src = ($src ? $src : "");
	$loc->int = ($int ? $int : "");
	return $loc;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * Takes a source string (the src attribute of a location object) and
 * does special translation on it.  Mostly, it just translates "@section$ID"
 * into "Sectional : $SECTION_NAME".
 *
 * @param $src The source to translate.
 * @param string The translated source.
 * @state Deprecated since 0.95beta8
 * @node Subsystems:Core
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

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * Looks through the themes directory and returns a list
 * of themes. returns an associative array. For each key=>value pair, the
 * key is the theme class name, and the value is the
 * user-friendly theme name ("Default Theme")
 * @node Subsystems:Core
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

/* exdoc
 * Put in place to get around the strange assignment
 * semantics in PHP5 (assign by ref not value)
 * @param Object $o The object to copy.  An exact duplicate of this will be returned.
 * @node Subsystems:Core
 */
function pathos_core_copyObject($o) {
	$new = null;
	foreach (get_object_vars($o) as $var=>$val) $new->$var = $val;
	return $new;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_core_decrementLocationReference($loc,$section) {
	global $db;
	$oldLocRef = $db->selectObject("locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	$oldSecRef = $db->selectObject("sectionref", "module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
	
	$oldLocRef->refcount -= 1;
	$oldSecRef->refcount -= 1;
	
	$db->updateObject($oldLocRef,"locationref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."'");
	$db->updateObject($oldSecRef,"sectionref","module='".$loc->mod."' AND source='".$loc->src."' AND internal='".$loc->int."' AND section=$section");
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * This function checks a full URL against a set of
 * known protocls (like http and https) and determines
 * if the URL is valid.  Returns true if the URL is valid,
 * and false if otherwise.
 *
 * @param string $url The URL to test for validity
 * @node Subsystems:Core
 */
function pathos_core_URLisValid($url) {
	return (
		substr($url,0,7) == "http://" ||
		substr($url,0,8) == "https://" ||
		substr($url,0,7) == "mailto:" ||
		substr($url,0,6) == "ftp://"
	);
}

function pathos_core_maxUploadSizeMessage($single = true) {
	$size = ini_get("upload_max_filesize");
	$size_msg = "";
	$type = substr($size,-1,1);
	$shorthand_size = substr($size,0,-1);
	switch ($type) {
		case 'M':
			$size_msg = $shorthand_size . ' MB';
			break;
		case 'K':
			$size_msg = $shorthand_size . ' kB';
			break;
		case 'G': // PHP5 +
			$size_msg = $shorthand_size . ' GB';
			break;
		default:
			if ($size >= 1024*1024*1024) { // Gigs
				$size_msg = round(($size / (1024*1024*1024)),2) . " GB";
			} else if ($size >= 1024*1024) { // Megs
				$size_msg = round(($size / (1024*1024)),2) . " MB";
			} else if ($size >= 1024) { // Kilo
				$size_msg = round(($size / 1024),2) . " kB";
			} else {
				$size_msg = $size . " bytes";
			}
	}
	return 'The maximum size of uploaded files is ' . $size_msg . '.  Uploading files larger than that may result in erratic behavior.';
}

?>
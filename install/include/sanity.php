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

define('SANITY_FINE',				0);
define('SANITY_NOT_R',				2);
define('SANITY_NOT_RW',				4);
define('SANITY_NOT_E',				8);

define('SANITY_READONLY',			1);
define('SANITY_READWRITE',			2);
define('SANITY_CREATEFILE',			4); // Read write, without the need for the file to exist prior

define('SANITY_WARNING',			1);
define('SANITY_ERROR',				2);

function sanity_checkFile($file,$as_file,$flags) {
	$__oldumask = umask(0);
	if (!file_exists($file)) {
		if ($flags == SANITY_CREATEFILE) {
			return sanity_checkFile(dirname($file),false,SANITY_READWRITE);
		} else {
			if ($as_file) {
				@touch($file);
			} else {
				@mkdir($file,0777);
			}
		}
	}
	if (!file_exists($file)) {
		umask($__oldumask);
		return SANITY_NOT_E;
	} else if ($flags == SANITY_CREATEFILE) {
		$flags = SANITY_READWRITE;
	}
	$not_r = false;
	// File exists.  Check the flags for what to check for
	if ($flags == SANITY_READONLY || $flags == SANITY_READWRITE) {
		if (!is_readable($file)) {
			@chmod($file,0777);
		}
		if (!is_readable($file)) {
			if ($flags == SANITY_READONLY) {
				umask($__oldumask);
				return SANITY_NOT_R;
			}
			// Otherwise, we need to set NOT_R
			$not_r = true;
		}
	}
	if ($flags == SANITY_READWRITE) {
		if (!is_really_writable($file)) {
			@chmod($file,0777);
		}
		if (!is_really_writable($file)) {
			umask($__oldumask);
			return SANITY_NOT_RW;
		} else if ($not_r) {
			umask($__oldumask);
			return SANITY_NOT_R;
		}
	}
	return SANITY_FINE;
}

function sanity_checkDirectory($dir,$flag,&$status) {
	$status[$dir] = sanity_checkFile(BASE.$dir,0,$flag);
	if (is_readable(BASE.$dir)) {
		$dh = opendir(BASE.$dir);
		while (($file = readdir($dh)) !== false) {
			if ($file{0} != '.' && $file != 'CVS') {
				if (is_file(BASE.$dir.'/'.$file)) {
					$status[$dir.'/'.$file] = sanity_checkFile(BASE.$dir.'/'.$file,1,$flag);
				} else {
					sanity_checkDirectory($dir.'/'.$file,$flag,$status);
				}
			}
		}
	}
}

function sanity_checkFiles() {
	$status = array(
		'conf/config.php'=>sanity_checkFile(BASE.'conf/config.php',1,SANITY_CREATEFILE),
		'conf/profiles'=>sanity_checkFile(BASE.'conf/profiles',0,SANITY_READWRITE),
		'overrides.php'=>sanity_checkFile(BASE.'overrides.php',1,SANITY_READWRITE),
		'install'=>sanity_checkFile(BASE.'install',0,SANITY_READWRITE),
		'modules'=>sanity_checkFile(BASE.'modules',0,SANITY_READONLY),
		'views_c'=>sanity_checkFile(BASE.'views_c',0,SANITY_READWRITE),
		'extensionuploads'=>sanity_checkFile(BASE.'extensionuploads',0,SANITY_READWRITE)
	);
	sanity_checkDirectory('files',SANITY_READWRITE,$status);
	sanity_checkDirectory('tmp',SANITY_READWRITE,$status);
	return $status;
}

function sanity_checkServer() {
	$status = array(
		'GD Graphics Library'=>_sanity_checkGD(),
		'PHP Version'=>_sanity_checkPHPVersion(),
		'ZLib Compression'=>_sanity_checkZlib(),
		'XML (Expat) Library'=>_sanity_checkXML(),
		'Safe Mode'=>_sanity_CheckSafeMode(),
		'Open BaseDir Restriction'=>_sanity_checkOpenBaseDir(),
		'File Uploads'=>_sanity_checkTemp(ini_get('upload_tmp_dir')),
		'Temporary File Creation'=>_sanity_checkTemp(BASE.'tmp'),
	);
	return $status;
}

function sanity_checkModules() {
	$status = array();
	if (is_readable(BASE.'modules')) {
		$dh = opendir(BASE.'modules');
		while (($moddir = readdir($dh)) !== false) {
			if (is_dir(BASE.'modules/'.$moddir) && substr($moddir,0,1) != "." && file_exists(BASE.'modules/'.$moddir."/views") && substr($moddir,-6,6) == "module") {
				// Got a module.
				if (!is_readable(BASE.'modules/'.$moddir."/class.php")) {
					$status[$moddir] = array(SANITY_WARNING,'Can\'t read class file');
				} else {
					$status[$moddir] = array(SANITY_FINE,'Okay');
				}
			}
		}
	}
	return $status;
}

function _sanity_checkGD() {
	$info = gd_info();
	if ($info['GD Version'] == 'Not Supported') {
		return array(SANITY_WARNING,'Not Supported');
	} else if (strpos($info['GD Version'],'2.0') === false) {
		return array(SANITY_WARNING,'Older Version Installed ('.$info['GD Version'].')');
	}
	return array(SANITY_FINE,$info['GD Version']);
}

function _sanity_checkPHPVersion() {
	if (version_compare(phpversion(),'4.0.6','>')) {
		return array(SANITY_FINE,phpversion());
	} else {
		return array(SANITY_ERROR,'PHP < 4.0.6 (not supported)');
	}
}

function _sanity_checkZlib() {
	if (function_exists('gzdeflate')) {
		return array(SANITY_FINE,'Installed');
	} else {
		return array(SANITY_ERROR,'Not Installed');
	}
}

function _sanity_checkSafeMode() {
	if (ini_get('safe_mode') == 1) {
		return array(SANITY_WARNING,'Enabled');
	} else {
		return array(SANITY_FINE,'Not Enabled');
	}
}

function _sanity_checkXML() {
	if (function_exists('xml_parser_create')) {
		return array(SANITY_FINE,'Installed');
	} else {
		return array(SANITY_WARNING,'Not Installed');
	}
}

function _sanity_checkOpenBaseDir() {
	$path = ini_get('open_basedir');
	if ($path == '') {
		return array(SANITY_FINE,'Not Enabled');
	} else {
		return array(SANITY_WARNING,'Enabled');
	}
}

function _sanity_checkTemp($dir) {
	$file = tempnam($dir,'temp');
	if (is_readable($file) && is_really_writable($file)) {
		unlink($file);
		return array(SANITY_FINE,'Enabled');
	} else {
		return array(SANITY_ERROR,'Not Enabled');
	}
}


//-------------------------------------------------------------------------

function sanity_checkThemes() {
	global $warnings, $errors;
	$__oldumask = umask(0);
	
	$themebase = BASE."themes";
	if (is_readable($themebase)) {
		$basedh = opendir($themebase);
		$one_readable = false;
		while (($themedir = readdir($basedh)) !== false) {
			if (is_dir($themebase."/".$themedir) && substr($themedir,0,1) != ".") {
				if (!is_readable($themebase."/".$themedir)) {
					$warnings[] = "Theme directory for '$themedir' (themes/$themedir) is not readable.  This theme will not be available for use.";
				} else {
					$one_readable = true;
				}
			}
		}
		if (!$one_readable) {
			$errors[] = "No theme directories in themes/ are readable.";
		}
	} else $errors[] = "Themes directory (themes/) is not readable.";
	
	umask($__oldumask);
}


?>


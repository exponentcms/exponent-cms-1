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
 * Info Subsystem
 *
 * Provides reflective information about the components of
 * the current website.
 *
 * @package		Subsystems
 * @subpackage	Info
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag for Info Subsystem
 *
 * The definition of this constant lets other parts
 * of the system know that the Info Subsystem
 * has been included for use.
 */
define("SYS_INFO",1);

/**
 * Look up information on a subsystem
 *
 * Looks through the subsystems/ directory for a *.info.php for
 * a given subsystem, and returns the metadata stored in that file.
 *
 * @param string $subsystem The name of the subsystem to retrieve information about.
 * @return array An array of meta tag/values for the subsystem.  Returns null if no information
 *    file was found.
 */
function pathos_info_subsystemInfo($subsys) {
	if (!is_readable(BASE."subsystems/$subsystem.info.php")) return null;
	return include(BASE."subsystems/$subsystem.info.php");
}

/**
 * Lists subsystems installed
 *
 * Looks through the subsystems/ directory for all subsystems currently installed,
 * and retrieves their information.
 *
 * @return array An array of all subsystems, with meta tags/values for each.
 */
function pathos_info_subsystems() {
	$info = array();
	
	$dir = BASE."subsystems";
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if (is_readable("$dir/$file") && substr($file,-9,9) == ".info.php") {
				$info[substr($file,0,-9)] = include("$dir/$file");
			}
		}
	}
	return $info;
}

/**
 * Lists all files for an extension.
 *
 * Looks for a manifest file, which contains a list of all files
 * claimed by the given extension.  This list also contains the
 * cached file checksums, for verification purpses.
 *
 * MD5 checksums are used to verify file integrity.
 *
 * @param integer $type The type of extension.
 * @param string $name The name of the extension
 * @return mixed An array or a string.  If no manifest file is found,
 *    or the specified extension was not found, a string error is returned.
 *    otherwise an array of files information is returned.
 * @see pathos_info_fileChecksums
 */
function pathos_info_files($type,$name) {
	$dir = "";
	$file = "manifest.php";
	switch ($type) {
		case CORE_EXT_MODULE:
			$dir = BASE."modules/$name";
			break;
		case CORE_EXT_THEME:
			$dir = BASE."themes/$name";
			break;
		case CORE_EXT_SUBSYSTEM:
			$dir = BASE."subsystems";
			$file = "$name.manifest.php";
			break;
		default:
			echo "Bad type: $type";
	}
	
	if (is_readable("$dir/$file")) return include("$dir/$file");
	else if (!is_readable($dir)) return "No such extensions ($name)";
	else return "Manifest file not found.";
}

/**
 * Run checksums on a list of files
 *
 * Generates an MD5 file checksum of each file in the passed array,
 * and returns a new array of the checksums.
 *
 * @param array $files An array of file names to generate checksums for
 * @return array The checksums for the passed files.  Each checksum is indexed
 *    by the file it belongs to.
 * @see pathos_info_files
 */
function pathos_info_fileChecksums($files) {
	$newfiles = array();
	foreach (array_keys($files) as $file) {
		if (is_integer($files[$file])) $newfiles[$file] = "";
		else $newfiles[$file] = md5_file(BASE.$file);
	}
	return $newfiles;
}

/**
 * Highlight a file and show line numbering
 *
 * Slightly bastardized by James, for Exponent
 *
 * @param        string  $data       The string to add line numbers to
 * @param        bool    $funclink   Automatically link functions to the manual
 * @param        bool    $return     return or echo the data
 * @author       Aidan Lister <aidan@php.net>
 * @version      1.0.0
 */
function pathos_info_highlightPHP($data, $return = true)
{
    // Init
	ob_start();
	highlight_string($data); // for better compat with PHP < 4 . 20
	$contents = ob_get_contents();
	ob_end_clean();
	
    $data = explode ('<br />', $contents);
    $start = '<span style="color: black;">';
    $end   = '</span>';
    $i = 1;
    $text = '';

    // Loop
    foreach ($data as $line) {
		$spacer = str_replace(" ","&nbsp;",str_pad("",5-strlen($i."")));
    	$text .= $start . $i . $spacer . $end . str_replace("\n", '', $line) . "<br />\n";
	    ++$i;
    }
	
    // Return mode
    if ($return === false) {
        echo $text;
    } else {
        return $text;
    }
}

?>
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

#
# Shared Core Library File
#
# NOTE : Shared Core extension to Exponent only works on UNIX servers
#   because Windows platforms do not support symlinks.
#

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SYS_SHAREDCORE",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_OK",			0);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_LINKDEST_EXISTS",	1);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_LINKDEST_NOTWRITABLE",	2);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_LINKSRC_NOTEXISTS",	3);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_LINKSRC_NOTREADABLE",	4);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SHAREDCORE_ERR_BADFILETYPE",		5);

//way to check if Apache uses symlinks?

/* exdoc
 * This is a specific function for linking the files and directories
 * from one core distro to a linked site.  Returns one of the SHARED_CORE_ERR_* constants
 *
 * @param string $typdir The directory name for the type of extension to link,
 *   One of either 'modules', 'subsystems' or 'themes'
 * @param string $name The name of the extension to link.
 * @param string $source The source path (to the root of Exponent)
 * @param string $destination The destination path (to the root of Exponent)
 * @param Constant $type The type of linking to perform.  One of either SHAREDCORE_LINK_NONE,
 *   SHAREDCORE_LINK_SHALLOW, SHAREDCORE_LINK_HALFDEEP or
 *   SHAREDCORE_LINK_FULLDEEP
 * @node Subsystems:SharedCore
 */
function pathos_sharedcore_linkExtension($typedir,$name,$source,$destination) {
	// Now we can pass either.
	if ($typedir == CORE_EXT_MODULE) $typedir = "modules";
	else if ($typedir == CORE_EXT_SUBSYSTEM) $typedir = "subsystems";
	else if ($typedir == CORE_EXT_THEME) $typedir = "themes";
	
	if (substr($source,-1,1) == "/") $source = substr($source,0,-1);
	if (substr($destination,-1,1) == "/") $destination = substr($destination,0,-1);
	$linksrc = "$source/$typedir/$name";
	$linkdest = "$destination/$typedir/$name";
	
	### FIX ERROR CHECKING (goes here)
	
	if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
	
	if ($typedir == "subsystems" && !file_exists("$linkdest.php")) { // subsytems need something else.
		symlink("$linksrc.php","$linkdest.php");
		if (is_readable($linksrc.".info.php")) {
			symlink("$linksrc.info.php","$linkdest.info.php");
		}
		if (is_readable($linksrc.".manifest.php")) {
			symlink("$linksrc.manifest.php","$linkdest.manifest.php");
			foreach (array_keys(include($linksrc.".manifest.php")) as $file) {
				if (!file_exists($destination."/".$file)) {
					$fparts = explode("/",$file);
					$file = $fparts[count($fparts)-1];
					unset($fparts[count($fparts)-1]);
					$dir = implode("/",$fparts);
					
					pathos_files_makeDirectory($destination."/".$dir,0755,true);
					symlink($source."/".$dir."/".$file,$destination."/".$dir."/".$file);
				}
			}
		}
		
		// Link dependency subsystems
		if (is_readable($linksrc.".deps.php")) {
			foreach (include("$linksrc.deps.php") as $info) {
				pathos_sharedcore_linkExtension($info['type'],$info['name'],$source,$destination);
			}
		}
		
		return SHAREDCORE_ERR_OK;
	} else if (($typedir == "modules" || $typedir == "themes") && !file_exists($linkdest)) {
		pathos_files_copyDirectoryStructure($linksrc,$linkdest);
		foreach (array_keys(include($linksrc."/manifest.php")) as $file) {
			if (!file_exists($destination."/".$file)) {
				$fparts = explode("/",$file);
				$file = $fparts[count($fparts)-1];
				unset($fparts[count($fparts)-1]);
				$dir = implode("/",$fparts);
				
				pathos_files_makeDirectory($destination."/".$dir,0755,true);
				symlink($source."/".$dir."/".$file,$destination."/".$dir."/".$file);
			}
		}
		
		if (is_readable("$linksrc/deps.php")) {
			foreach (include("$linksrc/deps.php") as $info) {
				pathos_sharedcore_linkExtension($info['type'],$info['name'],$source,$destination);
			}
		}
		
		return SHAREDCORE_ERR_OK;
	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_sharedcore_clear($linked_root,$full_delete = false) {
	# Remove all links.  If $full_delete is false, leave the files/ and conf/
	# directories intact (because we are going to relink).  Otherwise, delete
	# absolutely everything.
	
	if (substr($linked_root,-1,1) != "/") $linked_root .= "/";
	
	if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
	
	$dh = opendir($linked_root);
	while (($file = readdir($dh)) !== false) {
		if ($file != "." && $file != "..") {
			if (is_file("$linked_root$file") || is_link("$linked_root$file")) {
				unlink("$linked_root$file");
			} else {
				if (!$full_delete && ($file == "conf" || $file == "files")) {
					// Do nothing
				} else {
					pathos_files_removeDirectory("$linked_root$file");
				}
			}
		}
	}
}

/* exdoc
 * Unlink a previously linked extension
 *
 * @param string $typdir The directory name for the type of extension to link,
 *   One of either 'modules', 'subsystems' or 'themes'
 * @param string $name The name of the extension to link.
 * @param string $dir The root of the linked site
 * @node Subsystems:SharedCore
 */
 # This may be deprecated
function pathos_sharedcore_unlinkExtension($typedir,$name,$dir) {
	if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
	pathos_files_removeDirectory("$dir/$typedir/$name");
}


/* exdoc
 * Link Exponent Core
 *
 * @param string $linksrc The source core to link to
 * @param string $linkdest The destination directory to created
 *   symlinks in.
 * @node Subsystems:SharedCore
 */
function pathos_sharedcore_linkCore($linksrc,$linkdest) {
	if (!file_exists($linksrc)) {
		return SHAREDCORE_ERR_LINKSRC_NOTEXISTS;
	}
	if (!is_readable($linksrc)) {
		return SHAREDCORE_ERR_LINKSRC_NOTREADABLE;
	}
	if (!is_writable($linkdest)) {
		return SHAREDCORE_ERR_LINKDEST_NOTWRITABLE;
	}
	
	if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
	$exclude = array(
		"external",
		"modules",
		"subsystems",
		"themes",
		"views_c"
	);
	
	$fh = fopen($linkdest."overrides.php","w");
	fwrite($fh,"<?php\n\ndefine(\"BASE\",\"$linkdest\");\n\n?>\n");
	fclose($fh);
	
	pathos_files_copyDirectoryStructure($linksrc,$linkdest,$exclude);
	
	mkdir($linkdest."views_c",fileperms($linksrc."views_c"));
	mkdir($linkdest."modules",fileperms($linksrc."modules"));
	mkdir($linkdest."themes",fileperms($linksrc."themes"));
	mkdir($linkdest."subsystems",fileperms($linksrc."subsystems"));
	symlink($linksrc."external",$linkdest."external");
	
	// Link dependent subsystems
	$linksrc_notrail = substr($linksrc,0,-1);
	$linkdest_notrail = substr($linkdest,0,-1);
	foreach (include($linksrc."deps.php") as $info) {
		pathos_sharedcore_linkExtension($info['type'],$info['name'],$linksrc_notrail,$linkdest_notrail);
	}
	
	foreach (include($linksrc."manifest.php") as $file=>$linkit) {
		if ($linkit !== 0 && file_exists($linksrc.$file)) symlink($linksrc.$file,$linkdest.$file);
	}
	
	return SHAREDCORE_ERR_OK;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_sharedcore_listCores($dir) {
	$arr = array();
	if (!is_readable($dir)) return $arr;
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		if (is_dir("$dir/$file") && substr($file,0,1) != ".") {
			if (file_exists("$dir/$file/pathos_version.php") && !is_link("$dir/$file/pathos_version.php")) {
				$arr[] = "$dir/$file";
			}
			$arr = array_merge($arr,pathos_sharedcore_listCores("$dir/$file"));
		}
	}
	return $arr;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_sharedcore_listLinkedSites($dir,$core = null) {
	$arr = array();
	if (!is_readable($dir)) return $arr;
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		if (is_dir("$dir/$file") && substr($file,0,1) != ".") {
			if (file_exists("$dir/$file/pathos_version.php") && is_link("$dir/$file/pathos_version.php")) {
				if ($core == null || dirname(readlink("$dir/$file/pathos_version.php")) == $core) {
					$arr[] = "$dir/$file";
				}
			}
			$arr = array_merge($arr,pathos_sharedcore_listLinkedSites("$dir/$file"));
		}
	}
	return $arr;
}

?>
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
 * Files Subsystem
 *
 * Provides an easy and consistent way of dealing
 * with files in a CMS environment.
 *
 * @package		Subsystems
 * @subpackage	Files
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flasg for Files Subsystem
 *
 * The definition of this constant lets other parts
 * of the system know that the Files Subsystem
 * has been included for use.
 */
define("SYS_FILES",1);

/**
 * Filesystem Error Response: Success
 */
define("SYS_FILES_SUCCESS",		0);

/**
 * Filesystem Error Response: Found File at Destination
 */
define("SYS_FILES_FOUNDFILE",	1);

/**
 * Filesystem Error Response: Found Directory at Destination
 */
define("SYS_FILES_FOUNDDIR",	2);

/**
 * Filesystem Error Response: Destination not writable
 */
define("SYS_FILES_NOTWRITABLE",	3);

/**
 * Filesystem Error Response: Destination not readable
 */
define("SYS_FILES_NOTREADABLE",	4);

/**
 * Filesystem Error Response: Destination not deletable
 */
define("SYS_FILES_NOTDELETABLE",	5);

/**
 * Makes a Directory
 *
 * This method creates a directory and all of its parent directories, if they do not exist,
 * emulating the behavior of the -p option to mkdir on UNIX systems.
 *
 * @param string $dir The directory to create.  This path must be relative to BASE
 * @return integer A SYS_FILES_* constant.
 */
function pathos_files_makeDirectory($dir,$mode=null,$is_full=false) {
	$__oldumask = umask(0);
	$parentdir = ($is_full ? "" : BASE); // we will add to parentdir with each directory
	foreach (explode("/",$dir) as $part) {
		if ($part != "" && !is_dir($parentdir.$part)) {
			// No parent directory.  Create it.
			if (is_file($parentdir.$part)) return SYS_FILES_FOUNDFILE;
			if (is_writable($parentdir)) {
				if ($mode == null) $mode = DIR_DEFAULT_MODE;
				mkdir($parentdir.$part,$mode);
				chmod($parentdir.$part,$mode);
			} else return SYS_FILES_NOTWRITABLE;
		}
		$parentdir .= $part."/";
	}
	umask($__oldumask);
	return SYS_FILES_SUCCESS;
}

/**
 * Remove a Directory
 *
 * Recursively removes the given directory, and all
 * of the files and directories underneath it.
 *
 * @param string $dir The path of the directory to remove
 */
function pathos_files_removeDirectory($dir) {
	if (strpos($dir,BASE) != 0) $dir = BASE.$dir;
	$dh = opendir($dir);
	if ($dh) {
		while (($file = readdir($dh)) !== false) {
			if ($file != "." && $file != ".." && is_dir("$dir/$file")) {
				if (pathos_files_removeDirectory("$dir/$file") == SYS_FILES_NOTDELETABLE) return SYS_FILES_NOTDELETABLE;
			} else if (is_file("$dir/$file") || is_link(is_file("$dir/$file"))) {
				unlink("$dir/$file");
				if (file_exists("$dir/$file")) {
					return SYS_FILES_NOTDELETABLE;
				}
			}
			else if ($file != "." && $file != "..") {
				echo "BAD STUFF HAPPENED<br />";
				echo "--------Don't know what to do with $dir/$file<br />";
				echo "<xmp>";
				print_r(stat("$dir/$file"));
				echo filetype("$dir/$file");
				echo "</xmp>";
			}
		}
	}
	rmdir($dir);
}

/**
 * Check Existence of Upload Destination File
 *
 * Checks to see if the upload destination file exists.  This is to prevent
 * accidentally uploading over the top of another file.
 *
 * @param string $dir The directory to contain the existing directory.
 * @param string $name The name of the file control used to upload the
 *  file.  The files subsystem will look to the $_FILES array
 *  to get the filename of the uploaded file.
 * @return boolean True if the file already exists, and false if it does not.
 */
function pathos_files_uploadDestinationFileExists($dir,$name) {
	return (file_exists(BASE.$dir."/".$_FILES[$name]['name']));
}

function pathos_files_moveUploadedFile($tmp_name,$dest) {
	move_uploaded_file($tmp_name,$dest);
	if (file_exists($dest)) {
		$__oldumask = umask(0);
		chmod($dest,FILE_DEFAULT_MODE);
		umask($__oldumask);
	}
}

/**
 * List Files, in a Recursive Array
 *
 * Lists files and directories under a given parent directory.
 *
 * @param string $dir The path of the directory to look at.
 * @param boolean $recurse A boolean dictating whether to descend into subdirectories
 * 	recursviely, and list files and subdirectories.
 * @param string $ext An optional file extension.  If specified, only files ending with
 * 	that file extension will show up in the list.  Directories are not affected.
 * @param array $exclude_dirs An array of directory names to exclude.  These names are
 * 	path-independent.  Specifying "dir" will ignore all directories and
 * 	sub-directories named "dir", regardless of their parent.
 * @return array An associative, recursive array of files and directories.  The key is
 * 	the file or directory name.  In the case of files, the value if the file name.  In
 * 	the case of directories, the value if an array of the files / directories in that
 * 	directory.
 */
function pathos_files_list($dir, $recurse = false, $ext=null, $exclude_dirs = array()) {
	$files = array();
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if (is_dir("$dir/$file") && !in_array($file,$exclude_dirs) && $recurse && $file != "." && $file != ".." && $file != "CVS") {
				$files[$file] = pathos_files_list("$dir/$file",$recurse,$ext,$exclude_dirs);
			}
			if (is_file("$dir/$file") && ($ext == null || substr($file,-1*strlen($ext),strlen($ext)) == $ext)) {
				$files[$file] = $file;
			}
		}
	}
	return $files;
}

/**
 * List Files, in a Flat Array
 *
 * Lists files and directories under a given parent directory.
 *
 * @param string $dir The path of the directory to look at.
 * @param boolean $recurse A boolean dictating whether to descend into subdirectories
 * 	recursviely, and list files and subdirectories.
 * @param string $ext An optional file extension.  If specified, only files ending with
 * 	that file extension will show up in the list.  Directories are not affected.
 * @param array $exclude_dirs An array of directory names to exclude.  These names are
 * 	path-independent.  Specifying "dir" will ignore all directories and
 * 	sub-directories named "dir", regardless of their parent.
 * @return array An associative, flat array of files and directories.  The key is
 * 	the full file or directory name, and the value is the file or directory name.
 */
function pathos_files_listFlat($dir, $recurse = false, $ext=null, $exclude_dirs = array(), $relative = "") {
	$files = array();
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if (is_dir("$dir/$file") && !in_array($file,$exclude_dirs) && $recurse && $file != "." && $file != ".." && $file != "CVS") {
				$files = array_merge($files,pathos_files_listFlat("$dir/$file",$recurse,$ext,$exclude_dirs,$relative));
			}
			if (is_file("$dir/$file") && ($ext == null || substr($file,-1*strlen($ext),strlen($ext)) == $ext)) {
				$files[str_replace($relative,"","$dir/$file")] = $file;
			}
		}
	}
	return $files;
}

/**
 * Copy Directory Structure
 *
 * Copies just the directory structure (including subdirectories) of a given directory.
 * Any files in the source directory are ignore, and duplicate copies are made (no symlinks).
 *
 * @param string $src The directory to copy structure from.  This must be a full path.
 * @param string $dest The directory to create duplicate structure in.  If this directory is not empty,
 * 	you may run into some problems, because of file/directory conflicts.
 * @param $exclude_dirs An array of directory names to exclude.  These names are
 * 	path-independent.  Specifying "dir" will ignore all directories and
 * 	sub-directories named "dir", regardless of their parent.
 */
function pathos_files_copyDirectoryStructure($src,$dest,$exclude_dirs = array()) {
	$__oldumask = umask(0);
	if (!file_exists($dest)) mkdir($dest,fileperms($src));
	$dh = opendir($src);
	while (($file = readdir($dh)) !== false) {
		if (is_dir("$src/$file") && !in_array($file,$exclude_dirs) && substr($file,0,1) != "." && $file != "CVS") {
			if (!file_exists("$dest/$file")) mkdir("$dest/$file",fileperms("$src/$file"));
			if (is_dir("$dest/$file")) {
				pathos_files_copyDirectoryStructure("$src/$file","$dest/$file");
			}
		}
	}
	umask($__oldumask);
}

/**
 * Check if a file / directory can be created
 *
 * Looks at the filesystem strucutre surrounding the destination
 * and determines if the web server can create a new file there.
 *
 * @param string $dest Path to the directory to check
 *
 * @return constant One of the following:
 *	<br>SYS_FILES_NOTWRITABLE - unable to create files in destination
 *	<br>SYS_FILES_SUCCESS - A file or directory can be created in destination
 *	<br>SYS_FILES_FOUNDFILE - Found destination to be a file, not a directory
 */
function pathos_files_canCreate($dest) {
	if (substr($dest,0,1) != "/") $dest = BASE.$dest;
	$parts = explode("/",$dest);
	$working = "";
	for ($i = 0; $i < count($parts); $i++) {
		if ($parts[$i] != "") {
			$working .= "/";
			if (!file_exists($working.$parts[$i])) {
				return (is_writable($working) ? SYS_FILES_SUCCESS : SYS_FILES_NOTWRITABLE);
			}
			$working .= $parts[$i];
		}
	}
	if (!is_writable($working)) return SYS_FILES_NOTWRITABLE;
	else {
		if (is_file($working)) return SYS_FILES_FOUNDFILE;
		else return SYS_FILES_NOTWRITABLE;
	}
}

?>
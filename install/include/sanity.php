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

function sanity_checkConfigFile() {
	$__oldumask = umask(0);
	global $warnings, $errors;
	
	$conffile = BASE."conf/config.php";
	
	if (!file_exists($conffile)) {
		@touch($conffile);
	}
	if (!is_readable($conffile)) {
		@chmod($conffile,0777);
		if (!is_readable($conffile)) {
			$errors[] = "Configuration file (conf/config.php) is not readable.";
		}
	} else {
		if (!is_writable($conffile)) {
			@chmod($conffile);
			if (!is_writable($conffile)) {
				$errors[] = "Configuration file (conf/config.php) is not writable.";
			}
		}
	}
	
	$profiledir = BASE."conf/profiles";
	if (!file_exists($profiledir)) {
		@mkdir($profiledir,0777);
	}
	if (!is_writable($profiledir) || !is_readable($profiledir)) {
		@chmod($profiledir,0777);
		if (!is_readable($profiledir)) {
			$errors[] = "Configuration profile directory (conf/profiles/) is not readable.";
		}
		if (!is_writable($profiledir)) {
			$errors[] = "Configuration profile directory (conf/profiles/) is not writable.";
		}
	}
	umask($__oldumask);
}

function sanity_checkModules() {
	global $warnings, $errors;
	$__oldumask = umask(0);
	
	if (is_readable(BASE."modules")) {
		$modules = BASE."modules/";
		$dh = opendir($modules);
		while (($moddir = readdir($dh)) !== false) {
			if (is_dir($modules.$moddir) && substr($moddir,0,1) != "." && file_exists($modules.$moddir."/views")) {
				// Got a module.
				if (!is_readable($modules.$moddir."/class.php") && substr($moddir,-6,6) == "module") {
					$warnings[] = "Module in $moddir will not be usable, as the module file is not readable.";
				}
				
				if (file_exists($modules.$moddir."/views") && !file_exists($modules.$moddir."/views_c")) {
					@mkdir($modules.$moddir."/views_c",0777);
					if (!file_exists($modules.$moddir."/views_c")) {
						$errors[] = "The view compilation directory (modules/$moddir/views_c) for $moddir does not exist.";
						continue;
					}
				}
				
				if (file_exists($modules.$moddir."/views_c") && !is_writable($modules.$moddir."/views_c")) {
					@chmod($modules.$moddir."/views_c");
					if (!is_writable($modules.$moddir."/views_c")) {
						$errors[] = "The view compilation directory (modules/$moddir/views_c) for $moddir is not writable by the web server";
						continue;
					}
				}
			}
		}
	} else $errors[] = "Modules directory (modules/) is not readable";
	umask($__oldumask);
}

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
					if (file_exists($themebase."/".$themedir."/views")) {
						if (!file_exists($themebase."/".$themedir."/views_c")) {
							@mkdir($themebase."/".$themedir."/views_c");
						}
						
						if (!file_exists($themebase."/".$themedir."/views_c")) {
							$errors[] = "Theme root view compilation directory (themes/$themedir/views_c) does not exist.";
						} else {
							@chmod($themebase."/".$themedir."/views_c",0777);
							if (!is_readable($themebase."/".$themedir."/views_c")) {
								$errors[] = "Theme root view compilation directory (themes/$themedir/views_c) is not readable.";
							}
							if (!is_writable($themebase."/".$themedir."/views_c")) {
								$errors[] = "Theme root view compilation directory (themes/$themedir/views_c) is not writable.";
							}
						}
					}
					$one_readable = true;
					if (is_readable($themebase."/".$themedir."/modules")) {
						$moddh = opendir($themebase."/".$themedir."/modules");
						while (($moddir = readdir($moddh)) !== false) {
							if ($moddir == "CVS") continue;
							if (is_dir($themebase."/".$themedir."/modules/".$moddir) && substr($moddir,0,1) != ".") {
								$tmpbase = $themebase."/".$themedir."/modules/".$moddir;
								if (!file_exists($tmpbase."/views_c")) {
									@mkdir($tmpbase."/views_c",0777);
								}
								if (!is_readable($tmpbase."/views_c") || !is_writable($tmpbase."/views_c")) {
									@chmod($tmpbase."/views_c",0777);
									if (!file_exists($tmpbase."/views_c")) {
										$errors[] = "The view compilation directory (themes/$themedir/modules/$moddir/views_c) does not exist";
									} else {
										if (!is_readable($tmpbase."/views_c")) {
											$errors[] = "The view compilation directory (themes/$themedir/modules/$moddir/views_c) is not readable";
										}
										if (!is_writable($tmpbase."/views_c")) {
											$errors[] = "The view compilation directory (themes/$themedir/modules/$moddir/views_c) is not writable";
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if (!$one_readable) {
			$errors[] = "No theme directories in themes/ are readable.";
		}
	} else $errors[] = "Themes directory (themes/) is not readable.";
	
	umask($__oldumask);
}

function sanity_CheckSite() {
	global $warnings, $errors;
	$__oldumask = umask(0);
	
	if (!file_exists(BASE."views_c")) {
		@mkdir(BASE."views_c",0777);
	}
	
	if (!file_exists(BASE."views_c")) {
		$errors[] = "The root view compilation directory (views_c) does not exist";
	} else {
		if (!is_readable(BASE."views_c") || !is_writable(BASE."views_c")) {
			@chmod(BASE."views_c",0777);
		}
		
		if (!is_readable(BASE."views_c")) {
			$errors[] = "The root view compilation directory (views_c) is not readable";
		}
		if (!is_writable(BASE."views_c")) {
			$errors[] = "The root view compilation directory (views_c) is not writable";
		}
	}
	
	if (!file_exists(BASE."extensionuploads")) {
		@mkdir(BASE."extensionuploads");
	}
	
	if (!file_exists(BASE."extensionuploads")) {
		$errors[] = "The extension upload directory (extensionuploads) does not exist";
	} else {
		if (!is_readable(BASE."extensionuploads") || !is_writable(BASE."extensionuploads")) {
			@chmod(BASE."extensionuploads",0777);
		}
		
		if (!is_readable(BASE."extensionuploads")) {
			$errors[] = "The extension upload directory (extensionuploads) is not readable";
		}
		if (!is_writable(BASE."extensionuploads")) {
			$errors[] = "The extension upload directory (extensionuploads) is not writable";
		}
	}
	
	umask($__oldumask);
}

?>


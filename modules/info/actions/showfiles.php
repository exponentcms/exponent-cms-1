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

if (!defined("PATHOS")) exit("");

if ($user && $user->is_admin) {
	if (!defined("SYS_INFO")) include_once(BASE."subsystems/info.php");
	$files = pathos_info_files($_GET['type']+0,$_GET['name']);
	if (is_array($files)) ksort($files);
	
	$template = new template("info","_checksums",$loc);

	if (is_array($files)) {
		$actual = pathos_info_fileChecksums($files);
		foreach (array_keys($files) as $f) if (is_integer($files[$f])) $files[$f] = "";
		$relative = array();
		foreach (array_keys($files) as $file) {
			$relative[$file] = array(
				"dir"=>str_replace(array(BASE," "),array("","&nbsp;"),dirname($file)."/"),
				"file"=>str_replace(" ","&nbsp;",basename($file))
			);
		}
		foreach (array_keys($files) as $f) {
			if (!is_string($files[$f])) $files[$f] = "";
		}
		$template->assign("files",$files);
		$template->assign("checksums",$actual);
		$template->assign("relative",$relative);
	} else {
		$template->assign("error",$files);
	}
	$template->output();
}

?>

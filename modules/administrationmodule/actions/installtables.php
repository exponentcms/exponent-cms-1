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

define("TMP_TABLE_EXISTED",		1);
define("TMP_TABLE_INSTALLED",	2);
define("TMP_TABLE_FAILED",		3);
define("TMP_TABLE_ALTERED",		4);

$dir = BASE."datatypes/definitions";
if (is_readable($dir)) {
	$tables = array();
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		if (is_readable("$dir/$file") && is_file("$dir/$file") && substr($file,-4,4) == ".php" && substr($file,-9,9) != ".info.php") {
			$tablename = substr($file,0,-4);
			$dd = include("$dir/$file");
			$info = null;
			if (is_readable("$dir/$tablename.info.php")) $info = include("$dir/$tablename.info.php");
			if (!$db->tableExists($tablename)) {
				$db->createTable($tablename,$dd,$info);
				if ($db->tableExists($tablename)) {
					$tables[$tablename] = TMP_TABLE_INSTALLED;
				} else {
					$tables[$tablename] = TMP_TABLE_FAILED;
				}
			} else {
				if ($db->alterTable($tablename,$dd,$info) == TABLE_ALTER_NOT_NEEDED) {
					$tables[$tablename] = TMP_TABLE_EXISTED;
				} else {
					$tables[$tablename] = TMP_TABLE_ALTERED;
				}
			}
		}
	}
	ksort($tables);
	
	$template = new Template("administrationmodule","_tableInstallSummary",$loc);
	$template->assign("status",$tables);
	$template->output();
}


?>
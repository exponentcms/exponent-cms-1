<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

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
				foreach ($db->createTable($tablename,$dd,$info) as $key=>$status) {
					$tables[$key] = $status;
				}
			} else {
				foreach ($db->alterTable($tablename,$dd,$info) as $key=>$status) {
					$tables[$key] = ($status == TABLE_ALTER_NOT_NEEDED ? DATABASE_TABLE_EXISTED : DATABASE_TABLE_ALTERED);
				}
			}
		}
	}
	ksort($tables);
	
	$template = new template("administrationmodule","_tableInstallSummary",$loc);
	$template->assign("status",$tables);
	$template->output();
}


?>
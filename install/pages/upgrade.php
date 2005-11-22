<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

if (!defined('PATHOS')) exit('');

$errors = array();

if (PATHOS != $_POST['from_version']) {
	// User wants to upgrade from a previous major or minor version (i.e. 0.95 to 0.96, or 0.96 to 1.0)
	if (!defined('SYS_BACKUP')) include_once(BASE.'subsystems/backup.php');
	$eql = pathos_backup_dumpDatabase($db,null,$_POST['from_version']);
	
	$tempnam = tempnam(BASE.'tmp','eql');
	$fh = fopen($tempnam,'w');
	fwrite($fh,$eql);
	fclose($fh);

	pathos_backup_restoreDatabase($db,$tempnam,$errors,$_POST['from_version']);
	
	unlink($tempnam);
} else {
	// User wants to upgrade from a potentially different revision of the major and minor version (0.96.2 to 0.96.3)
	// This means we need to go through and essentially run Install Tables for them (to alter tables minorly)
	$dir = BASE.'datatypes/definitions';
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if (is_readable($dir.'/'.$file) && is_file($dir.'/'.$file) && substr($file,-4,4) == '.php' && substr($file,-9,9) != '.info.php') {
				$tablename = substr($file,0,-4);
				$dd = include($dir.'/'.$file);
				$info = array();
				if (is_readable($dir.'/'.$tablename.'.info.php')) $info = include($dir.'/'.$tablename.'.info.php');
				if (!$db->tableExists($tablename)) {
					$db->createTable($tablename,$dd,$info);
				} else {
					$db->alterTable($tablename,$dd,$info);
				}
			}
		}
	}
}

$i18n = pathos_lang_loadFile('install/pages/upgrade.php');

if (count($errors)) {
	echo $i18n['errors'].'<br /><br />';
	foreach ($errors as $e) echo $e . '<br />';
} else {
	echo $i18n['success'];;
	echo '<br /><br /><a href="?page=final">'.$i18n['complete'].'</a>.';
}

?>
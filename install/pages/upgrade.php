<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

if (!defined('EXPONENT')) exit('');

$errors = array();

//include_once(BASE . 'modules/administrationmodule/actions/installtables.php');

/* This code is copied from installtables.php in the admin module, which has permissions.
* Be sure to copy any changes there if modifying this code */
	
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
						if ($status == TABLE_ALTER_FAILED){
							$tables[$key] = $status;
						}else{
							$tables[$key] = ($status == TABLE_ALTER_NOT_NEEDED ? DATABASE_TABLE_EXISTED : DATABASE_TABLE_ALTERED);						
						}
						
					}
				}
			} 
		}
		ksort($tables);
		
		$template = new template("administrationmodule","_tableInstallSummary",$loc);
		$template->assign("status",$tables);	
		$template->output();
	}
//end copy from installtable.php

//Run the upgrade scripts
$upgrade_dir = 'upgrades/'.$_POST['from_version'];
if (is_readable($upgrade_dir)) {
	$dh = opendir($upgrade_dir);
        while (($file = readdir($dh)) !== false) {
        	if (is_readable($upgrade_dir.'/'.$file) && ($file != '.' && $file != '..' && $file != '.svn') ) {
                	include_once($upgrade_dir.'/'.$file);
                }
       	}
}
	
$i18n = exponent_lang_loadFile('install/pages/upgrade.php');

if (count($errors)) {
	echo $i18n['errors'].'<br /><br /><br />';
	foreach ($errors as $e) echo $e . '<br />';
} else {
	echo $i18n['success'];;
	echo '<br /><br /><a href="?page=final">'.$i18n['complete'].'</a>.';
}

?>
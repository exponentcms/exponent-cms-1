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
//GREP:HARDCODEDTEXT
if (!defined("PATHOS")) exit("");

if ($user && $user->is_admin) {
	if (!defined("SYS_CONFIG")) include_once(BASE."subsystems/config.php");
	
	$continue = true;
	$errors = "";
	
	// Test the prefix
	if (preg_match("/[^A-Za-z0-9]/",$_POST['c']['DB_TABLE_PREFIX'])) {
		$continue = false;
		$errors .= "Invalid table prefix.  The table prefix can only contain alphanumeric characters.";
	}
	
	// Test the database connection
	$newdb = pathos_database_connect($_POST['c']['DB_USER'],$_POST['c']['DB_PASS'],$_POST['c']['DB_HOST'].":".$_POST['c']['DB_PORT'],$_POST['c']['DB_NAME'],$_POST['c']['DB_ENGINE']);
	$newdb->prefix = $_POST['c']['DB_TABLE_PREFIX'] . "_";
	
	if (!$newdb->isValid()) {
		$continue = false;
		$errors .= "Unable to connect to database server.  Make sure that the database specified exists, and the user account specified has access to the server.<br />";
	}
	
	if ($continue) {
		$status = $newdb->testPrivileges();
		foreach ($status as $type=>$flag) {
			if (!$flag) {
				$continue = false;
				$errors .= "Unable to run $type commands<br />";
			}
		}
	}
	
	if ($continue) {
		pathos_config_saveConfiguration($_POST);
		$db = $newdb;
		ob_start();
		include_once(BASE."modules/administrationmodule/actions/installtables.php");
		$ob = ob_get_contents();
		ob_end_clean();
		if ($db->tableIsEmpty("user")) {
			$user = null;
			$user->username = "admin";
			$user->password = md5("admin");
			$user->is_admin = 1;
			$db->insertObject($user,"user");
		}
		
		if ($db->tableIsEmpty("modstate")) {
			$modstate = null;
			$modstate->module = "administrationmodule";
			$modstate->active = 1;
			$db->insertObject($modstate,"modstate");
		}
		
		if ($db->tableIsEmpty("section")) {
			$section = null;
			$section->name = "Home";
			$section->public = 1;
			$section->active = 1;
			$section->rank = 0;
			$section->parent = 0;
			$sid = $db->insertObject($section,"section");
		}
		
		echo "<br /><br />Configuration Saved!  Click <a class='mngmntlink' href='".pathos_flow_get()."'>here</a> to continue.<br /><hr size='1' />";
		echo $ob;
	} else {
		echo "<div style='color: #FF0000; font-weight: bold;'>Errors were encountered with your database connection settings:</div>";
		echo "<div style='padding-left: 15px;'>";
		echo $errors;
		echo "<br /><br />Site configuration was <b>not</b> saved.  Click <a class='mngmntlink' href='".$_SERVER['HTTP_REFERER']."'>here</a> to go back and reconfigure.<br /><br />";
		echo "</div>";
	}
} else {
	echo SITE_403_HTML;
}

?>
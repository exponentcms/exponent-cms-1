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

?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Checking Database Configuration
</div>
<br /><br />
The Exponent Install Wizard will now check to make sure that the database configuration information you provided is valid.
<br /><br />
<table>
<?php

function echoStart($msg) {
	echo '<tr><td valign="top">'.$msg.'</td><td valign="top">';
}

function echoSuccess($msg = "") {
	echo '<span class="success">Succeeded</span>';
	if ($msg != "") echo " : $msg";
	echo '</td></tr>';
}

function echoFailure($msg = "") {
	echo '<span class="failed">Failed</span>';
	if ($msg != "") echo " : $msg";
	echo '</td></tr>';
}

function isAllGood($str) {
	return !preg_match("/[^A-Za-z0-9_]/",$str);
}

pathos_sessions_set("installer_config",$_POST['c']);
$config = $_POST['c'];

$passed = true;

if (!isAllGood($config["db_table_prefix"])) {
	echoFailure("Invalid table prefix.  The table prefix can only contain alphanumeric characters and underscores ('_').");
	$passed = false;
}

if ($passed) {
	$db = pathos_database_connect($config['db_user'],$config['db_pass'],$config['db_host'],$config['db_name']);
	$db->prefix = $config['db_table_prefix'];
	
	$status = array();
	
	echoStart("Connecting to database:");

	if ($db->connection == null) {
		echoFailure($db->error());
		// BETTER ERROR CHECKING
		$passed = false;
	}
}

if ($passed) {
	$tables = $db->getTables();
	if ($db->inError()) {
		echoFailure($db->error());
		$passed = false;
	} else {
		echoSuccess();
	}
}

$tablename = "installer_test".time(); // Used for other things

$dd = array(
	"id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	"installer_test"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100)
);

if ($passed) {
	$db->createTable($tablename,$dd,array());
	
	echoStart("Checking CREATE TABLE privilege:");
	if ($db->tableExists($tablename)) {
		echoSuccess();
	} else {
		echoFailure();
		$passed = false;
	}
}

$insert_id = null;
$obj = null;

if ($passed) {
	echoStart("Checking INSERT privilege:");
	$obj->installer_test = "Exponent Installer Wizard";
	$insert_id = $db->insertObject($obj,$tablename);
	if ($insert_id == 0) {
		$passed = false;
		echoFailure($db->error());
	} else {
		echoSuccess();
	}
}

if ($passed) {
	echoStart("Checking SELECT privilege:");
	$obj = $db->selectObject($tablename,"id=".$insert_id);
	if ($obj == null || $obj->installer_test != "Exponent Installer Wizard") {
		$passed = false;
		echoFailure($db->error());
	} else {
		echoSuccess();
	}
}

if ($passed) {
	echoStart("Checking UPDATE privilege:");
	$obj->installer_test = "Exponent 2";
	if (!$db->updateObject($obj,$tablename)) {
		$passed = false;
		echoFailure($db->error());
	} else {
		echoSuccess();
	}
}

if ($passed) {
	echoStart("Checking DELETE privilege:");
	$db->delete($tablename,"id=".$insert_id);
	$error = $db->error();
	$obj = $db->selectObject($tablename,"id=".$insert_id);
	if ($obj != null) {
		$passed = false;
		echoFailure($error);
	} else {
		echoSuccess();
	}
}

if ($passed) {
	$dd["exponent"] = array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>8);
	
	echoStart("Checking ALTER TABLE privilege:");
	$db->alterTable($tablename,$dd,array());
	$error = $db->error();
	
	$obj = null;
	$obj->installer_test = "Exponent Installer ALTER test";
	$obj->exponent = "Exponent";
	
	if (!$db->insertObject($obj,$tablename)) {
		$passed = false;
		echoFailure($error);
	} else {
		echoSuccess();
	}
}


if ($passed) {
	echoStart("Checking DROP TABLE privilege:");
	$db->dropTable($tablename);
	$error = $db->error();
	if ($db->tableExists($tablename)) {
		$passed = false;
		echoFailure($error);
	} else {
		echoSuccess();
	}
}

if ($passed) {
	echoStart("Installing Tables:");
	
	$dir = BASE."datatypes/definitions";
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if (is_readable("$dir/$file") && is_file("$dir/$file") && substr($file,-4,4) == ".php" && substr($file,-9,9) != ".info.php") {
				$tablename = substr($file,0,-4);
				$dd = include("$dir/$file");
				$info = array();
				if (is_readable("$dir/$tablename.info.php")) $info = include("$dir/$tablename.info.php");
				if (!$db->tableExists($tablename)) {
					$db->createTable($tablename,$dd,$info);
				} else {
					$db->alterTable($tablename,$dd,$info);
				}
			}
		}
	}
	
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
	
	echoSuccess();
}

if ($passed) {
	echoStart("Saving Configuration");
	
	$values = array(
		"c"=>$config,
		"opts"=>array(),
		"configname"=>"Default",
		"activate"=>1
	);
	
	if (!defined("SYS_CONFIG")) include_once(BASE."subsystems/config.php");
	pathos_config_saveConfiguration($values);
	// ERROR CHECKING
	echoSuccess();
}

?>
</table>
<?php

if ($passed) {
	// Do some final cleanup
	foreach ($db->getTables() as $t) {
		// FIX table prefix problem
		if (substr($t,0,14+strlen($db->prefix)) == $db->prefix."installer_test") {
			$db->dropTable(str_replace($db->prefix,"",$t));
		}
	}
	?>
	<br /><br />Database tests passed.  The installer will now populate the database with default content.  This may take a few minutes.
	<br /><br />To continue, click <a href="?page=tmp_create_site">here</a>
	
	<br /><br />To skip the creation of default content, and start with a clean database, click <a href="?page=final">here</a>
	<?php
} else {
	?>
	<br /><br />Click <a href="?page=dbconfig" onClick="history.go(-1); return false;">here</a> to edit your settings after you have corrected whatever problems arise.
	<?php
}
//GREP:HARDCODEDTEXT
?>

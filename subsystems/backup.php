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
/**
 * Backup Subsystem
 *
 * The backup subsystem provides general backup
 * functionality.  Currently only entire database
 * backups are supported.
 *
 * Any sort of export or import of important website
 * data should be handled through this subsytem
 *
 *
 * @package		Subsystems
 * @subpackage	Backup
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS Flag for Backup Subsystem.
 * The definition of this constant lets other parts
 * of the system know that the Backup Subsystem
 * has been included for use.
 */
define("SYS_BACKUP",1);

/**
 * The EQL header string for object dump file formats.
 * This header defines the version of EQL native to
 * the current implementation of the Backup Subsystem
 */
define("EQL_HEADER","EQL-Exponent Query Language");

/**
 * Dump Objects from an entire Database
 *
 * This function takes a database object and dumps
 * all of the records in all of the tables into a string.
 * The contents of the string are suitable for storage
 * in a file or other permanent mechanism, and is in
 * the EQL format natively handled by the current
 * implementation.
 *
 * @param Database $db The database object to dump to EQL.
 *
 * @return string A snapshot of the database in EQL.
 */
function pathos_backup_dumpDatabase($db,$tables = null) {
	$dump = EQL_HEADER."\n";
	$dump .= "VERSION:".PATHOS."\n\n";
	if ($tables == null) {
		$tables = $db->getTables();
		if (!function_exists("tmp_removePrefix")) {
			function tmp_removePrefix($tbl) {
				return substr($tbl,strlen(DB_TABLE_PREFIX)+1);
				// we add 1, because DB_TABLE_PREFIX  no longer has the trailing
				// '_' character - that is automatically added by the database class.
			}
		}
		$tables = array_map("tmp_removePrefix",$tables);
	}
	usort($tables,"strnatcmp");
	foreach ($tables as $table) {
		$dump .= "TABLE:"."$table\n";
		foreach ($db->selectObjects($table) as $obj) {
			$dump .= "RECORD:".str_replace("\r\n","\\r\\n",serialize($obj))."\n";
		}
		$dump .= "\n";
	}
	return $dump;
}

/**
 * Restore an entire Database
 *
 * This function restores a database (overwriting all data in
 * any existing tables) from an EQL object dump.
 *
 * @param Database $db The database to restore to
 * @param string $file The filename of the EQL file to restore from
 * @param array $errors A referenced array that stores errors.  Whatever
 * 	variable is passed in this argument will contain all errors encounterd
 *	during the parse/restore.
 * @param boolean $verbose Whether or not to output verbose progress information.
 *
 * @return boolean true if the restore was a success and false if
 *	something went horribly wrong (unable to read file, etc.)  Even if true is
 *	returned, there is a chance that some errors were encountered.  Check $errors
 *	to be sure everything was fine.
 */
function pathos_backup_restoreDatabase($db,$file,&$errors,$verbose = 0) {
	$errors = array();
	
	if (is_readable($file)) {
		$lines = @file($file);
		
		// Sanity check
		if (count($lines) < 2 || trim($lines[0]) != EQL_HEADER) {
			$errors[] = "Not a valid EQL file";
			return false;
		}
		
		$version = explode(":",trim($lines[1]));
		$version = $version[1];
		
		if ($verbose) echo "EQL contains a database for version $version of Exponent<br />";
		
		$table = "";
		for ($i = 2; $i < count($lines); $i++) {
			$line_number = $i;
			$line = trim($lines[$i]);
			if ($line != "") {
				$pair = explode(":",$line);
				$pair[1] = implode(":",array_slice($pair,1));
				$pair = array_slice($pair,0,2);
				
				if ($pair[0] == "TABLE") {
					$table = $pair[1];
					if ($db->tableExists($table)) {
						if ($verbose) echo "Clearing table $table<br />";
						$db->delete($table);
					} else {
						if (!file_exists(BASE."datatypes/definitions/$table.php")) {
							$errors[] = "Table $table not found in system (line $line_number)";
						} else if (!is_readable(BASE."datatypes/definitions/$table.php")) {
							$errors[] = "Data definition file for $table (datatypes/definitsion/$table.php) is not readable (line $line_number)";
						} else {
							$dd = include(BASE."datatypes/definitions/$table.php");
							$info = (is_readable(BASE."datatypes/definitions/$table.info.php") ? include(BASE."datatypes/definitions/$table.info.php") : array());
							$db->createTable($table,$dd,$info);
						}
					}
				} else if ($pair[0] == "RECORD") {
					$pair[1] = str_replace("\\r\\n","\r\n",$pair[1]);
					$object = unserialize($pair[1]);
					if ($verbose) echo "&nbsp;&nbsp;&nbsp;Inserting record into $table<br />";
					if ($verbose > 1) {
						echo "<xmp>Object Dump\n";
						print_r($object);
						echo "</xmp>";
					}
					#echo "db->insertObject($table,\$object);<br />";
					$db->insertObject($object,$table);
					if ($verbose > 1) echo pg_last_error();
				} else {
					$errors[] = "Invalid type on line $line_number";
				}
			}
		}
		return true;
	} else {
		$errors[] = "Unable to read EQL file";
		return false;
	}
}

?>
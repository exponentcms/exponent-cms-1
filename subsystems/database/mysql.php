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

/* exdoc
 * Database Class (MySQL)
 *
 * This is the MySQL-specific implementation of the database class.
 *
 * @node Subsystems:Database:MySQL
 */
class mysql_database {
	var $connection = null;
	var $havedb = false;
	var $prefix = "";
	
	/* exdoc
	 * Make a connection to the Database Server
	 *
	 * Takes the supplied credentials (username / password) and tries to
	 * connect to the server and select the given database.  All the rules
	 * governing mysql_connect also govern this method.
	 *
	 * This must be called before any other methods of database are invoked.
	 *
	 * @param string $username The username to connect to the server as.
	 * @param string $password The password for $username
	 * @param string $hostname The hostname of the database server.  If
	 *   localhost is specified, a local socket connection will be attempted.
	 * @param string $database The name of the database to use.  Multi-database
	 *   sites are still not yet supported.
	 */
	function connect($username,$password,$hostname,$database,$new=false) {
		$this->connection = @mysql_connect($hostname,$username,$password,$new);
		if ($this->connection) {
			$this->havedb = (mysql_select_db($database,$this->connection) ? true : false);
		}
		$this->prefix = DB_TABLE_PREFIX.'_';
	}
	
	/* exdoc
	 * Create a new Table
	 *
	 * Creates a new database table, according to the passed data definition.
	 *
	 * This function abides by the Exponent Data Definition Language, and interprets
	 * its general structure for MySQL.
	 *
	 * @param string $tablename The name of the table to create
	 * @param array $datadef The data definition to create, expressed in
	 *   the Exponent Data Definition Language.
	 * @param array $info Information about the table itself.
	 */
	function createTable($tablename,$datadef,$info) {
		if (!is_array($info)) $info = array(); // Initialize for later use.
	
		$sql = "CREATE TABLE `" . $this->prefix . "$tablename` (";
		$primary = array();
		$unique = array();
		$index = array();
		foreach ($datadef as $name=>$def) {
			if ($def != null) {
				$sql .= $this->fieldSQL($name,$def) . ",";
				if (isset($def[DB_PRIMARY]) && $def[DB_PRIMARY] == true) $primary[] = $name;
				if (isset($def[DB_INDEX]) && ($def[DB_INDEX] > 0)) {
					if ($def[DB_FIELD_TYPE] == DB_DEF_STRING) {
						$index[$name] = $def[DB_INDEX];
					}
					else {
						$index[$name] = 0;
					}
				}
				if (isset($def[DB_UNIQUE])) {
					if (!isset($unique[$def[DB_UNIQUE]])) $unique[$def[DB_UNIQUE]] = array();
					$unique[$def[DB_UNIQUE]][] = $name;
				}
			}
		}
		$sql = substr($sql,0,-1);
		if (count($primary)) {
			$sql .= ", PRIMARY KEY(`" . join("`,`",$primary) . "`)";
		}
		foreach ($unique as $key=>$value) {
			$sql .= ", UNIQUE `".$key."` ( `" . join("`,`",$value) . "`)";
		}
		foreach ($index as $key=>$value) {
			$sql .= ", INDEX (`" . $key . "`" . (($value > 0)?"(".$value.")":"") . ")";
		}
		$sql .= ")";
		if (isset($info[DB_TABLE_COMMENT])) {
			$sql .= " COMMENT = '" . $info[DB_TABLE_COMMENT] . "'";
		}
		@mysql_query($sql,$this->connection);
		
		if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
			// Initialize workflow tables:
			if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
			pathos_workflow_installWorkflowTables($tablename,$datadef);
		}
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function fieldSQL($name,$def) {
		$sql = "`$name`";
		if (!isset($def[DB_FIELD_TYPE])) {
			return false;
		}
		$type = $def[DB_FIELD_TYPE];
		if ($type == DB_DEF_ID) {
			$sql .= " INT(11)";
		} else if ($type == DB_DEF_BOOLEAN) {
			$sql .= " TINYINT(1)";
		} else if ($type == DB_DEF_TIMESTAMP) {
			$sql .= " INT(14)";
		} else if ($type == DB_DEF_INTEGER) {
			$sql .= " INT(8)";
		} else if ($type == DB_DEF_STRING) {
			if (isset($def[DB_FIELD_LEN]) && is_integer($def[DB_FIELD_LEN])) {
				$len = $def[DB_FIELD_LEN];
				if ($len < 256) $sql .= " VARCHAR($len)";
				else if ($len < 65536) $sql .= " TEXT";
				else if ($len < 16777216) $sql .= " MEDIUMTEXT";
				else $sql .= "LONGTEXT";
			} else {
				return false; // must specify a field length as integer.
			}
		} else if ($type == DB_DEF_DECIMAL) {
			$sql .= " DOUBLE";
		} else {
			return false; // must specify known FIELD_TYPE
		}
		$sql .= " NOT NULL";
		if (isset($def[DB_DEFAULT])) $sql .= " DEFAULT '" . $def[DB_DEFAULT] . "'";
		
		if (isset($def[DB_INCREMENT]) && $def[DB_INCREMENT]) $sql .= " AUTO_INCREMENT";
		return $sql;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function isValid() {
		return ($this->connection != null && $this->havedb);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function testPrivileges() {
		$status = array();
		
		$tablename = "___testertable".uniqid("");
		$dd = array(
			"id"=>array(
				DB_FIELD_TYPE=>DB_DEF_ID,
				DB_PRIMARY=>true,
				DB_INCREMENT=>true),
			"name"=>array(
				DB_FIELD_TYPE=>DB_DEF_STRING,
				DB_FIELD_LEN=>100)
		);
		
		$this->createTable($tablename,$dd,array());
		if (!$this->tableExists($tablename)) {
			$status["CREATE TABLE"] = false;
			return $status;
		} else $status["CREATE TABLE"] = true;
		
		$o = null;
		$o->name = "Testing Name";
		$insert_id = $this->insertObject($o,$tablename);
		if ($insert_id == 0) {
			$status["INSERT"] = false;
			return $status;
		} else $status["INSERT"] = true;
		
		$o = $this->selectObject($tablename,"id=".$insert_id);
		if ($o == null || $o->name != "Testing Name") {
			$status["SELECT"] = false;
			return $status;
		} else $status["SELECT"] = true;
		
		$o->name = "Testing 2";
		if (!$this->updateObject($o,$tablename)) {
			$status["UPDATE"] = false;
			return $status;
		} else $status["UPDATE"] = true;
		
		$this->delete($tablename,"id=".$insert_id);
		$o = $this->selectObject($tablename,"id=".$insert_id);
		if ($o != null) {
			$status["DELETE"] = false;
			return $status;
		} else $status["DELETE"] = true;
		
		$dd["thirdcol"] = array(
			DB_FIELD_TYPE=>DB_DEF_TIMESTAMP);
		
		$this->alterTable($tablename,$dd,array());
		$o = null;
		$o->name = "Alter Test";
		$o->thirdcol = "Third Column";
		if (!$this->insertObject($o,$tablename)) {
			$status["ALTER TABLE"] = false;
			return $status;
		} else $status["ALTER TABLE"] = true;
		
		$this->dropTable($tablename);
		if ($this->tableExists($tablename)) {
			$status["DROP TABLE"] = false;
			return $status;
		} else $status["DROP TABLE"] = true;
		
		foreach ($this->getTables() as $t) {
			if (substr($t,0,14+strlen($this->prefix)) == $this->prefix."___testertable") $this->dropTable($t);
		}
		
		return $status;
	}
	
	/* exdoc
	 * Alter an existing table
	 *
	 * Alters the structure of an existing database table to conform to the passed
	 * data definition.
	 *
	 * This function abides by the Exponent Data Definition Language, and interprets
	 * its general structure for MySQL.
	 *
	 * @param string $tablename The name of the table to alter
	 * @param array $newdatadef The new data definition for the table.
	 *   This is expressed in the Exponent Data Definition Language
	 * @param array $info Information about the table itself.
	 * @param bool $aggressive Whether or not to aggressively update the table definition.
	 *   An aggressive update will drop columns in the table that are not in the Exponent definition.
	 */
	function alterTable($tablename,$newdatadef,$info,$aggressive = false) {
		$dd = $this->getDataDefinition($tablename);
		$modified = false;
		
		if ($aggressive) {
			$oldcols = array_diff_assoc($dd, $newdatadef);
			if (count($oldcols)) {
				$modified = true;
				$sql = "ALTER TABLE `" . $this->prefix . "$tablename` ";
				foreach ($oldcols as $name=>$def) {
					$sql .= " DROP COLUMN " . $name . ",";
				}
				$sql = substr($sql,0,-1);
				
				@mysql_query($sql,$this->connection);
			}
		}	
		$diff = array_diff_assoc($newdatadef,$dd);
		if (count($diff)) {
			$modified = true;
			$sql = "ALTER TABLE `" . $this->prefix . "$tablename` ";
			foreach ($diff as $name=>$def) {
				$sql .= " ADD COLUMN (" . $this->fieldSQL($name,$def) . "),";
			}
			
			$sql = substr($sql,0,-1);
			
			@mysql_query($sql,$this->connection);
		} 
		
		if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
			// Initialize workflow tables:
			if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
			pathos_workflow_alterWorkflowTables($tablename,$newdatadef,$aggressive);
		}
		
		if ($modified) {
			return TABLE_ALTER_SUCCEEDED;
		} else {
			return TABLE_ALTER_NOT_NEEDED;
		}
	}
	
	/* exdoc
	 * Drop a table from the database
	 *
	 * Removes an existing table from the database. Returns true if the table was dropped, false if there
	 * was an error returned by the MySQL server.
	 *
	 * @param string $table The name of the table to drop.
	 */
	function dropTable($table) {
		return @mysql_query("DROP TABLE `".$this->prefix."$table`",$this->connection) !== false;
	}
	
	/* exdoc
	 * Run raw SQL.  Returns true if the query succeeded, and false
	 *   if an error was returned from the MySQL server.
	 *
	 * <div style="color:red">If you can help it, do not use this function.  It presents Database Portability Issues.</div>
	 *
	 * Runs a straight SQL query on the database.  This is not a
	 * very portable way of dealing with the database, and is only
	 * provided as a last resort.
	 *
	 * @param string $sql The SQL query to run
	 */
	function sql($sql) {
		return @mysql_query($sql,$this->connection);
	}
	
	/* exdoc
	 * Select a series of objects
	 *
	 * Selects a set of objects from the database.  Because of the way
	 * Exponent handles objects and database tables, this is akin to
	 * SELECTing a set of records from a database table.  Returns an
	 * array of objects, in any random order.
	 *
	 * @param string $table The name of the table/object to look at
	 * @param string $where Criteria used to narrow the result set.  If this
	 *   is specified as null, then no criteria is applied, and all objects are
	 *   returned
	 */
	function selectObjects($table,$where = null) {
		if ($where == null) $where = "1";
		$res = @mysql_query("SELECT * FROM `" . $this->prefix . "$table` WHERE $where",$this->connection);
		if ($res == null) return array();
		$objects = array();
		for ($i = 0; $i < mysql_num_rows($res); $i++) $objects[] = mysql_fetch_object($res);
		return $objects;
	}
	
	/* exdoc
	 * Select a series of objects, and return by ID
	 *
	 * Selects a set of objects from the database.  Because of the way
	 * Exponent handles objects and database tables, this is akin to
	 * SELECTing a set of records from a database table. Returns an
	 * array of objects, in any random order.  The indices of the array
	 * are the IDs of the objects.
	 *
	 * @param string $table The name of the table/object to look at
	 * @param string $where Criteria used to narrow the result set.  If this
	 *   is specified as null, then no criteria is applied, and all objects are
	 *   returned
	 */
	function selectObjectsIndexedArray($table,$where = null) {
		if ($where == null) $where = "1";
		$res = @mysql_query("SELECT * FROM `" . $this->prefix . "$table` WHERE $where",$this->connection);
		if ($res == null) return array();
		$objects = array();
		for ($i = 0; $i < mysql_num_rows($res); $i++) {
			$o = mysql_fetch_object($res);
			$objects[$o->id] = $o;
		}
		return $objects;
	}
	
	/* exdoc
	 * Count Objects matching Criteria
	 *
	 * @param string $table The name of the table to count objects in.
	 * @param string $where Criteria for counting.
	 */
	function countObjects($table,$where = null) {
		if ($where == null) $where = "1";
		$res = @mysql_query("SELECT COUNT(*) as c FROM `" . $this->prefix . "$table` WHERE $where",$this->connection);
		if ($res == null) return 0;
		$obj = mysql_fetch_object($res);
		return $obj->c;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function selectObject($table,$where) {
		$res = @mysql_query("SELECT * FROM `" . $this->prefix . "$table` WHERE $where LIMIT 0,1",$this->connection);
		if ($res == null) return null;
		return mysql_fetch_object($res);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function insertObject($object,$table) {
		$sql = "INSERT INTO `" . $this->prefix . "$table` (";
		$values = ") VALUES (";
		foreach (get_object_vars($object) as $var=>$val) {
			//We do not want to save any fields that start with an '_'
			if ($var{0} != '_') {
				$sql .= "$var,";
				$values .= "'".mysql_escape_string($val)."',";
			}
		}
		$sql = substr($sql,0,-1).substr($values,0,-1) . ")";
		if (@mysql_query($sql,$this->connection) != false) {
			$id = mysql_insert_id($this->connection);
			return $id;
		} else return 0;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function delete($table,$where = null) {
		if ($where != null) {
			$res = @mysql_query("DELETE FROM `" . $this->prefix . "$table` WHERE $where",$this->connection);
			return $res;
		} else {
			$res = @mysql_query("TRUNCATE TABLE `" . $this->prefix . "$table`",$this->connection);
			return $res;
		}
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function updateObject($object,$table,$where=null) {
		$sql = "UPDATE " . $this->prefix . "$table SET ";
		foreach (get_object_vars($object) as $var=>$val) {
			//We do not want to save any fields that start with an '_'
			if ($var{0} != '_') {
				$sql .= "`$var`='".mysql_escape_string($val)."',";
			}
		}
		$sql = substr($sql,0,-1) . " WHERE ";
		if ($where != null) $sql .= $where;
		else $sql .= "`id`=" . $object->id;
		
		$res = (@mysql_query($sql,$this->connection) != false);
		return $res;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function max($table,$attribute,$groupfields = null,$where = null) {
		if (is_array($groupfields)) $groupfields = join(",",$groupfields);
		$sql = "SELECT MAX($attribute) as fieldmax FROM `" . $this->prefix . "$table`";
		if ($where != null) $sql .= " WHERE $where";
		if ($groupfields != null) $sql .= " GROUP BY $groupfields";
		
		$res = @mysql_query($sql,$this->connection);
		
		if ($res != null) $res = mysql_fetch_object($res);
		if (!$res) return null;
		return $res->fieldmax;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function min($table,$attribute,$groupfields = null,$where = null) {
		if (is_array($groupfields)) $groupfields = join(",",$groupfields);
		$sql = "SELECT MIN($attribute) as fieldmin FROM `" . $this->prefix . "$table`";
		if ($where != null) $sql .= " WHERE $where";
		if ($groupfields != null) $sql .= " GROUP BY $groupfields";
		
		$res = @mysql_query($sql,$this->connection);
		
		if ($res != null) $res = mysql_fetch_object($res);
		if (!$res) return null;
		return $res->fieldmin;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function increment($table,$field,$step,$where = null) {
		if ($where == null) $where = "1";
		$sql = "UPDATE `".$this->prefix."$table` SET `$field`=`$field`+$step WHERE $where";
		return @mysql_query($sql,$this->connection);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function decrement($table,$field,$step,$where = null) {
		$this->increment($table,$field,-1*$step,$where);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function tableExists($table) {
		$res = @mysql_query("SELECT * FROM `" . $this->prefix . "$table` LIMIT 0,1",$this->connection);
		return ($res != null);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function getTables($prefixed_only=true) {
		$res = @mysql_query("SHOW TABLES",$this->connection);
		$tables = array();
		for ($i = 0; $res && $i < mysql_num_rows($res); $i++) {
			$tmp = mysql_fetch_array($res);
			if ($prefixed_only && substr($tmp[0],0,strlen($this->prefix)) == $this->prefix) {
				$tables[] = $tmp[0];
			} else if (!$prefixed_only) {
				$tables[] = $tmp[0];
			}
		}
		return $tables;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function optimize($table) {
		$res = (@mysql_query("OPTIMIZE TABLE `" . $this->prefix . "$table`",$this->connection) != false);
		return $res;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function tableInfo($table) {
		$sql = "SHOW TABLE STATUS LIKE '" . $this->prefix . "$table'";
		$res = @mysql_query($sql,$this->connection);
		if (!$res) return null;
		return $this->translateTableStatus(mysql_fetch_object($res));
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function tableIsEmpty($table) {
		return ($this->countObjects($table) == 0);
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function databaseInfo() {
		$sql = "SHOW TABLE STATUS";
		$res = @mysql_query("SHOW TABLE STATUS",$this->connection);
		$info = array();
		for ($i = 0; $res && $i < mysql_num_rows($res); $i++) {
			$obj = mysql_fetch_object($res);
			$info[substr($obj->Name,strlen($this->prefix))] = $this->translateTableStatus($obj);
		}
		return $info;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function translateTableStatus($status) {
		$data = null;
		$data->rows = $status->Rows;
		$data->average_row_lenth = $status->Avg_row_length;
		$data->data_overhead = $status->Data_free;
		$data->data_total = $status->Data_length;
		
		return $data;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function getDataDefinition($table) {
		if (!$this->tableExists($table)) return array();
		$res = @mysql_query("DESCRIBE `".$this->prefix."$table`",$this->connection);
		$dd = array();
		for ($i = 0; $i < mysql_num_rows($res); $i++) {
			$fieldObj = mysql_fetch_object($res);
			
			$field = array();
			$field[DB_FIELD_TYPE] = $this->getDDFieldType($fieldObj);
			if ($field[DB_FIELD_TYPE] == DB_DEF_STRING) {
				$field[DB_FIELD_LEN] = $this->getDDStringLen($fieldObj);
			}
			
			$dd[$fieldObj->Field] = $field;
		}
		
		return $dd;
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function getDDFieldType($fieldObj) {
		$type = strtolower($fieldObj->Type);
		
		if ($type == "int(11)") return DB_DEF_ID;
		if ($type == "int(8)") return DB_DEF_INTEGER;
		else if ($type == "tinyint(1)") return DB_DEF_BOOLEAN;
		else if ($type == "int(14)") return DB_DEF_TIMESTAMP;
		else if (substr($type,5) == "double") return DB_DEF_DECIMAL;
		// Strings
		else if ($type == "text" || $type == "mediumtext" || $type == "longtext" || strpos($type,"varchar(") !== false) {
			return DB_DEF_STRING;
		}
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function getDDStringLen($fieldObj) {
		$type = strtolower($fieldObj->Type);
		if ($type == "text") return 65535;
		else if ($type == "mediumtext") return 16777215;
		else if ($type == "longtext") return 16777216;
		else if (strpos($type,"varchar(") !== false) {
			return str_replace(  array("varchar(",")"),  "",$type) + 0;
		}
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function error() {
		if ($this->connection && mysql_errno($this->connection) != 0) {
			$errno = mysql_errno($this->connection);
			switch ($errno) {
				case 1046:
					return "1046 : Selected database does not exist";
				default:
					return mysql_errno($this->connection) . " : " . mysql_error($this->connection);
			}
		} else if ($this->connection == false) {
			return "Unable to connect to database server";
		} else return "";
	}
	
	/* exdoc
	 * @state <b>UNDOCUMENTED</b>
	 * @node Undocumented
	 */
	function inError() {
		return ($this->connection != null && mysql_errno($this->connection) != 0);
	}
}

?>
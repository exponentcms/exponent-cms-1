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

/**
 * PostGreSQL Database Engine
 *
 * Manages SQL transactions made to a (possibly
 * networked) PostGreSQL database server.
 *
 * @package	Subsystems
 * @subpackage	Database = PostGreSQL
 *
 * @author		James Hunt
 * @copyright	2004 James Hunt and the OIC Group, Inc.
 * @version	0.95
 *
 * @ignore
 */

class postgres_database {

	var $connection = null;
	var $prefix = "";
	
	var $error = "";
	var $in_error = false;
	
	/**
	 * @internal
	 */
	function checkError($res) {
		if ($res === false) {
			$in_error = true;
			$error = pg_last_error();
		} else if (pg_result_status($res) == PGSQL_FATAL_ERROR) {
			$in_error = true;
			$error = pg_result_error($res);
		} else {
			$error = "";
			$in_error = false;
		}
	}

	function connect($username,$password,$hostname,$database) {
		$host_data = split(":",$hostname);
		$hostname = $host_data[0];
		$port = $hostname[1];
		if ($hostname == "localhost") $dsn = "user=$username password=$password dbname=$database";
		else $dsn = "host=$hostname user=$username password=$password dbname=$database";
		$this->connection = pg_connect($dsn);
		
		$this->prefix = DB_TABLE_PREFIX;
	}
	
	function createTable($tablename,$datadef,$info) {
		$sql = "CREATE TABLE \"".$this->prefix."$tablename\" (";
		foreach ($datadef as $name=>$def) $sql .= $this->fieldSQL($name,$def) . ",";
		$sql = substr($sql,0,-1) . ")";
		@pg_query($this->connection,$sql);
		
		if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
			// Initialize workflow tables:
			if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
			pathos_workflow_installWorkflowTables($tablename,$datadef);
		}
	}
	
	function fieldSQL($name,$def) {
		$sql = "$name";
		if (!isset($def[DB_FIELD_TYPE])) {
			return false;
		}
		$type = $def[DB_FIELD_TYPE];
		if (isset($def[DB_INCREMENT]) && $def[DB_INCREMENT]) {
			$sql .= " serial";
		} else if ($type == DB_DEF_ID) {
			$sql .= " int8";
		} else if ($type == DB_DEF_INTEGER) {
			$sql .= " numeric";
		} else if ($type == DB_DEF_TIMESTAMP) {
			$sql .= " int4";
		} else if ($type == DB_DEF_BOOLEAN) {
			$sql .= " bit";
		} else if ($type == DB_DEF_STRING) {
			$sql .= " text";
		} else if ($type == DB_DEF_DECIMAL) {
			$sql .= " float4";
		} else {
			return false; // must specify known FIELD_TYPE
		}
		
		if (isset($def[DB_PRIMARY]) && $def[DB_PRIMARY]) $sql .= " PRIMARY KEY";
		
		#$sql .= " NOT NULL";
		if (isset($def[DB_DEFAULT])) $sql .= " DEFAULT '" . $def[DB_DEFAULT] . "'";
		
		return $sql;
	}
	
	function alterTable($tablename,$newdatadef,$info) {
		$dd = $this->getDataDefinition($tablename);
		
		$diff = array_diff_assoc($newdatadef,$dd);
		if (count($diff)) {
			$sql = "ALTER TABLE " . $this->prefix . "$tablename ";
			foreach ($diff as $name=>$def) {
				$sql .= " ADD COLUMN " . $this->fieldSQL($name,$def) . ",";
			}
			$sql = substr($sql,0,-1);
			
			@pg_query($this->connection,$sql);
			
			if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
				// Initialize workflow tables:
				if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
				pathos_workflow_alterWorkflowTables($tablename,$newdatadef);
			}
			
			return TABLE_ALTER_SUCCEEDED;
		} else {
			if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
				// Initialize workflow tables:
				if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
				pathos_workflow_alterWorkflowTables($tablename,$newdatadef);
			}
			return TABLE_ALTER_NOT_NEEDED;
		}
	}
	
	function dropTable($table) {
		$sql = "DROP TABLE ".$this->prefix.$table;
		@pg_query($this->connection,$sql);
	}
	
	function sql($sql) {
		$res = @pg_query($sql);
		return $res;
	}
	
	function selectObjects($table,$where = null) {
		$sql = "SELECT * FROM " . $this->prefix.$table;
		if ($where != null) $sql .= " WHERE $where";
		$res = pg_query($sql);
		$this->checkError($res);
		
		$records = array();
		for ($i = 0; $i < @pg_num_rows($res); $i++) {
			$records[] = pg_fetch_object($res);
		}
		@pg_free_result($res);
		return $records;
	}
	
	function selectObjectsIndexedArray($table,$where = null) {
		$sql = "SELECT * FROM " . $this->prefix.$table;
		if ($where != null) $sql .= " WHERE $where";
		$res = @pg_query($sql);
		$this->checkError($res);
		
		$records = array();
		for ($i = 0; $i < @pg_num_rows($res); $i++) {
			$o = pg_fetch_object($res);
			$records[$o->id] = $o;
		}
		@pg_free_result($res);
		return $records;
	}
	
	function countObjects($table,$where = null) {
		$sql = "SELECT COUNT(*) as num FROM " . $this->prefix . $table;
		if ($where != null) $sql .= " WHERE $where";
		$res = @pg_query($this->connection,$sql);
		$this->checkError($res);
		if ($res !== FALSE) {
			$num = @pg_fetch_object($res);
			@pg_free_result($res);
			return $num->num;
		} else return 0;
	}
	
	function selectObject($table,$where) {
		$sql = "SELECT * FROM " . $this->prefix . $table . " WHERE $where";
		$res = @pg_query($this->connection,$sql);
		$this->checkError($res);
		if ($res == null) return null;
		return pg_fetch_object($res);
	}
	
	function insertObject($object,$table) {
		$sql = "INSERT INTO " . $this->prefix.$table . " (";
		$values = ") VALUES (";
		foreach (get_object_vars($object) as $var=>$val) {
			$sql .= "$var,";
			$values .= "'".str_replace("'","\\'",$val)."',";
		}
		if (@pg_query($this->connection,substr($sql,0,-1).substr($values,0,-1) . ")") != false) {
			$sql = "SELECT last_value FROM " . $this->prefix.$table ."_id_seq";
			$res = @pg_query($this->connection,$sql);
			if ($res) {
				$o = @pg_fetch_object($res);
				@pg_free_result($res);
				return $o->last_value;
			} else return 0;
		} else return 0;
	}
	
	function delete($table,$where = null) {
		$sql = "DELETE FROM " . $this->prefix . $table;
		if ($where != null) $sql .= " WHERE " . $where;
		@pg_query($this->connection,$sql);
	}
	
	function updateObject($object,$table,$where=null) {
		$sql = "UPDATE " . $this->prefix.$table . " SET ";
		foreach (get_object_vars($object) as $var=>$val) {
			$sql .= "$var='".str_replace("'","\\'",$val)."',";
		}
		$sql = substr($sql,0,-1) . " WHERE ";
		if ($where != null) $sql .= $where;
		else $sql .= "id=" . $object->id;
		
		return (@pg_query($this->connection,$sql) != false);
	}
	
	function max($table,$attribute,$groupfields = null,$where = null) {
		if (is_array($groupfields)) $groupfields = join(",",$groupfields);
		$sql = "SELECT MAX($attribute) as fieldmax FROM " . $this->prefix . "$table";
		if ($where != null) $sql .= " WHERE $where";
		if ($groupfields != null) $sql .= " GROUP BY $groupfields";
		$res = @pg_query($this->connection,$sql);
		if ($res == null) return null;
		$o = @pg_fetch_object($res);
		@pg_free_result($res);
		if (!$o) return null;
		return $o->fieldmax;
	}
	
	function tableExists($table) {
		$sql = "SELECT COUNT(relname) as num FROM pg_catalog.pg_class JOIN pg_catalog.pg_namespace ON (relnamespace = pg_namespace.oid) WHERE relkind IN ('r') AND nspname = 'public' AND relname = '".$this->prefix.$table."'";
		$res = @pg_query($this->connection,$sql);
		$this->checkError($res);
		if ($res) {
			$o = pg_fetch_object($res);
			@pg_free_result($res);
			return ($o->num != 0);
		} else return false;
	}
	
	function getTables($prefixed_only=true) {
		$sql = "SELECT relname as tablename FROM pg_catalog.pg_class JOIN pg_catalog.pg_namespace ON (relnamespace = pg_namespace.oid) WHERE relkind IN ('r') AND nspname = 'public'";
		$res = @pg_query($this->connection,$sql);
		$this->checkError($res);
		$tables = array();
		for ($i = 0; $i < @pg_num_rows($res); $i++) {
			$o = pg_fetch_object($res);
			if ($prefixed_only && substr($o->tablename,0,strlen($this->prefix)) == $this->prefix) {
				$tables[] = $o->tablename;
			} else if (!$prefixed_only) {
				$tables[] = $o->tablename;
			}
		}
		@pg_free_result($res);
		return $tables;
	}
	
	function optimize($table) {
		$sql = "VACUUM ". $this->prefix.$table;
		@pg_query($this->connection,$sql);
	}
	
	function tableInfo($table) {
	// Logic here
		return $this->translateTableStatus(null);
	}
	
	function tableIsEmpty($table) {
		return ($this->countObjects($table) == 0);
	}
	
	function databaseInfo() {
		
	}
	
	function translateTableStatus($status) {
		$data = null;
		$data->rows = -1;
		$data->average_row_length = -1;
		$data->data_overhead = -1;
		$data->data_total = -1;
		return $data;
	}
	
	function getDataDefinition($table) {
		$sql = <<<ENDSQL
SELECT
	a.attnum,
	a.attname AS field,
	t.typname AS type,
	a.attlen AS length,
	a.atttypmod AS lengthvar,
	a.attnotnull AS notnull
FROM
	pg_class c,
	pg_attribute a,
	pg_type t
WHERE
	c.relname = '{$this->prefix}$table'
	and a.attnum > 0
	and a.attrelid = c.oid
	and a.atttypid = t.oid
ORDER BY a.attnum
ENDSQL;
		
		$dd = array();
		
		$res = @pg_query($sql);
		$this->checkError($res);
		for ($i = 0; $i < @pg_num_rows($res); $i++) {
			$o = @pg_fetch_object($res);
			
			$fld = array();
			
			switch ($o->type) {
				case "int8":
					$fld[DB_FIELD_TYPE] = DB_DEF_ID;
					break;
				case "text":
					$fld[DB_FIELD_TYPE] = DB_DEF_STRING;
					$fld[DB_FIELD_LEN] = 100;
					break;
				case "numeric":
					$fld[DB_FIELD_TYPE] = DB_DEF_INTEGER;
					break;
				case "bit":
					$fld[DB_FIELD_TYPE] = DB_DEF_BOOLEAN;
					break;
				case "int4":
					$fld[DB_FIELD_TYPE] = DB_DEF_TIMESTAMP;
					break;
				case "float4":
					$fld[DB_FIELD_TYPE] = DB_DEF_DECIMAL;
					break;
			}
			
			$dd[$o->field] = $fld;
		}
		
		return $dd;
	}
	
	function error() {
		return $this->error;
	}
	
	function inError() {
		return ($this->in_error == true);
	}
}

?>
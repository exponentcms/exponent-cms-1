<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

/* exdoc
 * Database Class (MySQL)
 *
 * This is the MySQL-specific implementation of the database class.
 *
 * @node Subsystems:Database:MySQL
 */
class mysqli_database {
	var $connection = null;
	var $havedb = false;
	var $prefix = "";


	function connect ($username, $password, $hostname, $database, $new=false) {
		list ( $host, $port ) = @explode (":", $hostname);
		if ($this->connection = mysqli_connect($host, $username, $password, $database, $port)) {
			$this->havedb = true;
		}
		
		list($major, $minor, $micro) = sscanf(mysqli_get_server_info($this->connection), "%d.%d.%d-%s");
		if(defined("DB_ENCODING")) {
			//SET NAMES is possible since version 4.1
			if(($major > 4) OR (($major == 4) AND ($minor >= 1))) {
				mysqli_query($this->connection, "SET NAMES " . DB_ENCODING);
			}
		}

		$this->prefix = DB_TABLE_PREFIX . '_';
	}
	
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
			$sql .= ", PRIMARY KEY(`" . implode("`,`",$primary) . "`)";
		}
		foreach ($unique as $key=>$value) {
			$sql .= ", UNIQUE `".$key."` ( `" . implode("`,`",$value) . "`)";
		}
		foreach ($index as $key=>$value) {
			$sql .= ", INDEX (`" . $key . "`" . (($value > 0)?"(".$value.")":"") . ")";
		}
		$sql .= ")";
		if (defined(DB_ENCODING)) {
			$sql .= " ENGINE = MYISAM CHARACTER SET " . DB_ENCODING;		
		}else{
			$sql .= " ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci";		
		}	
		
		if (isset($info[DB_TABLE_COMMENT])) {
			$sql .= " COMMENT = '" . $info[DB_TABLE_COMMENT] . "'";
		}			
		
		mysqli_query($this->connection, $sql);		

		$return = array(
			$tablename=>($this->tableExists($tablename) ? DATABASE_TABLE_INSTALLED : DATABASE_TABLE_FAILED)
		);

		if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
			// Initialize workflow tables:
			if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
			$wf = exponent_workflow_installWorkflowTables($tablename,$datadef);
			foreach ($wf as $key=>$status) {
				$return[$key] = $status;
			}
		}

		return $return;
	}

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
			if (isset($def[DB_FIELD_LEN]) && is_int($def[DB_FIELD_LEN])) {
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

	function switchValues($table,$field,$a,$b,$additional_where = null) {
		if ($additional_where == null) {
			$additional_where = '1';
		}
		$object_a = $this->selectObject($table,"$field='$a' AND $additional_where");
		$object_b = $this->selectObject($table,"$field='$b' AND $additional_where");

		if ($object_a && $object_b) {
			$tmp = $object_a->$field;
			$object_a->$field = $object_b->$field;
			$object_b->$field = $tmp;

			$this->updateObject($object_a,$table);
			$this->updateObject($object_b,$table);

			return true;
		} else {
			return false;
		}
	}

	function isValid() {
		return ($this->connection != null && $this->havedb);
	}

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

	function alterTable($tablename,$newdatadef,$info,$aggressive = false) {
		$dd = $this->getDataDefinition($tablename);
		$modified = false;

		//Drop any old columns from the table if aggressive mode is set.
		if ($aggressive) {
			$oldcols = array_diff_assoc($dd, $newdatadef);
			if (count($oldcols)) {
				$modified = true;
				$sql = "ALTER TABLE `" . $this->prefix . "$tablename` ";
				foreach ($oldcols as $name=>$def) {
					$sql .= " DROP COLUMN " . $name . ",";
				}
				$sql = substr($sql,0,-1);

				mysqli_query($this->connection, $sql);
			}
		}

		//Add any new columns to the table
		$diff = array_diff_assoc($newdatadef,$dd);
		if (count($diff)) {
			$modified = true;
			$sql = "ALTER TABLE `" . $this->prefix . "$tablename` ";
			foreach ($diff as $name=>$def) {
				$sql .= " ADD COLUMN (" . $this->fieldSQL($name,$def) . "),";
			}

			$sql = substr($sql,0,-1);
			mysqli_query($this->connection, $sql);
		}

		//Add any new indexes & keys to the table.
		$index = array();
		foreach ($newdatadef as $name=>$def) {
                        if ($def != null) {
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
				$sql = "ALTER TABLE `" . $this->prefix . "$tablename` ";
                /*if (count($primary)) {
                        $sql .= ", PRIMARY KEY(`" . implode("`,`",$primary) . "`)";
                }
                foreach ($unique as $key=>$value) {
                        $sql .= ", UNIQUE `".$key."` ( `" . implode("`,`",$value) . "`)";
                }*/
                foreach ($index as $key=>$value) {
                        $sql .= "ADD INDEX (" . $key . ")";
                        mysqli_query($this->connection, $sql);
                }

		//Get the return code
		$return = array(
			$tablename=>($modified ? TABLE_ALTER_SUCCEEDED : TABLE_ALTER_NOT_NEEDED)
		);
		
		if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW]) {
			// Initialize workflow tables:
			if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
			$wf = exponent_workflow_alterWorkflowTables($tablename,$newdatadef,$aggressive);
			foreach ($wf as $key=>$status) {
				$return[$key] = $status;
			}
		}

		return $return;
	}

	function dropTable($table) {
		return mysqli_query($this->connection, "DROP TABLE `".$this->prefix."$table`") !== false;
	}

	function sql($sql) {
		return mysqli_query($this->connection, $sql);
	}

	function selectObjects($table,$where = null,$orderby = null) {
		if ($where == null) $where = "1";
		if ($orderby == null) $orderby = '';
	    else $orderby = "ORDER BY " . $orderby;

	    $res = mysqli_query($this->connection, "SELECT * FROM `" . $this->prefix . "$table` WHERE $where $orderby");
		if ($res == null) return array();
		$objects = array();
		for ($i = 0; $i < mysqli_num_rows($res); $i++) $objects[] = mysqli_fetch_object($res);
		return $objects;
	}

	function selectObjectsBySql($sql) {
                $res = mysqli_query($sql, $this->connection);
                if ($res == null) return array();
                $objects = array();
                for ($i = 0; $i < mysqli_num_rows($res); $i++) $objects[] = mysqli_fetch_object($res);
                return $objects;
	}

	function selectColumn($table,$col,$where = null,$orderby = null) {
                if ($where == null) $where = "1";
                if ($orderby == null) $orderby = '';
            	else $orderby = "ORDER BY " . $orderby;

                $res = mysqli_query($this->connection, "SELECT ".$col." FROM `" . $this->prefix . "$table` WHERE $where $orderby");
                if ($res == null) return array();
                $resarray = array();
                for ($i = 0; $i < mysqli_num_rows($res); $i++){
                        $row = mysqli_fetch_array($res, MYSQL_NUM);
                        $resarray[$i] = $row[0];
                }
                return $resarray;
        }

	function selectValue($table,$col,$where=null) {
	if ($where == null) $where = "1";
    	$res = mysqli_query($this->connection, "SELECT ".$col." FROM `" . $this->prefix . "$table` WHERE $where LIMIT 0,1");

        if ($res == null) return null;
		$obj = mysqli_fetch_object($res);
		 if (is_object($obj)) {
                        return $obj->$col;
                } else {
                        return null;
                }
        }

	function selectObjectsInArray($table, $array=array(), $orderby=null) {
		$where = '';
		foreach($array as $array_id) {
			if ($where == '') {
				$where .= 'id='.$array_id;
			} else {
				$where .= ' OR id='.$array_id;
			}
		}

		//eDebug($where);
		$res = $this->selectObjects($table, $where, $orderby);
		return $res;
	}

	function selectObjectsIndexedArray($table,$where = null,$orderby = null) {
		if ($where == null) $where = "1";
		if ($orderby == null) $orderby = '';
	    else $orderby = "ORDER BY " . $orderby;
		$res = mysqli_query($this->connection, "SELECT * FROM `" . $this->prefix . "$table` WHERE $where $orderby");

		if ($res == null) return array();
		$objects = array();
		for ($i = 0; $i < mysqli_num_rows($res); $i++) {
			$o = mysqli_fetch_object($res);
			$objects[$o->id] = $o;
		}
		return $objects;
	}

	function countObjects($table,$where = null) {
		if ($where == null) $where = "1";
		$res = mysqli_query($this->connection,"SELECT COUNT(*) as c FROM `" . $this->prefix . "$table` WHERE $where");
		if ($res == null) return 0;
		$obj = mysqli_fetch_object($res);
		return $obj->c;
	}

	function selectObject($table,$where) {
		$res = mysqli_query($this->connection, "SELECT * FROM `" . $this->prefix . "$table` WHERE $where LIMIT 0,1");

        	if ($res == null) return null;
		    return mysqli_fetch_object($res);
	}

	function insertObject($object,$table) {
		$sql = "INSERT INTO `" . $this->prefix . "$table` (";
		$values = ") VALUES (";
		foreach (get_object_vars($object) as $var=>$val) {
			//We do not want to save any fields that start with an '_'
			if ($var{0} != '_') {
				$sql .= "`$var`,";
				if ( $values != ") VALUES (" ) {
					$values .= ",";
				}
				$values .= "'".mysqli_real_escape_string($this->connection, $val)."'";
			}
		}
		$sql = substr($sql,0,-1).substr($values,0) . ")";
		if (mysqli_query($this->connection, $sql) != false) {
			$id = mysqli_insert_id($this->connection);
			return $id;
		} else return 0;
	}

	function delete($table,$where = null) {
		if ($where != null) {
			$res = mysqli_query($this->connection,"DELETE FROM `" . $this->prefix . "$table` WHERE $where");
			return $res;
		} else {
			$res = mysqli_query($this->connection,"TRUNCATE TABLE `" . $this->prefix . "$table`");
			return $res;
		}
	}

	function updateObject($object,$table,$where=null) {
		$sql = "UPDATE " . $this->prefix . "$table SET ";
		foreach (get_object_vars($object) as $var=>$val) {
			//We do not want to save any fields that start with an '_'
			if ($var{0} != '_') {
				$sql .= "`$var`='".mysqli_real_escape_string($this->connection,$val)."',";
			}
		}
		$sql = substr($sql,0,-1) . " WHERE ";
		if ($where != null) $sql .= $where;
		else $sql .= "`id`=" . $object->id;

		$res = (mysqli_query($this->connection,$sql) != false);
		return $res;
	}

	function max($table,$attribute,$groupfields = null,$where = null) {
		if (is_array($groupfields)) $groupfields = implode(",",$groupfields);
		$sql = "SELECT MAX($attribute) as fieldmax FROM `" . $this->prefix . "$table`";
		if ($where != null) $sql .= " WHERE $where";
		if ($groupfields != null) $sql .= " GROUP BY $groupfields";

		$res = mysqli_query($this->connection,$sql);

		if ($res != null) $res = mysqli_fetch_object($res);
		if (!$res) return null;
		return $res->fieldmax;
	}

	function min($table,$attribute,$groupfields = null,$where = null) {
		if (is_array($groupfields)) $groupfields = implode(",",$groupfields);
		$sql = "SELECT MIN($attribute) as fieldmin FROM `" . $this->prefix . "$table`";
		if ($where != null) $sql .= " WHERE $where";
		if ($groupfields != null) $sql .= " GROUP BY $groupfields";

		$res = mysqli_query($this->connection,$sql);

		if ($res != null) $res = mysqli_fetch_object($res);
		if (!$res) return null;
		return $res->fieldmin;
	}

	function increment($table,$field,$step,$where = null) {
		if ($where == null) $where = "1";
		$sql = "UPDATE `".$this->prefix."$table` SET `$field`=`$field`+$step WHERE $where";
		return mysqli_query($this->connection,$sql);
	}

	function decrement($table,$field,$step,$where = null) {
		$this->increment($table,$field,-1*$step,$where);
	}

	function tableExists($table) {
		$res = mysqli_query($this->connection, "SELECT * FROM `" . $this->prefix . "$table` LIMIT 0,1");
		return ($res != null);
	}

	function getTables($prefixed_only=true) {
		$res = mysqli_query($this->connection,"SHOW TABLES");
		$tables = array();
		for ($i = 0; $res && $i < mysqli_num_rows($res); $i++) {
			$tmp = mysqli_fetch_array($res);
			if ($prefixed_only && substr($tmp[0],0,strlen($this->prefix)) == $this->prefix) {
				$tables[] = $tmp[0];
			} else if (!$prefixed_only) {
				$tables[] = $tmp[0];
			}
		}
		return $tables;
	}

	function optimize($table) {
		$res = (mysqli_query($this->connection,"OPTIMIZE TABLE `" . $this->prefix . "$table`") != false);
		return $res;
	}

	function tableInfo($table) {
		$sql = "SHOW TABLE STATUS LIKE '" . $this->prefix . "$table'";
		$res = mysqli_query($this->connection,$sql);
		if (!$res) return null;
		return $this->translateTableStatus(mysqli_fetch_object($res));
	}

	function tableIsEmpty($table) {
		return ($this->countObjects($table) == 0);
	}

	function databaseInfo() {
		$sql = "SHOW TABLE STATUS";
		$res = mysqli_query($this->connection,"SHOW TABLE STATUS LIKE '".$this->prefix."%'");
		$info = array();
		for ($i = 0; $res && $i < mysqli_num_rows($res); $i++) {
			$obj = mysqli_fetch_object($res);
			$info[substr($obj->Name,strlen($this->prefix))] = $this->translateTableStatus($obj);
		}
		return $info;
	}

	function translateTableStatus($status) {
		$data = null;
		$data->rows = $status->Rows;
		$data->average_row_lenth = $status->Avg_row_length;
		$data->data_overhead = $status->Data_free;
		$data->data_total = $status->Data_length;

		return $data;
	}

	function getDataDefinition($table) {
		if (!$this->tableExists($table)) return array();
		$res = mysqli_query($this->connection,"DESCRIBE `".$this->prefix."$table`");
		$dd = array();
		for ($i = 0; $i < mysqli_num_rows($res); $i++) {
			$fieldObj = mysqli_fetch_object($res);

			$field = array();
			$field[DB_FIELD_TYPE] = $this->getDDFieldType($fieldObj);
			if ($field[DB_FIELD_TYPE] == DB_DEF_STRING) {
				$field[DB_FIELD_LEN] = $this->getDDStringLen($fieldObj);
			}

			$dd[$fieldObj->Field] = $field;
		}

		return $dd;
	}

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

	function getDDStringLen($fieldObj) {
		$type = strtolower($fieldObj->Type);
		if ($type == "text") return 65535;
		else if ($type == "mediumtext") return 16777215;
		else if ($type == "longtext") return 16777216;
		else if (strpos($type,"varchar(") !== false) {
			return str_replace(  array("varchar(",")"),  "",$type) + 0;
		}
	}

	function error() {
		if ($this->connection && mysqli_errno($this->connection) != 0) {
			$errno = mysqli_errno($this->connection);
			switch ($errno) {
				case 1046:
					return "1046 : Selected database does not exist";
				default:
					return mysqli_errno($this->connection) . " : " . mysqli_error($this->connection);
			}
		} else if ($this->connection == false) {
			return "Unable to connect to database server";
		} else return "";
	}

	function inError() {
		return ($this->connection != null && mysqli_errno($this->connection) != 0);
	}

	function limit($num,$offset) {
		return ' LIMIT '.$offset.','.$num.' ';
	}
}

?>

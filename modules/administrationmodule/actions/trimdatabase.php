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

// Part of the Database category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('database',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin) {
	$dropped_tables = array();
	
	foreach ($db->getTables(true) as $table) {
		if (strpos(DB_TABLE_PREFIX.'_',$table) == 0) {
			$table = str_replace(DB_TABLE_PREFIX.'_',"",$table);
			
			//This is a quick fix to keep this from deleting the formbuilder tables!
			$tmp = str_replace('formbuilder_',"",$table);
			if ($db->countObjects('formbuilder_form',"table_name='".$tmp."'") == 0) {
			
				if ($db->tableIsEmpty($table)) {
					$db->dropTable($table);
					$dropped_tables[] = $table;
				}
			
			}
		}
	}
	
	$dropped_count = count($dropped_tables);
	
	$dir = BASE.'datatypes/definitions';
	if (is_readable($dir)) {
		if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
		foreach ($dropped_tables as $key=>$tablename) {
			if (is_readable("$dir/$tablename.php") && is_file("$dir/$tablename.php")) {
				$dd = include("$dir/$tablename.php");
				$info = null;
				if (is_readable("$dir/$tablename.info.php")) $info = include("$dir/$tablename.info.php");
				
				$db->createTable($tablename,$dd,$info);
				
				// Handle funky workflow situtations
				if (isset($info[DB_TABLE_WORKFLOW]) && $info[DB_TABLE_WORKFLOW] == 1) {
					pathos_workflow_alterWorkflowTables($tablename,$dd);
				}
			} else if (pathos_workflow_isWorkflowTable($tablename)) {
				$tablename = pathos_workflow_originalTable($tablename);
				if ($tablename != "") {
					if (is_readable("$dir/$tablename.php") && is_file("$dir/$tablename.php")) {
						$dd = include("$dir/$tablename.php");
						pathos_workflow_alterWorkflowTables($tablename,$dd);
					}
				}
			}
		}
		foreach ($dropped_tables as $key=>$tablename) {
			if ($db->tableExists($tablename)) {
				unset($dropped_tables[$key]);
			}
		}
	}
	
	$real_dropped_count = count($dropped_tables);
	
	$template = new Template('administrationmodule','_tableTrimSummary',$loc);
	$template->assign('status',$dropped_tables);
	$template->assign('dropped',$dropped_count);
	$template->assign('real_dropped',$real_dropped_count);
	$template->output();
	
} else {
	echo SITE_403_HTML;
}

?>
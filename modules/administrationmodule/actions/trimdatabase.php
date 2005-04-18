<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
	$droppable_tables = array();
	
	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	
	foreach ($db->getTables(true) as $table) {
		if (strpos(DB_TABLE_PREFIX.'_',$table) == 0) {
			$table = str_replace(DB_TABLE_PREFIX.'_',"",$table);
			//This is a quick fix to keep this from deleting the formbuilder tables!
			if (substr($table,0,12) == 'formbuilder_') {
				$tmp = str_replace('formbuilder_',"",$table);
				if ($db->countObjects('formbuilder_form',"table_name='".$tmp."'") != 0) {
					// Ignore this table.
					continue;
				}
			}
			
			if (pathos_workflow_isWorkflowTable($table)) {
				// Ignore workflow tables.
				continue;
			}
			
			$file = BASE.'datatypes/definitions/'.$table.'.php';
			if (is_readable($file) && is_file($file)) {
				// Table file exists.
				continue;
			}
			$droppable_tables[$table] = $db->countObjects($table);
		}
	}
	
	$droppable_count = count($droppable_tables);
	
	#$template = new template('administrationmodule','_tableTrimSummary',$loc);
	$template = new template('administrationmodule','_trimdatabaseWhich',$loc);
	$template->assign('droppable_tables',$droppable_tables);
	$template->assign('droppable_count',$droppable_count);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
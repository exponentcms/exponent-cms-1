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

// Part of the Database category

if (!defined('EXPONENT')) exit('');

$dropped_count = 0;
$dropped_tables = 0;
$real_dropped_count = 0;

if (exponent_permissions_check('database',exponent_core_makeLocation('administrationmodule'))) {
    
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
			
			if (exponent_workflow_isWorkflowTable($table)) {
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
	
	$template = new template('administrationmodule','_tableTrimSummary',$loc);
	$template->assign('status',$dropped_tables);

    $template->assign('dropped',$dropped_count);
	$template->assign('real_dropped',$real_dropped_count);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>

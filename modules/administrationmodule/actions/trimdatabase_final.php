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

if (exponent_permissions_check('database',exponent_core_makeLocation('administrationmodule'))) {
	foreach (array_keys($_POST['tables']) as $table) {
		$db->dropTable($table);
	}
	
	$template = new template('administrationmodule','_tableTrimSummary',$loc);
	$template->assign('dropped_tables',array_keys($_POST['tables']));
	$template->assign('redirect',exponent_flow_get());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
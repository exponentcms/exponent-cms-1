<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');

if (!defined('SYS_BACKUP')) include_once(BASE.'subsystems/backup.php');
$errors = null;

$template = new template('importer','_eql_results',$loc);
//GREP:UPLOADCHECK
if (!exponent_backup_restoreDatabase($db,$_FILES['file']['tmp_name'],$errors)) {
	$template->assign('success',0);
	$template->assign('errors',$errors);
} else {
	$template->assign('success',1);
}
$template->output();

?>
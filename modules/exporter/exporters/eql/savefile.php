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
//GREP:HARDCODEDTEXT
if (!defined('PATHOS')) exit('');

if (!isset($_POST['tables'])) { // No checkboxes clicked, and got past the JS check
	pathos_lang_loadDictionary('exporters','eql');
	echo TR_EXPORT_EQL_NEEDONETABLE;
} else { // All good
	if (!defined('SYS_BACKUP')) include_once(BASE.'subsystems/backup.php');
	
	ob_end_clean();
	
	$filename = str_replace(
		array('__DOMAIN__','__DB__'),
		array(str_replace('.','_',$_SERVER['HTTP_HOST']),DB_NAME),
		$_POST['filename']);
	$filename = strftime($filename,time()).'.eql';
	
	header('Content-type: application/octet-stream');
	header('Content-Disposition: inline; filename="'.$filename.'"');
	
	echo pathos_backup_dumpDatabase($db,array_keys($_POST['tables']));
	
	exit();
}

?>
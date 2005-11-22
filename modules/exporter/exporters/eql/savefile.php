<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

if (!defined('PATHOS')) exit('');

if (!isset($_POST['tables'])) { // No checkboxes clicked, and got past the JS check
	$i18n = pathos_lang_loadFile('modules/exporters/exporter/eql/savefile.php');
	echo $i18n['need_one'];
} else { // All good
	if (!defined('SYS_BACKUP')) require_once(BASE.'subsystems/backup.php');
	
	$filename = str_replace(
		array('__DOMAIN__','__DB__'),
		array(str_replace('.','_',HOSTNAME),DB_NAME),
		$_POST['filename']);
	$filename = preg_replace('/[^A-Za-z0-9_.-]/','-',strftime($filename,time()).'.eql');
	
	$filename = str_replace(
		array('__DOMAIN__','__DB__'),
		array(str_replace('.','_',HOSTNAME),DB_NAME),
		$_POST['filename']);
	$filename = preg_replace('/[^A-Za-z0-9_.-]/','-',strftime($filename,time()).'.eql');
	
	ob_end_clean();
	
	// This code was lifted from phpMyAdmin, but this is Open Source, right?
	
	// 'application/octet-stream' is the registered IANA type but
	//        MSIE and Opera seems to prefer 'application/octetstream'
	$mime_type = (PATHOS_USER_BROWSER == 'IE' || PATHOS_USER_BROWSER == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
	
	header('Content-Type: ' . $mime_type);
	header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	// IE need specific headers
	if (PATHOS_USER_BROWSER == 'IE') {
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	} else {
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Pragma: no-cache');
	}
	
	echo pathos_backup_dumpDatabase($db,array_keys($_POST['tables']));
	
	exit(''); // Exit, since we are exporting
}

?>
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
//GREP:HARDCODEDTEXT

// Part of the Extensions category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('extensions',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin == 1) {

	$template = new template('administrationmodule','_upload_finalSummary',$loc);

	$sessid = session_id();
	if (!file_exists(BASE."extensionuploads/$sessid") || !is_dir(BASE."extensionuploads/$sessid")) {
		$template->assign('nofiles',1);
	} else {
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
		$success = array();
		foreach (array_keys(pathos_files_listFlat(BASE."extensionuploads/$sessid",true,null,array(),BASE."extensionuploads/$sessid")) as $file) {
			if ($file != '/archive.tar' && $file != '/archive.tar.gz' && $file != 'archive.tar.bz2' && $file != '/archive.zip') {
				pathos_files_makeDirectory(dirname($file));
				$success[$file] = copy(BASE."extensionuploads/$sessid".$file,BASE.substr($file,1));
				if (basename($file) == 'views_c') chmod(BASE.substr($file,1),0777);
			}
		}
		
		$del_return = pathos_files_removeDirectory(BASE."extensionuploads/$sessid");
		echo $del_return;
		
		$template->assign('nofiles',0);
		$template->assign('success',$success);
		
		$template->assign('redirect',pathos_flow_get());
		
		ob_start();
		include(BASE.'modules/administrationmodule/actions/installtables.php');
		ob_end_clean();
	}
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
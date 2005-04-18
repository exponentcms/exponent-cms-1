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

// Part of the Extensions category.

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('extensions',pathos_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
	
	$sessid = session_id();
	$files = array();
	foreach (pathos_files_listFlat(BASE.'extensionuploads/'.$sessid,true,null,array(),BASE.'extensionuploads/'.$sessid) as $key=>$f) {
		if ($key != '/archive.tar' && $key != '/archive.tar.gz' && $key != '/archive.tar.bz2' && $key != '/archive.zip') {
			$files[] = array(
				'absolute'=>$key,
				'relative'=>$f,
				'canCreate'=>pathos_files_canCreate(BASE.substr($key,1)),
				'ext'=>substr($f,-3,3)
			);
		}
	}
	
	$template = new template('administrationmodule','_upload_filesList',$loc);
	$template->assign('relative','extensionuploads/'.$sessid);
	$template->assign('files',$files);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
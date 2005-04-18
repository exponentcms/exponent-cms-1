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

if (!defined('PATHOS')) exit('');

$dest_dir = $_POST['dest_dir'];
$files = array();
if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
foreach (array_keys($_POST['mods']) as $mod) {
	$files[$mod] = array(
		'',
		array()
	);
	if (class_exists($mod)) {
		$files[$mod][0] = call_user_func(array($mod,'name'));
	}
	foreach (array_keys(pathos_files_listFlat($dest_dir.'/files/'.$mod,1,null,array(),$dest_dir.'/files/'.$mod.'/')) as $file) {
		$files[$mod][1][$file] = pathos_files_canCreate(BASE.'files/'.$mod.'/'.$file);
	}
}

pathos_sessions_set('dest_dir',$dest_dir);
pathos_sessions_set('files_data',$files);

$template = new template('importer','_files_verifyFiles');
$template->assign('files_data',$files);
$template->output();


?>
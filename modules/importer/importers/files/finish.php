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

if (!defined('PATHOS')) exit('');

$dest_dir = pathos_sessions_get('dest_dir');
$files = pathos_sessions_get('files_data');
if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
if (!file_exists(BASE.'files')) {
	mkdir(BASE.'files',0777);
}
foreach (array_keys($files) as $mod) {
	pathos_files_copyDirectoryStructure($dest_dir.'/files/'.$mod,BASE.'files/'.$mod);
	foreach (array_keys($files[$mod][1]) as $file) {
		copy($dest_dir.'/files/'.$mod.'/'.$file,BASE.'files/'.$mod.'/'.$file);
	}
}

pathos_sessions_unset('dest_dir');
pathos_sessions_unset('files_data');

$template = new template('importer','_files_final');
$template->output();

?>
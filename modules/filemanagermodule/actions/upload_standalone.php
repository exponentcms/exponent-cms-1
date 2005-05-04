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

include_once('../../../pathos.php');

$collection = null;
if (isset($_POST['collection_id'])) {
	$collection = $db->selectObject('file_collection','id='.$_POST['collection_id']);
} else {
	$collection->id = 0;
	$collection->name = 'Uncategorized Files';
	$collection->description = 'Theses files have not been categorized yet,';
}
$loc = pathos_core_makeLocation('filemanagermodule');

// PERM CHECK
	$file = file::update('file','files',null);
	if (is_object($file)) {
		$file->name = $_POST['name'];
		$file->collection_id = $collection->id;
		$file_id = $db->insertObject($file,'file');
		header('Location: '.URL_FULL.'modules/filemanagermodule/actions/picker.php?id='.$collection->id.'&highlight_file='.$file_id);
	} else {
		echo $file;
	}
// END PERM CHECK

?>
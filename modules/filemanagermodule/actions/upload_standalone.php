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

include_once('../../../pathos.php');

$collection = null;
if (isset($_POST['collection_id'])) {
	$collection = $db->selectObject('file_collection','id='.intval($_POST['collection_id']));
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
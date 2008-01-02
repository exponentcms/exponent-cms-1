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

if (!defined('EXPONENT')) exit('');

//include_once('../../../exponent.php');

/*$collection = null;
if (isset($_POST['collection_id'])) {
	$collection = $db->selectObject('file_collection','id='.intval($_POST['collection_id']));
} else {
	$collection->id = 0;
	$collection->name = 'Uncategorized Files';
	$collection->description = 'Theses files have not been categorized yet,';
}*/
$loc = exponent_core_makeLocation('cermi');

// PERM CHECK
	$file = file::upload('file','files');
	if (is_object($file)) {
		$files = file::findFilesForItem($_POST['item_type'], $_POST['item_id']);
		$template = new template('cermi', '_files', $loc);
		$template->assign('files', $files);
		echo $template->render();
	} else {
		echo $file;
	}
// END PERM CHECK

?>

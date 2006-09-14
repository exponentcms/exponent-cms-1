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

$collection = null;
if (isset($_POST['collection_id'])) {
	$collection = $db->selectObject('file_collection','id='.intval($_POST['collection_id']));
}
$loc = exponent_core_makeLocation('filemanagermodule');

if ($collection) {
	// PERM CHECK
		//$file = file::update('file','files.php',null);
		$file = file::update('file',null,null);
		if (is_object($file)) {
			$file->name = $_POST['name'];
			$file->collection_id = $collection->id;
			$db->insertObject($file,'file');
			exponent_flow_redirect();
		} else {
			echo $file;
		}
	// END PERM CHECK
} else {
	echo SITE_404_HTML;
}

?>

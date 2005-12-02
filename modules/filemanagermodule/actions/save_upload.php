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

if (!defined('PATHOS')) exit('');

$collection = null;
if (isset($_POST['collection_id'])) {
	$collection = $db->selectObject('file_collection','id='.$_POST['collection_id']);
}
$loc = pathos_core_makeLocation('filemanagermodule');

if ($collection) {
	// PERM CHECK
		$file = file::update('file','files.php',null);
		if (is_object($file)) {
			$file->name = $_POST['name'];
			$file->collection_id = $collection->id;
			$db->insertObject($file,'file');
			pathos_flow_redirect();
		} else {
			echo $file;
		}
	// END PERM CHECK
} else {
	echo SITE_404_HTML;
}

?>
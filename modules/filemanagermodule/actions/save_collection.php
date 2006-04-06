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
if (isset($_POST['id'])) {
	$collection = $db->selectObject('file_collection','id='.intval($_POST['id']));
}
$loc = exponent_core_makeLocation('filemanagermodule');

// PERM CHECK
	$collection = file_collection::update($_POST,$collection);
	if (isset($collection->id)) {
		$db->updateObject($collection,'file_collection');
	} else {
		$db->insertObject($collection,'file_collection');
	}
	exponent_flow_redirect();
// END PERM CHECK

?>
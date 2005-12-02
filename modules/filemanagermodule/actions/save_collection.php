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
if (isset($_POST['id'])) {
	$collection = $db->selectObject('file_collection','id='.$_POST['id']);
}
$loc = pathos_core_makeLocation('filemanagermodule');

// PERM CHECK
	$collection = file_collection::update($_POST,$collection);
	if (isset($collection->id)) {
		$db->updateObject($collection,'file_collection');
	} else {
		$db->insertObject($collection,'file_collection');
	}
	pathos_flow_redirect();
// END PERM CHECK

?>
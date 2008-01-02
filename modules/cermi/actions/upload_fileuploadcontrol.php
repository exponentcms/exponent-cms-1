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

include_once('../../../exponent.php');

$loc = exponent_core_makeLocation('cermi');

// PERM CHECK
	$file = file::update('file','files',null);
	if (is_object($file)) {
		$file->name = $_POST['name'];
		$file->item_type = $_POST['item_type'];
		$file->item_id = $_POST['item_id'];
		$file_id = $db->insertObject($file,'file');
		header('Location: '.URL_FULL.'modules/cermi/actions/fileuploadcontrol.php?id='.$file_id);
	} else {
		echo $file;
	}
// END PERM CHECK

?>

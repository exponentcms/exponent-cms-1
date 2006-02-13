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

if (defined('PATHOS')) return;

include_once('pathos_bootstrap.php');
// Initialize the Imaging Subsystem (this does not need the Pathos Framework to function)
include_once('subsystems/image.php');

if (isset($_GET['id'])) {
	include_once('subsystems/config/load.php');
	// Initialize the Database Subsystem
	include_once(BASE.'subsystems/database.php');
	$db = pathos_database_connect(DB_USER,DB_PASS,DB_HOST.':'.DB_PORT,DB_NAME);
	
	$file_obj = $db->selectObject('file','id='. intval($_GET['id']));
	$_GET['file'] = $file_obj->directory.'/'.$file_obj->filename;
}

$file = BASE.$_GET['file'];

$thumb = null;

if (isset($_GET['constraint'])) {
	$thumb = pathos_image_scaleToConstraint($file,$_GET['width'],$_GET['height']);
} else if (isset($_GET['width'])) {
	$thumb = pathos_image_scaleToWidth($file,$_GET['width']);
} else if (isset($_GET['height'])) {
	$thumb = pathos_image_scaleToHeight($file,$_GET['height']);
} else if (isset($_GET['scale'])) {
	$thumb = pathos_image_scaleByPercent($file,$_GET['scale'] / 100);
}

if (is_resource($thumb)) pathos_image_output($thumb,pathos_image_sizeinfo($file));
else pathos_image_showFallbackPreviewImage(BASE,$thumb);

?>

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

if (defined('EXPONENT')) return;

include_once('exponent_bootstrap.php');
// Initialize the Imaging Subsystem (this does not need the Exponent Framework to function)
include_once('subsystems/image.php');

if (isset($_GET['id'])) {
	include_once('subsystems/config/load.php');
	// Initialize the Database Subsystem
	include_once(BASE.'subsystems/database.php');
	$db = exponent_database_connect(DB_USER,DB_PASS,DB_HOST.':'.DB_PORT,DB_NAME);
	
	$file_obj = $db->selectObject('file','id='. intval($_GET['id']));

    $_GET['file'] = $file_obj->directory.'/'.$file_obj->filename;
}


$file = BASE.$_GET['file'];
$thumb = null;

if (isset($_GET['constraint'])) {
	$thumb = exponent_image_scaleToConstraint($file,$_GET['width'],$_GET['height']);
} else if (isset($_GET['width'])) {
	$thumb = exponent_image_scaleToWidth($file,intval($_GET['width']));
} else if (isset($_GET['height'])) {
	$thumb = exponent_image_scaleToHeight($file,intval($_GET['height']));
} else if (isset($_GET['scale'])) {
	$thumb = exponent_image_scaleByPercent($file,intval($_GET['scale']) / 100);
}

$mythumb = getimagesize($file);

if ($mythumb[0] > 0 && $mythumb[1] > 0)
{
    if (is_resource($thumb)) {
        exponent_image_output($thumb,exponent_image_sizeinfo($file));
    } else {
        exponent_image_showFallbackPreviewImage(BASE,$thumb);
    }
}
?>

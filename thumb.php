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

$quality = isset($_GET['quality']) ? intval($_GET['quality']) : 75;

$file = BASE.$_GET['file'];
$thumb = null;

$sizeinfo = exponent_image_sizeinfo($filename);
if (!is_array($sizeinfo)) exit();

$original = exponent_image_createFromFile($filename,$sizeinfo);
if (!is_resource($original)) exit();

$w = $_GET['width'];
$h = $_GET['height'];

if (isset($_GET['constraint'])) {
	$w = $width;
	$h = ($width / $sizeinfo[0]) * $sizeinfo[1];

	if ($h > $height) { // height is outside
		$w = ($height / $sizeinfo[1]) * $sizeinfo[0];
		$h = $height;
	}
	//$thumb = exponent_image_scaleToConstraint($file,$_GET['width'],$_GET['height']);
} else if (isset($_GET['width'])) {
	$w = $width;
	$h = ($width / $sizeinfo[0]) * $sizeinfo[1];
	//$thumb = exponent_image_scaleToWidth($file,intval($_GET['width']));
} else if (isset($_GET['height'])) {
	$w = ($height / $sizeinfo[1]) * $sizeinfo[0];
	$h = $height;
	//$thumb = exponent_image_scaleToHeight($file,intval($_GET['height']));
} else if (isset($_GET['scale'])) {
	$scale = intval($_GET['scale']) / 100;
	$w = $scale * $sizeinfo[0];
	$h = $scale * $sizeinfo[1];
	//$thumb = exponent_image_scaleByPercent($file,intval($_GET['scale']) / 100);
} else if (isset($_GET['square'])) {
 	//$thumb = exponent_image_scaleToSquare($file,intval($_GET['square']));
}

//check for cached file
$thumbpath = BASE.'files/thumbs/';
$cachefile = $filename."-".$w."x".$h;
//Check to see if the directory exists.  If not, create the directory structure.
if (!file_exists($thumbpath)) exponent_files_makeDirectory($thumbpath);
if (file_exists($thumbpath.$cachefile)) {
	$fh = fopen($thumbpath.$cachefile,'rb');
	$img = fread($fh);
	fclose($fh);
	header('Content-type: '.$sizeinfo['mime']);
	echo $img;
	exit();
}

$thumb = exponent_image_create($w,$h);
if (!$thumb) exit();
exponent_image_copyresized($thumb,$original,0,0,0,0,$w,$h,$sizeinfo[0],$sizeinfo[1]);

$mythumb = getimagesize($file);

if ($mythumb[0] > 0 && $mythumb[1] > 0) {
    if (is_resource($thumb)) {
        exponent_image_output($thumb, exponent_image_sizeinfo($file), null, $quality);
    } else {
        exponent_image_showFallbackPreviewImage(BASE, $thumb);
    }
}
?>

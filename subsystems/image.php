<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SYS_IMAGE",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_showFallbackPreviewImage($base) {
	$fh = fopen($base."subsystems/image/default_preview.gif","rb");
	$img = fread($fh,65536);
	fclose($fh);
	header("Content-type: image/gif");
	echo $img;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_sizeinfo($filename) {
	if (!is_readable($filename)) return null;
	$sizeinfo = getimagesize($filename);
	if (!isset($sizeinfo['mime'])) {
		$types = array(
			"jpg"=>"image/jpeg",
			"jpeg"=>"image/jpeg",
			"gif"=>"image/gif",
			"png"=>"image/png"
		);
		$lowerfile = strtolower($filename);
		foreach ($types as $type=>$mime) {
			if (substr($lowerfile,-1*strlen($type),strlen($type)) == $type) $sizeinfo['mime'] = $mime;
		}
	}
	
	return $sizeinfo;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_createFromFile($filename,$sizeinfo) {
	$info = gd_info();

	if ($sizeinfo['mime'] == "image/jpeg" && $info["JPG Support"] == true) {
		return imagecreatefromjpeg($_GET['file']);
	} else if ($sizeinfo['mime'] == "image/png" && $info["PNG Support"] == true) {
		return imagecreatefrompng($_GET['file']);
	} else if ($sizeinfo['mime'] == "image/gif" && $info["GIF Read Support"] == true) {
		return imagecreatefromgif($_GET['file']);
	} else {
		return null;
	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_create($w,$h) {
	$info = gd_info();
	if (strpos($info['GD Version'],"2.0") != false) return imagecreatetruecolor($w,$h);
	else return imagecreate($w,$h);
}

// $scale should be in decimal notation (i.e. 0.5 for 50%)
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_scaleByPercent($filename,$scale) {
	$sizeinfo = pathos_image_sizeinfo($filename);
	if (!$sizeinfo) return null;
	$original = pathos_image_createFromFile($filename,$sizeinfo);
	if (!$original) return null;
	
	if ($scale == 1) return $original;
	
	$w = $scale * $sizeinfo[0];
	$h = $scale * $sizeinfo[1];
	
	$thumb = pathos_image_create($w,$h);
	if (!$thumb) return null;
	imagecopyresized($thumb,$original,0,0,0,0,$w,$h,$sizeinfo[0],$sizeinfo[1]);
	
	return $thumb;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_scaleToWidth($filename,$width) {
	$sizeinfo = pathos_image_sizeinfo($filename);
	if (!$sizeinfo) return null;
	$original = pathos_image_createFromFile($filename,$sizeinfo);
	if (!$original) return null;
	
	if ($width == $sizeinfo[0]) return $original;
	
	$w = $width;
	$h = ($width / $sizeinfo[0]) * $sizeinfo[1];
	
	$thumb = pathos_image_create($w,$h);
	if (!$thumb) return null;
	imagecopyresized($thumb,$original,0,0,0,0,$w,$h,$sizeinfo[0],$sizeinfo[1]);
	
	return $thumb;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_scaleToHeight($filename,$height) {
	$sizeinfo = pathos_image_sizeinfo($filename);
	if (!$sizeinfo) return null;
	$original = pathos_image_createFromFile($filename,$sizeinfo);
	if (!$original) return null;
	
	if ($height == $sizeinfo[1]) return $original;
	
	$w = ($height / $sizeinfo[1]) * $sizeinfo[0];
	$h = $height;
	
	$thumb = pathos_image_create($w,$h);
	if (!$thumb) return null;
	imagecopyresized($thumb,$original,0,0,0,0,$w,$h,$sizeinfo[0],$sizeinfo[1]);
	
	return $thumb;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_scaleToConstraint($filename,$width,$height) {
	$sizeinfo = pathos_image_sizeinfo($filename);
	if (!$sizeinfo) return null;
	$original = pathos_image_createFromFile($filename,$sizeinfo);
	if (!$original) return null;
	
	if ($width >= $sizeinfo[0] && $height >= $sizeinfo[1]) return $original;
	
	$w = $width;
	$h = ($width / $sizeinfo[0]) * $sizeinfo[1];
	
	if ($h > $height) { // height is outside
		$w = ($height / $sizeinfo[1]) * $sizeinfo[0];
		$h = $height;
	}
	
	$thumb = pathos_image_create($w,$h);
	if (!$thumb) return null;
	imagecopyresized($thumb,$original,0,0,0,0,$w,$h,$sizeinfo[0],$sizeinfo[1]);
	
	return $thumb;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_scaleManually($filename,$width,$height) {
	$sizeinfo = pathos_image_sizeinfo($filename);
	if (!$sizeinfo) return null;
	$original = pathos_image_createFromFile($filename,$sizeinfo);
	if (!$original) return null;
	
	if ($width == $sizeinfo[0] && $height == $sizeinfo[1]) return $original;
	
	$thumb = pathos_image_create($width,$height);
	if (!$thumb) return null;
	imagecopyresized($thumb,$original,0,0,0,0,$width,$height,$sizeinfo[0],$sizeinfo[1]);
	
	return $thumb;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_image_output($img,$sizeinfo,$filename = null) {
	header("Content-type: " . $sizeinfo['mime']);
	if ($sizeinfo['mime'] == "image/jpeg") {
		($filename != null) ? imagejpeg($img,$filename) : imagejpeg($img);
	} else if ($sizeinfo['mime'] == "image/png") {
		($filename != null) ? imagepng($img,$filename) : imagepng($img);
	} else if ($sizeinfo['mime'] == "image/gif") {
		($filename != null) ? imagepng($img,$filename) : imagepng($img);
	}
}

?>
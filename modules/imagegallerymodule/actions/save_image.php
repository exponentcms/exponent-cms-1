<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: save_image.php,v 1.6 2005/05/09 05:54:38 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$image = null;
if (isset($_POST['id'])) {
	$image = $db->selectObject('imagegallery_image','id='.$_POST['id']);
} else {
	$image->gallery_id = $_POST['parent'];
}

if (isset($_POST['gid'])) {
	$image->gallery_id = $_POST['gid'];
}

$gallery = $db->selectObject('imagegallery_gallery','id='.$image->gallery_id);
$loc = unserialize($gallery->location_data);
$loc->int = $gallery->id;

if (exponent_permissions_check('manage',$loc)) {
	$image = imagegallery_image::update($_POST,$image);
	
	// Re-order for ranking
	if (isset($image->id)) {
		// changed rank for an existing image
		if ($_POST['rank'] < $image->rank) {
			// New rank is before the current rank. Item moved up
			$db->increment('imagegallery_image','rank',1,'gallery_id='.$image->gallery_id.' AND rank >= '.$_POST['rank'] . ' AND rank < ' . $image->rank);
		} else if ($_POST['rank'] > $image->rank) {
			// New rank is after the current rank. Item moved down
			$db->decrement('imagegallery_image','rank',1,'gallery_id='.$image->gallery_id.' AND rank < '.$_POST['rank'] . ' AND rank >= ' . $image->rank);
			$_POST['rank']--;
		}
		// Rank didn't change
		$image->rank = $_POST['rank'];
	} else {
		$image->rank = $_POST['rank'];
		$db->increment('imagegallery_image','rank',1,"gallery_id=".$image->gallery_id." AND rank >= ".$_POST['rank'] . " AND rank < " . $image->rank);
	}
	$loc = unserialize($gallery->location_data);
	if (!isset($image->id)) {
		$dir = 'files/imagegallerymodule/'.$loc->src.'/gallery'.$gallery->id;
		$file = file::update('file',$dir,null);
		if (is_object($file)) {
			
			$thumbname = imagegallerymodule::createThumbnailFile($file, $gallery->box_size); 
			$image->thumbnail = $thumbname; 
			$popname = imagegallerymodule::createEnlargedFile($file, $gallery->pop_size); 
			$image->enlarged = $popname;
			 
			$image->file_id = $db->insertObject($file,'file');
		} else {
			// If file::update() returns a non-object, it should be a string.  That string is the error message.
			$post = $_POST;
			$post['_formError'] = $file;
			exponent_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
	
	if (isset($image->id)) {
		$db->updateObject($image,'imagegallery_image');
	} else {
		$image->posted = time();
		$db->insertObject($image,"imagegallery_image");
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
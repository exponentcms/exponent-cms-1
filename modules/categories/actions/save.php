<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# Copyright (c) 2007 ACYSOS S.L. Modified by Ignacio Ibeas
# Added subcategory function
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

if (!defined("EXPONENT")) exit("");

$cat = null;
$old_parent = null;
if (isset($_POST['id'])) $cat = $db->selectObject("category","id=".intval($_POST['id']));
if ($cat) {
	$loc = unserialize($cat->location_data);
	$old_parent = $cat->parent;
} else {
	$loc->mod = $_POST['orig_module']; // Only need to update the module.
	$cat->rank = $_POST['rank'];
	// $cat->rank = $db->max('category', 'rank', 'location_data', "location_data='".serialize($loc)."'");
	// if ($cat->rank === null) {
		// $cat->rank = 0;
	// } else { 
		// $cat->rank ++;
	// }
}
if (exponent_permissions_check('manage_categories',$loc)) {
	$cat = category::update($_POST,$cat);
	$cat->location_data = serialize($loc);

	if (!defined('SYS_FILES')) require(BASE.'subsystems/files.php');
	$directory = 'files/category/'.$loc->src;
	if (!file_exists(BASE.$directory)) {
		$err = exponent_files_makeDirectory($directory);
		if ($err != SYS_FILES_SUCCESS) {
			exponent_lang_loadDictionary('modules','filemanager');
			$template->assign('noupload',1);
			$template->assign('uploadError',$err);
		}
	}
	$filefield = 'file';
	
	if (isset($_FILES[$filefield]) && $_FILES[$filefield]['name'] != '') {
		if (isset($cat->file_id) && ($cat->file_id != 0)) {
			$file = $db->selectObject('file','id='.$cat->file_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
		}
		$file = file::update($filefield,$directory,null);
		if ($file != null) {
			$cat->file_id = $db->insertObject($file,'file');
		}
	}

	if (isset($cat->id)) {
		if ($cat->parent != $old_parent) {
			$cat = category::changeParent($cat,$old_parent,$cat->parent);
		}
		$db->updateObject($cat,"category");
	} else {
		$db->increment('category','rank',1,'rank >= ' . $cat->rank . ' AND parent=' . $cat->parent);
		$db->insertObject($cat,"category");
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
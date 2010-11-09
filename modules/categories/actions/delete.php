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

if (!defined('EXPONENT')) exit('');

$cat = null;
if (isset($_GET['id'])) {
	$cat = $db->selectObject('category','id='.intval($_GET['id']));
}

if ($cat) {
	$loc = unserialize($cat->location_data);
	$loc->mod = $_GET['orig_module'];
	if (exponent_permissions_check('manage_categories',$loc)) {
		$db->delete("category","id=".$cat->id);
		$db->decrement('category', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$cat->rank." AND parent=".$cat->parent);

		if ($cat->file_id != 0) {
		$file = $db->selectObject('file','id='.$cat->file_id);
		file::delete($file);
		$db->delete('file','id='.$file->id);}

		//Remove the subcategories
		$kids = $db->selectObjects("category","parent=".$cat->id." AND location_data='".serialize($loc)."'");

		for ($i = 0; $i < count($kids); $i++) {
			$db->delete("category","id=".$kids[$i]->id);
			$db->decrement('category', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$kids[$i]->rank." AND parent=".$kids[$i]->parent);
			if ($kids[$i]->file_id != 0) {
				$file = $db->selectObject('file','id='.$kids[$i]->file_id);
				file::delete($file);
				$db->delete('file','id='.$file->id);
			}
		}

		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
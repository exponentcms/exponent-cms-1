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
# $Id: save_listing.php,v 1.4 2005/04/12 16:06:11 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

	$listing = null;		
	if (isset($_POST['id'])) {
		$listing = $db->selectObject('listing', 'id='.$_POST['id']);
		if ($listing != null) {
			$loc = unserialize($listing->location_data);
		} 
	} else {
		$listing->rank = $db->max('listing', 'rank', 'location_data', "location_data='".serialize($loc)."'");
		if ($listing->rank == null) {
			$listing->rank = 0;
		} else { 
			$listing->rank += 1;
		}
	}
	
	if (exponent_permissions_check("manage",$loc)) {	
		//Get the file save it to the temp directory
		$source = $loc->src;
		$directory = 'files/listingmodule/'.$source;
		$file = null;
		if ($_FILES['upload']['name'] != '') {
			$file = file::update("upload",$directory,null,time()."_".$_FILES['upload']['name']);
			if ($file == null) {
				switch($_FILES["upload"]["error"]) {
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						$post['_formError'] = "The file you attempted to upload is too large.  Contact your system administrator if this is a problem.";
						break;
					case UPLOAD_ERR_PARTIAL:
						$post['_formError'] = "The file was only partially uploaded.";
						break;
					case UPLOAD_ERR_NO_FILE:
						$post['_formError'] = "No file was uploaded.";
						break;
					default:
						$post['_formError'] = "A strange internal error has occured.  Please contact the Exponent Developers.";
						break;
				}
				exponent_sessions_set("last_POST",$post);
				header("Location: " . $_SERVER['HTTP_REFERER']);
				exit("");
			}
		}
		
		$listing = listing::update($_POST, $listing);
		$listing->location_data = serialize($loc);
		if ($file != null) {
			$listing->file_id = $db->insertObject($file, 'file');
		} else {
			if (!isset($listing->id)) {
				$listing->file_id = 0;
			}
		}
		
		if (isset($listing->id)) {
			$db->updateObject($listing,"listing");
		} else {
			$db->insertObject($listing,"listing");
		}		
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	

?>
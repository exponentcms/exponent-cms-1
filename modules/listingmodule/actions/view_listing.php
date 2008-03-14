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
# $Id: view_listing.php,v 1.2 2005/02/19 16:53:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	$listing = null;
	if (isset($_GET['id'])) {
		$listing = $db->selectObject("listing","id=".$_GET['id']);
		if ($listing != null) {
			$loc = unserialize($listing->location_data);
		} else {
			echo SITE_404_HTML;
		}
	}	
	
	global $db;
	if ($listing->file_id!=0) {
		$file = $db->selectObject('file', "id=".$listing->file_id);
		$listing->picpath = $file->directory."/".$file->filename;
	} else {
		$listing->picpath = "";
	}
	
	$template = new template("listingmodule","_viewlisting",$loc);
	$template->assign('listing', $listing);
	$template->output();
?>
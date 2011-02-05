<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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
##################################################

if (!defined("EXPONENT")) exit("");

$listing = null;
if (isset($_GET['id'])) {
	$listing = $db->selectObject('listing', 'id='.$_GET['id']);
	if ($listing != null) {
		$loc = unserialize($listing->location_data);
	}
}

if ($listing) {
	$loc = unserialize($listing->location_data);
	if (exponent_permissions_check('manage',$loc) || exponent_permissions_check('delete',$loc)) {
		if ($listing->file_id != '') {
        $file = $db->selectObject('file', 'id='.$listing->file_id);
        file::delete($file);
        $db->delete('file','id='.$file->id);
      }
		$db->delete('listing', 'id='.$_GET['id']);
		$db->decrement('listing', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$listing->rank." AND category_id=".$listing->category_id);
		$db->delete("listing_wf_info","real_id=".$_GET['id']);
		$db->delete("listing_revision","wf_original=".$_GET['id']);		
		//Delete search entries
		$db->delete('search',"ref_module='listingmodule' AND ref_type='listing' AND original_id=".$listing->id);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>

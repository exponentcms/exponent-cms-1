<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

// Bail in case someone has visited us directly, or the Pathos framework is
// otherwise not initialized.
if (!defined('PATHOS')) exit('');

$check_id = -1;
$section = null;
$old_parent = null;
if (isset($_POST['id'])) {
	// Saving an existing content page.  Read it from the database.
	$section = $db->selectObject('section','id='.$_POST['id']);
	if ($section) {
		$old_parent = $section->parent;
		$check_id = $section->id;
	}
}

echo $old_parent;

// Update the section from the _POST data.
$section = section::update($_POST,$section);
if ($check_id == -1) {
	$check_id = $section->parent;
}

if ($check_id != -1 && pathos_permissions_check('manage',pathos_core_makeLocation('navigationmodule','',$check_id))) {
	if (isset($section->id)) {
		if ($section->parent != $old_parent) {
			// Old_parent id was different than the new parent id.  Need to decrement the ranks
			// of the old children (after ours), and then add 
			$section = section::changeParent($section,$old_parent,$section->parent);
		}
	
		// Existing section.  Update the database record.
		// The 'id=x' where clause is implicit with an updateObject
		$db->updateObject($section,'section');
	} else {
		// Since this is new, we need to increment ranks, in case the user
		// added it in the middle of the level.
		$db->increment('section','rank',1,'rank >= ' . $section->rank . ' AND parent=' . $section->parent);
		// New section.  Insert a new database record.
		$db->insertObject($section,'section');
	}
	
	// Go back to where we came from.  Probably the navigation manager.
	pathos_flow_redirect();
}

?>
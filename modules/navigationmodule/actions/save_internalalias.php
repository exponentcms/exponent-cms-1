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

// Bail in case someone has visited us directly, or the Pathos framework is
// otherwise not initialized.
if (!defined('PATHOS')) exit('');

// FIXME: Allow non-administrative users to manage certain
// FIXME: parts of the section hierarchy.
if ($user && $user->is_acting_admin == 1) {
	$section = null;
	if (isset($_POST['id'])) {
		// Saving an existing content page.  Read it from the database.
		$section = $db->selectObject('section','id='.$_POST['id']);
	}
	// Update the section from the _POST data.
	$section = section::updateInternalAlias($_POST,$section);
	if ($section->active == 0) {
		// User tried to link to an inactive section.  This makes little or no sense in
		// this context, so throw them back to the edit form, with an error message.
		$_POST['_formError'] = 'You cannot link to an inactive section.  Inactive sections are shown with "(" and ")" around their names in the selection list.';
		pathos_sessions_set("last_POST",$_POST);
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit('');
	}
	
	if (isset($section->id)) {
		// Existing section.  Update the database record.
		// The 'id=x' WHERE clause is implicit with an updateObject
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
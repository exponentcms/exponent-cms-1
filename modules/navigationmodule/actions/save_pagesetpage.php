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
	// Update the section from the _POST data.
	$section = section::updatePageset($_POST,$section);
	
	// Still have to do some pageset processing, mostly handled by a handy
	// member method of the navigationmodule class.
	
	// Since this is new, we need to increment ranks, in case the user
	// added it in the middle of the level.
	$db->increment('section','rank',1,'rank >= ' . $section->rank . ' AND parent=' . $section->parent);
	
	// New section (Pagesets always are).  Insert a new database
	// record, and save the ID for the processing methods that need them.
	$section->id = $db->insertObject($section,'section');
	// Process the pageset, to add sections and subsections, as well as default content
	// that the pageset writer added to each element of the set.
	navigationmodule::process_section($section,$_POST['pageset']);
	
	// Go back to where we came from.  Probably the navigation manager.
	pathos_flow_redirect();
}

?>
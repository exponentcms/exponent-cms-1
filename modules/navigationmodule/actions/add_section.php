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

// First, retrieve the parent section from the database.
$parent = null;
if (isset($_GET['parent'])) {
	// May have been passed a '0', indicating that we want a top-level section
	if ($_GET['parent'] <= 0) {
		// Set $parent->id to passed value, so that $parent is not null.  The view will use this information
		// to output the appropriate messages to the user.
		$parent->id = $_GET['parent'];
	} else {
		// Passed a non-zero parent id - Adding a subsection.  Try to read
		// the parent from the database.
		$parent = $db->selectObject('section','id='.$_GET['parent']);
	}
}

// Check to see that A) a parent ID was passed in GET, and B) the id was valid
if ($parent) {
	if (pathos_permissions_check('manage',pathos_core_makeLocation('navigationmodule','',$parent->id))) {
		// For this action, all we need to do is output a basically
		// non-variable template the asks the user what type of page
		// they want to add to the site Navigation.
		
		$template = new template('navigationmodule','_add_whichtype');
		// We do, however need to know if there are any Pagesets.
		$template->assign('havePagesets',($db->countObjects('section_template','parent=0') && $parent->id >= 0));
		// We also need to know if there are any standalone pages.
		$template->assign('haveStandalone',($db->countObjects('section','parent=-1') && $parent->id >= 0));
		// Assign the parent we were passed, so that it can propagated along to the actual form action.
		$template->assign('parent',$parent);
		$template->assign('isAdministrator',($user && $user->is_acting_admin ? 1 : 0));
		$template->output();
	} else {
		// Current user is not allowed to manage sections.  Throw a 403.
		echo SITE_403_HTML;
	}
} else {
	// Passed parent id was invalid.  Throw a 404
	echo SITE_404_HTML;
}

?>
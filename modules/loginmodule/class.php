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
class loginmodule {
	function name() { return "Login Module"; }
	function author() { return "James Hunt"; } 
	function description() { return "Allows users to login to the site."; }
	
	function hasContent() { return false; }
	function hasSources() { return false; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array();
	}
	
	function deleteIn($loc) {
	
	}
	
	function copyContent($from_loc,$to_loc) {
	
	}

	function show($view,$loc=null,$title="") {
		$template = new template("loginmodule",$view,$loc);
		$template->assign("title",$title);
		if (pathos_sessions_loggedIn()) {
			global $user, $db;
			$template->assign("loggedin",1);
			$template->assign("user",$user);
			// Need to check for groups and whatnot
			if ($db->countObjects('groupmembership','member_id='.$user->id.' AND is_admin=1')) {
				$template->assign('is_group_admin',1);
			} else {
				$template->assign('is_group_admin',0);
			}
		} else {
			$template->assign("loggedin",0);
		}
		$template->output($view);
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
	}
	
}
?>
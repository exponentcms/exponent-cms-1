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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_checkOnModule('manage','navigationmodule')) {
	pathos_flow_set(SYS_FLOW_PROTECTED, SYS_FLOW_ACTION);
	
	$template = new template("navigationmodule","_manager",$loc);
	
	$sections = navigationmodule::getHierarchy();
	$last_manage_depth = -1;
	$last_manage_id = -1;
	$last_admin_depth = -1;
	$last_admin_id = -1;
	
	if ($user && $user->is_acting_admin) {
		foreach ($sections as $id=>$section) {
			$sections[$id]->canManage = 1;
			$sections[$id]->canManageRank = 1;
			$sections[$id]->canAdmin = 1;
		}
	} else {
		foreach ($sections as $id=>$section) {
			if ($last_manage_depth == -1 && pathos_permissions_check('manage',pathos_core_makeLocation('navigationmodule','',$id))) {
				$sections[$id]->canManage = 1;
				$sections[$id]->canManageRank = 0;
				$last_manage_depth = $section->depth;
				$last_manage_id = $section->id;
			} else if ($section->depth <= $last_manage_depth) {
				$last_manage_depth = -1;
			} else {
				$sections[$id]->canManage = ($last_manage_depth == -1 ? 0 : 1);
				$sections[$id]->canManageRank = $sections[$id]->canManage;
			}
			
			if ($last_admin_depth == -1 && pathos_permissions_check('administrate',pathos_core_makeLocation('navigationmodule','',$id))) {
				$sections[$id]->canAdmin = 1;
				$last_admin_depth = $section->depth;
				$last_admin_id = $section->id;
			} else if ($section->depth <= $last_admin_depth) {
				$last_admin_depth = -1;
			} else {
				$sections[$id]->canAdmin = ($last_admin_depth == -1 ? 0 : 1);
			}
		}
	}
	
	$template->assign('isAdministrator',($user && $user->is_acting_admin ? 1 : 0));
	$template->assign("sections",$sections);
	// Templates
	$tpls = $db->selectObjects("section_template","parent='0'");
	$template->assign("templates",$tpls);
	$template->output();
}

?>
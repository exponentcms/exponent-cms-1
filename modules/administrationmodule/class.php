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

class administrationmodule {
	function name() { return "Administration Control Panel"; }
	function author() { return "James Hunt"; }
	function description() { return "A control panel that gives administrators easy access to administrative tasks"; }
	
	function hasContent() { return false; }
	function hasSources() { return false; }
	function hasViews()   { return true;  }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		// Do nothing (for now)
		if (is_readable(BASE."modules/administrationmodule/tasks")) {
			$menu = array();
			$dh = opendir(BASE."modules/administrationmodule/tasks");
			while (($file = readdir($dh)) !== false) {
				if (is_readable(BASE."modules/administrationmodule/tasks/$file") && is_file(BASE."modules/administrationmodule/tasks/$file")) {
					$menu = array_merge($menu,include(BASE."modules/administrationmodule/tasks/$file"));
				}
			}
		}
		$permissions = array("administrate"=>"Administrate");
		foreach (array_keys($menu) as $header) {
			$permissions[strtolower(str_replace(' ','_',$header))] = $header;
		}
		return $permissions;
	}
	
	function deleteIn($loc) {
		// Do nothing, no content
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
	function show($view,$loc = null,$title = "") {
		global $user;
		if (is_readable(BASE."modules/administrationmodule/tasks")) {
			$menu = array();
			$dh = opendir(BASE."modules/administrationmodule/tasks");
			while (($file = readdir($dh)) !== false) {
				if (substr($file,-4,4) == '.php' && is_readable(BASE."modules/administrationmodule/tasks/$file") && is_file(BASE."modules/administrationmodule/tasks/$file")) {
					$menu = array_merge($menu,include(BASE."modules/administrationmodule/tasks/$file"));
				}
			}
		}
		$template = new Template("administrationmodule",$view,$loc);
		$template->assign("menu",$menu);
		$template->assign("moduletitle",$title);
		$template->assign("user",$user);
		$template->assign('check_permissions',array_flip(administrationmodule::permissions()));
		$template->register_permissions(array_keys(administrationmodule::permissions()),pathos_core_makeLocation("administrationmodule"));
		
		$template->output($view);
	}
}

?>
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

class sharedcoremodule {
	function name() { return "Multi-Site Manager"; }
	function description() { return "Allows new 'Shared Codebase' Exponent sites to be created from the web."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','sharedcoremodule');
		return array(
			'administrate'=>TR_SHAREDCOREMODULE_PERM_ADMIN,
			//'configure'=>TR_SHAREDCOREMODULE_PERM_CONFIG,
			'manage_site'=>TR_SHAREDCOREMODULE_PERM_MANAGESITE,
			'manage_core'=>TR_SHAREDCOREMODULE_PERM_MANAGECORE,
		);
	}
	
	function deleteIn($loc) {
		// FIXME:Implement deleteIn for sharedcoremodule
		// FIXME:
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
	
	function show($view,$loc = null,$title="") {
		$template = new template("sharedcoremodule",$view);
		
		global $db;
		$cores = array();
		foreach ($db->selectObjects("sharedcore_core") as $c) {
			if (file_exists($c->path."pathos_version.php")) {
				$c->version = include($c->path."pathos_version.php");
				$c->linked = $db->selectObjects("sharedcore_site","core_id=".$c->id);
				$cores[] = $c;
			}	
		}
		$template->assign("cores",$cores);
		$template->assign("moduletitle",$title);
		$template->register_permissions(
			array('administrate','manage'),$loc);
		
		$template->output();
	}
	
	function getLocationHierarchy($loc) {
		return array($loc);
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
}

?>

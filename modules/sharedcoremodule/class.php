<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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
	function name() { return pathos_lang_loadKey('modules/sharedcoremodule/class.php','module_name'); }
	function description() { return pathos_lang_loadKey('modules/sharedcoremodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/sharedcoremodule/class.php');
		
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'manage_site'=>$i18n['perm_manage_site'],
			'manage_core'=>$i18n['perm_manage_core'],
		);
	}
	
	function deleteIn($loc) {
		// FIXME:Implement deleteIn for sharedcoremodule
		// FIXME:
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
	
	function show($view,$loc = null,$title='') {
		$template = new template('sharedcoremodule',$view);
		
		global $db;
		$cores = array();
		foreach ($db->selectObjects('sharedcore_core') as $c) {
			if (file_exists($c->path.'pathos_version.php')) {
				$c->version = include($c->path.'pathos_version.php');
				$c->linked = $db->selectObjects('sharedcore_site','core_id='.$c->id);
				$cores[] = $c;
			}	
		}
		$template->assign('cores',$cores);
		$template->assign('moduletitle',$title);
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

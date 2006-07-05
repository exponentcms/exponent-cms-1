<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class sharedcoremodule {
	function name() { return exponent_lang_loadKey('modules/sharedcoremodule/class.php','module_name'); }
	function author() { return exponent_lang_loadKey('modules/sharedcoremodule/class.php','module_author'); }
	function description() { return exponent_lang_loadKey('modules/sharedcoremodule/class.php','module_description'); }

	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/sharedcoremodule/class.php');

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
			if (file_exists($c->path.'exponent_version.php')) {
				$c->version = include($c->path.'exponent_version.php');
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

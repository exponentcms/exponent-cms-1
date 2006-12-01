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

class searchmodule {
	function name() { return exponent_lang_loadKey('modules/searchmodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/searchmodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return true; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/searchmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure']
		);
	}
	
	function show($view,$loc = null, $title = '') {
		$template = new template('searchmodule',$view,$loc);
		
		$template->assign('loc',$loc);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure'),$loc);
		$template->output();
	}
	
	function deleteIn($loc) {
		// Do nothing, no content
	}
	
	function copyContent($oloc,$nloc) {
		// Do nothing, no content
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
}

?>
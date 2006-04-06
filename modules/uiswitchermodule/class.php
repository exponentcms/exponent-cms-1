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

class uiswitchermodule {
	function name() { return exponent_lang_loadKey('modules/uiswitchermodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/uiswitchermodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return false; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		return array();
	}
	
	function show($view,$loc = null, $title = '') {
		$ui_levels = exponent_sessions_get('uilevels');
		if (count($ui_levels)) {
			$template = new template('uiswitchermodule',$view,$loc);
			$template->assign('levels',$ui_levels);
			$default = (exponent_sessions_isset('uilevel') ? exponent_sessions_get('uilevel') : max(array_keys($ui_levels)));
			$template->assign('default_level',$default);
			$template->output();
		}
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
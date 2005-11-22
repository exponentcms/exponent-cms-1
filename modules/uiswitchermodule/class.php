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

class uiswitchermodule {
	function name() { return pathos_lang_loadKey('modules/uiswitchermodule/class.php','module_name'); }
	function description() { return pathos_lang_loadKey('modules/uiswitchermodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return false; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		return array();
	}
	
	function show($view,$loc = null, $title = '') {
		$ui_levels = pathos_sessions_get('uilevels');
		if (count($ui_levels)) {
			$template = new template('uiswitchermodule',$view,$loc);
			$template->assign('levels',$ui_levels);
			$default = (pathos_sessions_isset('uilevel') ? pathos_sessions_get('uilevel') : max(array_keys($ui_levels)));
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
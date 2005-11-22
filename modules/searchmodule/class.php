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

class searchmodule {
	function name() { return pathos_lang_loadKey('modules/searchmodule/class.php','module_name'); }
	function description() { return pathos_lang_loadKey('modules/searchmodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return true; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/searchmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure']
		);
	}
	
	function show($view,$loc = null, $title = '') {
		$template = new template('searchmodule',$view,$loc);
		
		$template->assign('loc',$loc);
		
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
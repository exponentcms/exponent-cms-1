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

class rotatormodule {
	function name() { return "Content Rotator"; }
	function description() { return "Randomly displays a piece of text (which may contain images) to the user every time the page is refreshed."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','rotatormodule');
		return array(
			'administrate'=>TR_ROTATORMODULE_PERM_ADMIN,
			'manage'=>TR_ROTATORMODULE_PERM_MANAGE
		);
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
		
		$obj = null;
		$o = $db->selectObjects('rotator_item',"location_data='".serialize($loc)."'");
		$num = rand(0,count($o)-1);
		if (isset($o[$num])) {
			$obj = $o[$num];
		} else {
			$obj->text = '';
		}
		$template = new template('rotatormodule',$view,$loc);
		$template->assign('moduletitle',$title);
		$template->assign('content',$obj);
		$template->register_permissions(
			array('administrate','manage'),
			$loc);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('rotator_item',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
	
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
	}
	
}

?>
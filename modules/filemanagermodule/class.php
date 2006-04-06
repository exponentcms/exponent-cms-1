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

class filemanagermodule {
	function name() { return 'Exponent File Manager'; }
	function description() { return 'Manages all uploaded files for the site.'; }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		}
	}
	
	function show($view,$loc = null, $title = "") {
		$loc = exponent_core_makeLocation('filemanagermodule');
		
		global $db;
		$collections = $db->selectObjects('file_collection');
		
		$template = new template('filemanagermodule',$view,$loc);
		$template->assign('collections',$collections);
		
		$template->output();
	}
	
	function deleteIn($loc) {
	
	}
	
	function copyContent($oloc,$nloc) {
	
	}
}

?>
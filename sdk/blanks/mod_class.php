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

class CHANGEME {
	function name() { return ''; }
	function description() { return ''; }
	function author() { return ''; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		if ($internal == '') {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
			);
		} else {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
			);
		}
	}
	
	function show($view,$loc = null, $title = '') {
	
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
}

?>
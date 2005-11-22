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

class administrationmodule {
	function name() { return pathos_lang_loadKey('modules/administrationmodule/class.php','module_name'); }
	function author() { return 'James Hunt'; }
	function description() { return pathos_lang_loadKey('modules/administrationmodule/class.php','module_description'); }
	
	function hasContent() { return false; }
	function hasSources() { return false; }
	function hasViews()   { return true;  }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		$i18n = pathos_lang_loadFile('modules/administrationmodule/class.php');
		
		$permissions = array('administrate'=>$i18n['perm_admin']);
		
		$menu = array();
		$dir = BASE.'modules/administrationmodule/tasks';
		if (is_readable($dir)) {
			$dh = opendir($dir);
			while (($file = readdir($dh)) !== false) {
				if (substr($file,-4,4) == '.php' && is_readable($dir.'/'.$file) && is_file($dir.'/'.$file)) {
					$menu = array_merge($menu,include($dir.'/'.$file));
				}
			}
		}
		
		foreach (array_keys($menu) as $header) {
			$permissions[strtolower(str_replace(' ','_',$header))] = $header;
		}
		return $permissions;
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
	
	function show($view,$loc = null,$title = "") {
		global $user;
		$menu = array();
		$dir = BASE.'modules/administrationmodule/tasks';
		if (is_readable($dir)) {
			$dh = opendir($dir);
			while (($file = readdir($dh)) !== false) {
				if (substr($file,-4,4) == '.php' && is_readable($dir.'/'.$file) && is_file($dir.'/'.$file)) {
					$menu = array_merge($menu,include($dir.'/'.$file));
				}
			}
		}
		$template = new template('administrationmodule',$view,$loc);
		$template->assign('menu',$menu);
		$template->assign('moduletitle',$title);
		$template->assign('user',$user);
		
		$perms = administrationmodule::permissions();
		$template->assign('check_permissions',array_flip($perms));
		$template->register_permissions(array_keys($perms),pathos_core_makeLocation('administrationmodule'));
		
		$template->output($view);
	}
}

?>
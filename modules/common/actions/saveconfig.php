<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check("configure",$loc)) {
	$config = $db->selectObject($_POST['module']."_config","location_data='".serialize($loc)."'");
	$config = call_user_func(array($_POST['module']."_config","update"),$_POST,$config);
	$config->location_data = serialize($loc);
	if (isset($config->id)) $db->updateObject($config,$_POST['module']."_config");
	else $db->insertObject($config,$_POST['module']."_config");
	
	$container = $db->selectObject("container","internal='".serialize($loc)."'");
	$vconfig = array();
	if (isset($_POST['_viewconfig'])) {
		$opts = pathos_template_getViewConfigOptions($loc->mod,$container->view);
		foreach (array_keys($opts) as $o) {
			$vconfig[$o] = (isset($_POST['_viewconfig'][$o]) ? $_POST['_viewconfig'][$o] : 0);
		}
	}
	$container->view_data = serialize($vconfig);
	$db->updateObject($container,"container");
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
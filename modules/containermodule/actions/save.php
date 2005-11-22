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

if (!defined("PATHOS")) exit("");

$container = null;
$iloc = null;
$cloc = null;
if (isset($_POST['id'])) $container = $db->selectObject("container","id=" . $_POST['id']);
if ($container != null) {
	$iloc = unserialize($container->internal);
	$loc = unserialize($container->external);
	$cloc = unserialize($container->external);
	$cloc->int = $container->id;
}

if (pathos_permissions_check("add_module",$loc) || 
	($iloc != null && pathos_permissions_check("administrate",$iloc)) ||
	($cloc != null && pathos_permissions_check("edit_module",$cloc))
	) {
	
	$container = container::update($_POST,$container,$loc);
	
	if (isset($container->id)) {
		$db->updateObject($container,"container");
	} else {
		$db->insertObject($container,"container");
	}
	
	if ($container->is_existing == 0) {
		$iloc = unserialize($container->internal);
		$locref = $db->selectObject("locationref","module='".$iloc->mod."' AND source='".$iloc->src."'");
		$locref->description = (isset($_POST['description'])?$_POST['description']:'');
		$db->updateObject($locref,"locationref","module='".$iloc->mod."' AND source='".$iloc->src."'");
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
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

$container = null;
if (isset($_GET['id'])) $container = $db->selectObject("container","id=".$_GET['id']);
if ($container != null) {
	$iloc = unserialize($container->internal);
	$cloc = unserialize($container->external);
	$cloc->int = $container->id;

	if (pathos_permissions_check("delete_module",$loc) || pathos_permissions_check("delete_module",$cloc) || pathos_permissions_check("administrate",$iloc)) {
		
		container::delete($container,(isset($_GET['rerank']) ? 1 : 0));
		$db->delete("container","id=".$container->id);
		pathos_template_clear();
		
		// Check to see if its the last reference
		$locref = $db->selectObject("locationref","module='".$iloc->mod."' AND source='".$iloc->src."' AND internal='".$iloc->int."'");
		if ($locref->refcount == 0 && pathos_permissions_check("administrate",$iloc) && call_user_func(array($iloc->mod,"hasContent")) == 1) {
		
			$template = new template("containermodule","_lastreferencedelete",$loc);
			$template->assign("iloc",$iloc);
			$template->assign("redirect",pathos_flow_get());
			$template->output();
		} else pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
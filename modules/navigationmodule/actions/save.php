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

if ($user->is_admin == 1) {
	$section = null;
	if (isset($_POST['id'])) $section = $db->selectObject("section","id=" . $_POST['id']);
	
	require_once(BASE."datatypes/section.php");
	$section = section::update($_POST,$section);
	
	if (isset($section->id)) $db->updateObject($section,"section","id=" . $section->id);
	else {
		$db->increment("section","rank",1,"rank >= ".$section->rank." AND parent=".$section->parent);
		$section->id = $db->insertObject($section,"section");
		
		if (isset($_POST['pagetype'])) {
			$data = explode("_",$_POST['pagetype'],2);
			if ($data[0] == "blank") $db->insertObject($section,"section");
			else navigationmodule::process_section($section,$data[1]);
		}
	}
	
	pathos_flow_redirect();
}

?>
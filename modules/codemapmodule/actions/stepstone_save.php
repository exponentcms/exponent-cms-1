<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: stepstone_save.php,v 1.3 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$stepstone = null;
if (isset($_POST['id'])) $stepstone = $db->selectObject("codemap_stepstone","id=".$_POST['id']);
if ($stepstone) {
	$loc = unserialize($stepstone->location_data);
}

if (exponent_permissions_check("manage_steps",$loc)) {
	$stepstone = codemap_stepstone::update($_POST,$stepstone);
	$stepstone->milestone_id = $_POST['milestone_id'];
	$stepstone->location_data = serialize($loc);
	if (isset($stepstone->id)) {
		$db->updateObject($stepstone,"codemap_stepstone");
	} else {
		$db->insertObject($stepstone,"codemap_stepstone");
	}
	exponent_flow_redirect();
}

?>
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

$cat = null;
if (isset($_GET['id'])) $cat = $db->selectObject("category","id=".$_GET['id']);
if ($cat) {
	$loc = unserialize($cat->location_data);
} else {
	$loc->mod = $_GET['orig_module']; // Update module in location
}
// PERM CHECK
	$form = category::form($cat);
	$form->location($loc);
	$form->meta("module","categories");
	$form->meta("orig_module", $_GET['orig_module']);
	$form->meta("action","save");
	echo $form->toHTML();
// END PERM CHECK

?>
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

if ($user && $user->is_acting_admin == 1) {
	$page = null;
	if (isset($_POST['id'])) {
		$page = $db->selectObject("section_template","id=".$_POST['id']);
	}
	
	$page = section_template::update($_POST,$page);
	
	if (isset($page->id)) {
		$db->updateObject($page,"section_template");	
	} else {
		if ($page->parent != 0) {
			// May have to change the section rankings, because the user could have put us in between two previous pages
			$db->increment('section_template','rank',1,'parent='.$page->parent.' AND rank >= ' . $page->rank);
		}
		$db->insertObject($page,"section_template");
	}
	
	pathos_flow_redirect();
}

?>
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
	$section_a = $db->selectObject("section_template","rank=" . $_GET['a'] . " AND parent=" . $_GET['parent']);
	$section_b = $db->selectObject("section_template","rank=" . $_GET['b'] . " AND parent=" . $_GET['parent']);
	
	$original_a = $section_a->rank;
	$section_a->rank = $section_b->rank;
	$section_b->rank = $original_a;
	
	$db->updateObject($section_a,"section_template","id=" . $section_a->id);
	$db->updateObject($section_b,"section_template","id=" . $section_b->id);
	
	pathos_flow_redirect();
}

?>
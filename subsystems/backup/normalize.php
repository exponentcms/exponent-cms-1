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

if (!defined('PATHOS')) exit('');

// Normalizer Script
// This script normalizes the database and sets some things back in order.

// Normalize Section Rankings
function pathos_backup_normalize_sections($db,$parent = 0) {
	$sections = $db->selectObjects('section','parent='.$parent);
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($sections,'pathos_sorting_byRankAscending');
	$rank = 0;
	foreach ($sections as $s) {
		$s->rank = $rank;
		$db->updateObject($s,'section');
		pathos_backup_normalize_sections($db,$s->id); // Normalize children
		$rank++;
	}
}

pathos_backup_normalize_sections($db);

?>
<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

require_once('../exponent.php');
global $user;
global $db;

if ($user->is_admin) {
	print_r("<pre>");
	print_r("<b>Searching for unassigned modules (no source)</b><br><br>");
	$sectionrefs = $db->selectObjects('sectionref',"source=''");
	print_r("Removing: ".count($sectionrefs)." sectionref empties (no source)<br>");
	$db->delete('sectionref',"source=''");
	$locationref = $db->selectObjects('locationref',"source=''");
	print_r("Removing: ".count($locationref)." locationref empties (no source)<br>");
	$db->delete('locationref',"source=''");
	print_r("</pre>");
} else {
	echo SITE_403_HTML;
}

?>
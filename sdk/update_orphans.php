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
// upgrade sectionref's that have still point to missing sections (pages)
	print_r("<b>Searching for sectionrefs pointing to missing sections/pages to fix for Recycle Bin</b><br><br>");
	$sectionrefs = $db->selectObjects('sectionref',"refcount!=0");
	foreach ($sectionrefs as $sectionref) {
		if ($db->selectObject('section',"id='".$sectionref->section."'") == null) {
		// There is no section/page for sectionref so change the refcount
			$sectionref->refcount = 0;
			$db->updateObject($sectionref,"sectionref");
			print_r("Fixed: ".$sectionref->module." - ".$sectionref->source."<br>");
		}
	}
	print_r("</pre>");
} else {
	echo SITE_403_HTML;
}

?>
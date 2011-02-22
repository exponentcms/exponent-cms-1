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
	print_r("<b>Searching for missing locationrefs based on existing sectionrefs</b><br><br>");
	$sectionrefs = $db->selectObjects('sectionref',"is_original=0");
	print_r("Found: ".count($sectionrefs)." copies (not originals)<br>");
	foreach ($sectionrefs as $sectionref) {
		if ($db->selectObject('sectionref',"module='".$sectionref->module."' AND source='".$sectionref->source."' AND is_original='1'") == null) {
		// There is no original for sectionref so change it to the original
			$sectionref->is_original = 1;
			$db->updateObject($sectionref,"sectionref");
			print_r("Fixed: ".$sectionref->module." - ".$sectionref->source."<br>");
		}
	}
	print_r("</pre>");
} else {
	echo SITE_403_HTML;
}

?>
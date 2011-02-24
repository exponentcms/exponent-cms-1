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
// add missing locationref's based on existing sectionref's 
	print_r("<b>Searching for detached modules with no original</b><br><br>");
	$sectionrefs = $db->selectObjects('sectionref',1);
	foreach ($sectionrefs as $sectionref) {
		if ($db->selectObject('locationref',"module='".$sectionref->module."' AND source='".$sectionref->source."'") == null) {
		// There is no locationref for sectionref.  Populate reference
			$newLocRef->module   = $sectionref->module;
			$newLocRef->source   = $sectionref->source;
			$newLocRef->internal = $sectionref->internal;
			$newLocRef->refcount = $sectionref->refcount;
			$db->insertObject($newLocRef,"locationref");
			print_r("Copied: ".$sectionref->module." - ".$sectionref->source."<br>");
		}
	}
	print_r("</pre>");
} else {
	echo SITE_403_HTML;
}

?>
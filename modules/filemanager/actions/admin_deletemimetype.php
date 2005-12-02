<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

// Part of the Administration Control Panel : Files Subsystem category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('files_subsystem',pathos_core_makeLocation('administrationmodule'))) {
	$type = null;
	if (isset($_GET['type'])) {
		// GREP:SECURITY -- SQL is created from _GET parameter that is non-numeric.  Needs to be sanitized.
		$type = $db->selectObject('mimetype',"mimetype='".$_GET['type']."'");
	}
	if ($type) {
		$db->delete('mimetype',"mimetype='" . $type->mimetype . "'");
	}
	
	pathos_flow_redirect();
}

?>
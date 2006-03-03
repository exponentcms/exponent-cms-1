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

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('files_subsystem',exponent_core_makeLocation('administrationmodule'))) {
	$type = null;
	if (isset($_GET['type'])) {
		$type = $db->selectObject('mimetype',"mimetype='".preg_replace('/[^A-Za-z0-9\/]/','',$_GET['type'])."'");
	}
	if ($type) {
		$db->delete('mimetype',"mimetype='" . $type->mimetype . "'");
	}
	
	exponent_flow_redirect();
}

?>
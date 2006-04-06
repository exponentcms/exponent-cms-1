<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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
	$db->delete('mimetype');
	$mimes = include(BASE.'subsystems/files/mimetypes.php');
	$obj = null;
	foreach ($mimes as $type=>$name) {
		$obj->mimetype = $type;
		$obj->name = $name;
		$db->insertObject($obj,'mimetype');
	}
	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
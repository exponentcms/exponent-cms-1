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
	$mimetype = null;
//	if (isset($_GET['type'])) {
//		$mimetype = $db->selectObject('mimetype',"mimetype='".preg_replace('/[^A-Za-z0-9\/]/','',$_GET['type'])."'");
//	}
//	if ($mimetype) {
//		$db->delete('mimetype',"mimetype='" . $mimetype->mimetype . "'");
//	}
	if (isset($_GET['id'])) {
		$mimetype = $db->selectObject('mimetype',"id='".$_GET['id']."'");
	}
	if ($mimetype) {
		$db->delete('mimetype',"id='" . $mimetype->id . "'");
	}	
	exponent_flow_redirect();
}

?>
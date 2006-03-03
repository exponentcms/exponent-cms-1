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
	if (isset($_POST['oldmime'])) {
		$type = $db->selectObject('mimetype',"mimetype='".$_POST['oldmime']."'");
	}
	$is_existing = ($type != null);
	
	$type = mimetype::update($_POST,$type);
	
	if ($is_existing) {
		$db->updateObject($type,'mimetype',"mimetype='".$type->mimetype."'");
	} else {
		$db->insertObject($type,'mimetype');
	}
	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
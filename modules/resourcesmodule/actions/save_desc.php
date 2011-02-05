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

if (!defined('EXPONENT')) exit('');

if (isset($_POST['id'])) {
	$resourceconfig = $db->selectObject('resourcesmodule_config','id='.intval($_POST['id']));
	if ($resourceconfig) {
		$loc = unserialize($resourceconfig->location_data);
	}
}

if (exponent_permissions_check('edit',$loc)) {
	$resourceconfig = resourcesmodule_config::update($_POST,$resourceconfig);
	$resourceconfig->location_data = serialize($loc);
	if (isset($resourceconfig->id)) {
		$db->updateObject($resourceconfig,'resourcesmodule_config');
	} else {
		$db->insertObject($resourceconfig,'resourcesmodule_config');
	}
	exponent_flow_redirect();
	// if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	// exponent_workflow_post($resourceconfig,'resourcesmodule_config',$loc);
} else {
	echo SITE_403_HTML;
}

?>

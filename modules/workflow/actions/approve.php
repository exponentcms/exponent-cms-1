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

if (!defined('EXPONENT')) exit('');

// Sanitize required _GET parameters
$_GET['id'] = intval($_GET['id']);
$_GET['datatype'] = preg_replace('/[^A-Za-z0-9_]/','',$_GET['datatype']);

$info = $db->selectObject($_GET['datatype']."_wf_info","real_id=".$_GET['id']);
$object = $db->selectObject($_GET['datatype']."_wf_revision","wf_original=".$_GET['id']." AND wf_major=".$info->current_major." AND wf_minor=".$info->current_minor);
$state = unserialize($object->wf_state_data);

$rloc = unserialize($object->location_data);
if (exponent_permissions_check("approve",$rloc) || ($user && $user->id == $state[0][0])) {
	if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	exponent_workflow_processApproval($_GET['id'],$_GET['datatype'],SYS_WORKFLOW_APPROVE_APPROVE);
} else {
	echo SITE_403_HTML;
}

?>
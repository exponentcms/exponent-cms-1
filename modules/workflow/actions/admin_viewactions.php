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

// Part of the Administration Control Panel : Workflow category
$_GET['id'] = intval($_GET['id']);

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {

	$i18n = exponent_lang_loadFile('modules/workflow/actions/admin_viewactions.php');

	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	$actions = exponent_workflow_getActions($_GET['id']);

	// Workflow constants action names.
	$names = array(
		SYS_WORKFLOW_ACTION_POSTED=>$i18n['posted'],
		SYS_WORKFLOW_ACTION_EDITED=>$i18n['edited'],
		SYS_WORKFLOW_ACTION_APPROVED_APPROVED=>$i18n['approved_approved'],
		SYS_WORKFLOW_ACTION_APPROVED_EDITED=>$i18n['approved_edited'],
		SYS_WORKFLOW_ACTION_APPROVED_DENIED=>$i18n['approved_denied'],
		SYS_WORKFLOW_ACTION_APPROVED_FINAL=>$i18n['approved_final'],
		SYS_WORKFLOW_ACTION_DELETED=>$i18n['deleted'],
		SYS_WORKFLOW_ACTION_RESTARTED=>$i18n['restarted'],
		SYS_WORKFLOW_ACTION_IMPLICIT_APPROVAL=>$i18n['implicit_approval'],
		SYS_WORKFLOW_ACTION_POSTED_ADMIN=>$i18n['posted_admin'],
		SYS_WORKFLOW_ACTION_APPROVED_ADMIN=>$i18n['approved_admin']
	);

	$template = new template('workflow','_viewactions',$loc);
	$template->assign('actions',$actions);
	$template->assign('names',$names);
	$template->assign('policy_id',$_GET['id']);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
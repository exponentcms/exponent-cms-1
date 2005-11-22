<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

// Part of the Administration Control Panel : Workflow category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {

	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	$actions = pathos_workflow_getActions($_GET['id']);
	
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
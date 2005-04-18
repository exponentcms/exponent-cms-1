<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

	pathos_lang_loadDictionary('modules','workflow');

	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	$actions = pathos_workflow_getActions($_GET['id']);
	
	// Workflow constants action names.
	$names = array(
		SYS_WORKFLOW_ACTION_POSTED=>TR_WORKFLOW_POSTED,
		SYS_WORKFLOW_ACTION_EDITED=>TR_WORKFLOW_EDITED,
		SYS_WORKFLOW_ACTION_APPROVED_APPROVED=>TR_WORKFLOW_APPROVED_APPROVED,
		SYS_WORKFLOW_ACTION_APPROVED_EDITED=>TR_WORKFLOW_APPROVED_EDITED,
		SYS_WORKFLOW_ACTION_APPROVED_DENIED=>TR_WORKFLOW_APPROVED_DENIED,
		SYS_WORKFLOW_ACTION_APPROVED_FINAL=>TR_WORKFLOW_APPROVED_FINAL,
		SYS_WORKFLOW_ACTION_DELETED=>TR_WORKFLOW_DELETED,
		SYS_WORKFLOW_ACTION_RESTARTED=>TR_WORKFLOW_RESTARTED,
		SYS_WORKFLOW_ACTION_IMPLICIT_APPROVAL=>TR_WORKFLOW_IMPLICIT_APPROVAL,
		SYS_WORKFLOW_ACTION_POSTED_ADMIN=>TR_WORKFLOW_POSTED_ADMIN,
		SYS_WORKFLOW_ACTION_APPROVED_ADMIN=>TR_WORKFLOW_APPROVED_ADMIN
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
<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {

	pathos_lang_loadDictionary('standard','core');
	pathos_lang_loadDictionary('modules','workflow');
	
	$action = null;
	if (isset($_GET['id'])) $action = $db->selectObject('workflowaction','id='.$_GET['id']);
	
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$form = new form();
	$form->meta('module','workflow');
	$form->meta('action','action_save');
	if ($action) $form->meta('id',$action->id);
	else {
		$form->meta('type',$_GET['type']); // CHECK
		$form->meta('policy_id',$_GET['policy_id']); // CHECK
		$action->method = '';
		$action->parameters = '';
	}
	
	if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	
	$actions = pathos_workflow_getAvailableActions();
	uasort($actions,'strnatcmp');
	$form->register('method',TR_WORKFLOW_ACTION,new dropdowncontrol($action->method,$actions));
	$form->register('parameters',TR_WORKFLOW_PARAMETERS, new texteditorcontrol($action->parameters));
	$form->register('submit','', new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
	
	$template = new template('workflow','_form_editaction',$loc);
	$template->assign('is_edit',(isset($action->id)?1:0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>
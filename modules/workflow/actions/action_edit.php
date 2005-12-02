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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {

	$i18n = pathos_lang_loadFile('modules/workflow/actions/action_edit.php');
	
	$action = null;
	if (isset($_GET['id'])) {
		$action = $db->selectObject('workflowaction','id='.intval($_GET['id']));
	}
	
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$form = new form();
	$form->meta('module','workflow');
	$form->meta('action','action_save');
	if ($action) $form->meta('id',$action->id);
	else {
		$form->meta('type',$_GET['type']); // CHECK
		$form->meta('policy_id',intval($_GET['policy_id'])); // CHECK
		$action->method = '';
		$action->parameters = '';
	}
	
	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	
	$actions = pathos_workflow_getAvailableActions();
	uasort($actions,'strnatcmp');
	$form->register('method',$i18n['action'],new dropdowncontrol($action->method,$actions));
	$form->register('parameters',$i18n['parameters'], new texteditorcontrol($action->parameters));
	$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
	
	$template = new template('workflow','_form_editaction',$loc);
	$template->assign('is_edit',(isset($action->id)?1:0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
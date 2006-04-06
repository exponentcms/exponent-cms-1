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

define('SCRIPT_EXP_RELATIVE','modules/workflow/');
define('SCRIPT_FILENAME','assoc_edit.php');

include_once('../../exponent.php');

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {

	$i18n = exponent_lang_loadFile('modules/workflow/assoc_edit.php');
	
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	$form = new form();
	$policies = array();
	
	$_GET['m'] = preg_replace('/[^A-Za-z0-9_]/','',$_GET['m']);
	$_GET['s'] = preg_replace('/[^A-Za-z0-9_]/','',$_GET['s']);
	
	$assoc = $db->selectObject('approvalpolicyassociation',"module='".$_GET['m']."' AND source='".$_GET['s']."'");
	if (!$assoc) $assoc = $db->selectObject('approvalpolicyassociation',"module='".$_GET['m']."' AND is_global='1'");
	if (!$assoc) $assoc->policy_id = 0;
	
	if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	if (exponent_workflow_moduleUsesDefaultPolicy($_GET['m'],$_GET['s'])) $assoc->policy_id = 0;
	
	foreach ($db->selectObjects('approvalpolicy') as $pol) {
		$policies[$pol->id] = $pol->name;
	}
	uasort($policies,'strnatcasecmp');
	
	$realpol = array();
	$defaultpol = exponent_workflow_getDefaultPolicy($_GET['m']);
	if ($defaultpol) {
		$realpol = array(-1=>$i18n['no_policy'],0=>sprintf($i18n['default_policy'],$defaultpol->name));
	} else {
		$realpol = array(-1=>$i18n['no_policy'],0=>sprintf($i18n['default_policy'],$i18n['no_policy']));
	}
	foreach ($policies as $key=>$name) $realpol[$key] = $name;
	
	$form->register('policy',$i18n['policy'],new dropdowncontrol($assoc->policy_id,$realpol));
	$form->register('submit','',new buttongroupcontrol($i18n['save']));
	
	$form->action = URL_FULL.'modules/workflow/assoc_save.php';
	$form->meta('module','workflow');
	$form->meta('action','assoc_save');
	$form->meta('m',$_GET['m']);
	$form->meta('redirect',$_SERVER['HTTP_REFERER']);
	if (isset($_GET['s'])) {
		$form->meta('s',$_GET['s']);
	}
	
	
	$template = new template('workflow','_form_editassoc');
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
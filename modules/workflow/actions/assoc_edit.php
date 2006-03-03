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

// Part of the Administration Panel:Workflow category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {
	
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	$i18n = exponent_lang_loadFile('modules/workflow/actions/assoc_edit.php');
	
	$form = new form();
	$policies = array();
	
	$assoc = $db->selectObject('approvalpolicyassociation',"module='".preg_replace('/[^A-Za-z0-9_]/','',$_GET['m'])."' AND is_global=1");
	if (!$assoc) $assoc->policy_id = 0;
	
	foreach ($db->selectObjects('approvalpolicy') as $pol) {
		$policies[$pol->id] = $pol->name;
	}
	uasort($policies,'strnatcasecmp');
	
	$realpol = array(0=>$i18n['no_policy']);
	foreach ($policies as $key=>$pol) $realpol[$key] = $pol;
	
	$form->register('policy',$i18n['policy'],new dropdowncontrol($assoc->policy_id,$realpol));
	$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
	
	$form->meta('module','workflow');
	$form->meta('action','assoc_save');
	$form->meta('m',$_GET['m']);
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
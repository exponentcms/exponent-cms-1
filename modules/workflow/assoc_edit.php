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

define("SCRIPT_EXP_RELATIVE","modules/workflow/");
define("SCRIPT_FILENAME","assoc_edit.php");

require_once("../../pathos.php");

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {

	pathos_lang_loadDictionary('modules','workflow');
	pathos_lang_loadDictionary('standard','core');
	
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$policies = array();
	
	$assoc = $db->selectObject("approvalpolicyassociation","module='".$_GET['m']."' AND source='".$_GET['s']."'");
	if (!$assoc) $assoc = $db->selectObject("approvalpolicyassociation","module='".$_GET['m']."' AND is_global='1'");
	if (!$assoc) $assoc->policy_id = 0;
	
	if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
	if (pathos_workflow_moduleUsesDefaultPolicy($_GET['m'],$_GET['s'])) $assoc->policy_id = 0;
	
	foreach ($db->selectObjects("approvalpolicy") as $pol) {
		$policies[$pol->id] = $pol->name;
	}
	uasort($policies,"strnatcasecmp");
	
	$realpol = array();
	$defaultpol = pathos_workflow_getDefaultPolicy($_GET['m']);
	if ($defaultpol) {
		$realpol = array(-1=>TR_WORKFLOW_NOPOLICY,0=>sprintf(TR_WORKFLOW_DEFAULTPOLICY,$defaultpol->name));
	} else {
		$realpol = array(-1=>TR_WORKFLOW_NOPOLICY,0=>sprintf(TR_WORKFLOW_DEFAULTPOLICY,TR_WORKFLOW_NOPOLICY));
	}
	foreach ($policies as $key=>$name) $realpol[$key] = $name;
	
	$form->register("policy",TR_WORKFLOW_POLICY,new dropdowncontrol($assoc->policy_id,$realpol));
	$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE));
	
	$form->action = URL_FULL."modules/workflow/assoc_save.php";
	$form->meta("module","workflow");
	$form->meta("action","assoc_save");
	$form->meta("m",$_GET['m']);
	$form->meta("redirect",$_SERVER['HTTP_REFERER']);
	if (isset($_GET['s'])) {
		$form->meta("s",$_GET['s']);
	}
	
	
	$template = new template("workflow","_form_editassoc");
	$template->assign("form_html",$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
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

include_once("../../pathos.php");
define("SCRIPT_RELATIVE",PATH_RELATIVE."modules/workflow/");
define("SCRIPT_ABSOLUTE",BASE."modules/workflow/");
define("SCRIPT_FILENAME","assoc_edit.php");

if (!defined("PATHOS")) exit("");

if ($user && $user->is_acting_admin) {
	
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$policies = array();
	
	$assoc = $db->selectObject("approvalpolicyassociation","module='".$_GET['m']."' AND source='".$_GET['s']."'");
	if (!$assoc) $assoc = $db->selectObject("approvalpolicyassociation","module='".$_GET['m']."' AND is_global='1'");
	if (!$assoc) $assoc->policy_id = 0;
	
	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	if (pathos_workflow_moduleUsesDefaultPolicy($_GET['m'],$_GET['s'])) $assoc->policy_id = 0;
	
	foreach ($db->selectObjects("approvalpolicy") as $pol) {
		$policies[$pol->id] = $pol->name;
	}
	uasort($policies,"strnatcasecmp");
	
	$realpol = array();
	$defaultpol = pathos_workflow_getDefaultPolicy($_GET['m']);
	if ($defaultpol) {
		$realpol = array(-1=>"No Policy",0=>"Default: " . $defaultpol->name);
	} else {
		$realpol = array(-1=>"No Policy",0=>"Default: No Policy");
	}
	foreach ($policies as $key=>$name) $realpol[$key] = $name;
	
	$form->register("policy","Policy",new dropdowncontrol($assoc->policy_id,$realpol));
	$form->register("submit","",new buttongroupcontrol("Save"));
	
	$form->action = "http://".$_SERVER['HTTP_HOST'].PATH_RELATIVE."/modules/workflow/assoc_save.php";
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
	
	pathos_forms_cleanup();
}

?>
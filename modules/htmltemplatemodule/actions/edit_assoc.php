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

if (!defined("EXPONENT")) exit("");

// PERM CHECK
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	exponent_forms_initialize();
	
	$i18n = exponent_lang_loadFile('modules/htmltemplatemodule/actions/edit_assoc.php');
	
	$form = new form();
	$templates = $db->selectObjectsIndexedArray("htmltemplate");
	foreach ($db->selectObjects("htmltemplateassociation","module='".preg_replace('/[^A-Za-z0-9_]/','',$_GET['mod'])."'") as $existing) {
		unset($templates[$existing->template_id]);
	}
	foreach (array_keys($templates) as $tid) {
		$templates[$tid] = $templates[$tid]->title;
	}
	$form->meta("mod",$_GET['mod']);
	$form->meta("module","htmltemplatemodule");
	$form->meta("action","save_assoc");
	
	if (count($templates)) {
		$form->register("template_id",$i18n['template'], new dropdowncontrol(0,$templates));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],"",$i18n['cancel']));
	} else {
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1'/>".$i18n['no_unassoc']));
		$submit = new buttongroupcontrol($i18n['save'],"",$i18n['cancel']);
		$submit->disabled = true;
		$form->register("submit","",$submit);
	}
	
	$template = new template("htmltemplatemodule","_form_editassoc",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
// END PERM CHECK

?>
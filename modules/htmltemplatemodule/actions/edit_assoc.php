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

if (!defined("PATHOS")) exit("");

// PERM CHECK
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	pathos_lang_loadDictionary('modules','htmltemplatemodule');
	pathos_lang_loadDictionary('standard','core');
	
	$form = new form();
	$templates = $db->selectObjectsIndexedArray("htmltemplate");
	foreach ($db->selectObjects("htmltemplateassociation","module='".$_GET['mod']."'") as $existing) {
		unset($templates[$existing->template_id]);
	}
	foreach (array_keys($templates) as $tid) {
		$templates[$tid] = $templates[$tid]->title;
	}
	$form->meta("mod",$_GET['mod']);
	$form->meta("module","htmltemplatemodule");
	$form->meta("action","save_assoc");
	
	if (count($templates)) {
		$form->register("template_id",TR_HTMLTEMPLATEMODULE_TEMPLATE, new dropdowncontrol(0,$templates));
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,"",TR_CORE_CANCEL));
	} else {
		$form->register(uniqid(""),"",new htmlcontrol("<hr size='1'/>".TR_HTMLTEMPLATEMODULE_NOUNASSOC));
		$submit = new buttongroupcontrol(TR_CORE_SAVE,"",TR_CORE_CANCEL);
		$submit->disabled = true;
		$form->register("submit","",$submit);
	}
	
	$template = new template("htmltemplatemodule","_form_editassoc",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
// END PERM CHECK

?>
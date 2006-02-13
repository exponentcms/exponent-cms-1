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

$item = null;
$iloc = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject('calendar','id=' . intval($_GET['id']));	
	if ($item) {
		if (isset($_GET['date_id'])) {
			$item->eventdate = $db->selectObject('eventdate','id='.intval($_GET['date_id']));
		} else {
			$item->eventdate = $db->selectObject('eventdate','event_id='.$item->id);
		}
		$item->eventstart += $item->eventdate->date;
		$item->eventend += $item->eventdate->date;
		$loc = unserialize($item->location_data);
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
	}
}

if (($item == null && pathos_permissions_check('post',$loc)) ||
	($item != null && pathos_permissions_check('edit',$loc)) ||
	($iloc != null && pathos_permissions_check('edit',$iloc))
) {
	$form = calendar::form($item);
	$form->meta('action','save');
	$form->location($loc);
	
	$config = $db->selectObject('calendarmodule_config',"location_data='".serialize($loc)."'");
	if (!$config) {
		$config->enable_categories = 0;
		$config->enable_feedback = 0;
	}
	
	$i18n = pathos_lang_loadFile('modules/calendarmodule/actions/edit.php');
	
	if ($config->enable_categories == 1) {
		$ddopts = array();
		foreach ($db->selectObjects('category',"location_data='".serialize($loc)."'") as $opt) {
			$ddopts[$opt->id] = $opt->name;
		}
		uasort($ddopts,'strnatcmp');
		$form->registerAfter('eventend','category',$i18n['categories'],new dropdowncontrol($item->category_id,$ddopts));
		$form->registerBefore('category', null, '', new htmlcontrol('<br />'));
	}
	
	if ($config->enable_feedback == 1) {
		$form->registerBefore('submit', null,'', new htmlcontrol('<hr size="1" />'));
		$allforms = array();
		$allforms[''] = $i18n['no_feedback'];
		$allforms = array_merge($allforms, pathos_template_getFormTemplates('email'));
		$form->registerAfter('eventend', 'feedback_form', $i18n['feedback_form'], new dropdowncontrol($item->feedback_form, $allforms));
		$form->registerAfter('feedback_form', 'feedback_email', $i18n['feedback_email'], new textcontrol($item->feedback_email, 20));
		$form->registerBefore('feedback_form', null, '', new htmlcontrol('<br />'));
	}
	
	if (!defined('SYS_MODULES')) include_once(BASE.'subsystems/modules.php');
	$form->validationScript = pathos_modules_getJSValidationFile('calendarmodule','postedit');
	
	$template = new template('calendarmodule','_form_edit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',($item == null ? 0 : 1));
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>

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

// Sanitize required _GET parameters
$_GET['id'] = intval($_GET['id']);

// GREP:SECURITY -- SQL is created from _GET parameter that is non-numeric.  Needs to be sanitized.
$info = $db->selectObject($_GET['datatype']."_wf_info","real_id=".$_GET['id']);
$object = $db->selectObject($_GET['datatype']."_wf_revision","wf_original=".$_GET['id']." AND wf_major=".$info->current_major." AND wf_minor=".$info->current_minor);
$state = unserialize($object->wf_state_data);

$rloc = unserialize($object->location_data);
if (pathos_permissions_check("approve",$rloc) || ($user && $user->id == $state[0][0])) {
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$i18n = pathos_lang_loadFile('modules/workflow/actions/deny_comment.php');
	
	$form = new form();
	$form->meta('module','workflow');
	$form->meta('action','deny');
	$form->meta('id',$_GET['id']);
	$form->meta('datatype',$_GET['datatype']);
	$form->register('wf_comment',$i18n['deny_comment'],new texteditorcontrol());
	$form->register('submit','', new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
	
	$template = new template('workflow','_form_denycomment',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>
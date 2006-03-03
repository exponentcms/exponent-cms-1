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

// Part of the Administration Control Panel : Files Subsystem category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('files_subsystem',exponent_core_makeLocation('administrationmodule'))) {
	$type = null;
	if (isset($_GET['type'])) {
		$type = $db->selectObject('mimetype',"mimetype='".preg_replace('/[^A-Za-z0-9\/]/','',$_GET['type'])."'");
	}
	
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	$form = mimetype::form($type);
	$form->meta('module','filemanager');
	$form->meta('action','admin_savemimetype');
	
	$template = new template('filemanager','_form_editmimetype',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',isset($type->id)?1:0);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
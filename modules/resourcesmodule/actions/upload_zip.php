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

if (!defined('EXPONENT')) exit('');

$resource = null;
$iloc = null;

if (($resource == null && exponent_permissions_check('post',$loc)) ||
	($resource != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
) {
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	$form = new form();
	$form->location($loc);
	$form->meta('action','import_zip');
 	$form->meta('referrer',$_SERVER['HTTP_REFERER']);	
	$template = new template('resourcesmodule','_form_edit',$loc);
	
	$i18n = exponent_lang_loadFile('modules/resourcesmodule/actions/edit.php');
	$form->register('submit','',new buttongroupcontrol('Upload', '', 'Cancel'));
	$form->registerBefore('submit','file',$i18n['file'],new uploadcontrol());
	
	$dir = 'tmp/';
	if (!is_really_writable(BASE.$dir)) {
		$template->assign('dir_not_readable',1);
		$form->controls['submit']->disabled = true;
	} else {
		$template->assign('dir_not_readable',0);
	}
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', 0);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>

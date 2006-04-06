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

if ($user) {
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');
	if (!defined('SYS_FORMS')) require_once('subsystems/forms.php');
	exponent_forms_initialize();
	
	$i18n = exponent_lang_loadFile('modules/loginmodule/actions/changepass.php');
	
	$form = new form();
	$form->location($loc);
	$form->meta('action','savepass');
	$form->register('oldpass',$i18n['oldpass'],new passwordcontrol());
	$form->register('pass1',$i18n['pass1'],new passwordcontrol());
	$form->register('pass2',$i18n['pass2'],new passwordcontrol());
	
	$form->register('submit','',new buttongroupcontrol($i18n['change']));
	
	$template = new template('loginmodule','_form_changePassword',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();

} else {
	echo SITE_403_HTML;
}

?>
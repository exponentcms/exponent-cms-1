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
 
if (!defined('EXPONENT')) exit('');

if (SITE_ALLOW_REGISTRATION == 1) {
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');
	if (!defined('SYS_FORMS')) require_once('subsystems/forms.php');
	exponent_forms_initialize();
	
	$form = exponent_users_form(null);
	$form->meta('module','loginmodule');
	$form->meta('action','saveuser');
	if (SITE_USE_CAPTCHA && EXPONENT_HAS_GD) {
		$i18n = exponent_lang_loadFile('modules/loginmodule/actions/createuser.php');
		$form->registerBefore('submit',null,'',new htmlcontrol(sprintf($i18n['captcha_description'],'<img src="'.PATH_RELATIVE.'captcha.php" />'),false));
		$form->registerBefore('submit','captcha_string','',new textcontrol('',6));
	}
	
	$template = new template('loginmodule','_form_createUser',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
}
?>
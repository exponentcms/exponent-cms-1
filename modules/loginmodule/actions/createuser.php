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
 
if (!defined('PATHOS')) exit('');

if (SITE_ALLOW_REGISTRATION == 1) {
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');
	if (!defined('SYS_FORMS')) require_once('subsystems/forms.php');
	pathos_forms_initialize();
	
	$form = pathos_users_form(null);
	$form->meta('module','loginmodule');
	$form->meta('action','saveuser');
	if (SITE_USE_CAPTCHA && PATHOS_HAS_GD) {
		pathos_lang_loadDictionary('modules','loginmodule');
		$form->registerBefore('submit',null,'',new htmlcontrol(sprintf(TR_LOGINMODULE_CAPTCHADESC,'<img src="'.PATH_RELATIVE.'captcha.php" />'),false));
		$form->registerBefore('submit','captcha_string','',new textcontrol('',6));
	}
	
	$template = new template('loginmodule','_form_createUser',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
}
?>
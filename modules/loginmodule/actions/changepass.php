<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

if ($user) {
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');
	if (!defined('SYS_FORMS')) require_once('subsystems/forms.php');
	pathos_forms_initialize();
	
	$i18n = pathos_lang_loadFile('modules/loginmodule/actions/changepass.php');
	
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
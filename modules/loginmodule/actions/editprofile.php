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
	
	$form = exponent_users_form($user);
	$form->meta('module','loginmodule');
	$form->meta('action','saveprofile');
	
	$template = new template('loginmodule','_form_editProfile',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
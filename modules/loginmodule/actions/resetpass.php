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

$i18n = exponent_lang_loadFile('modules/loginmodule/actions/resetpass.php');

if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
exponent_forms_initialize();

$form = new form();
$form->meta('module','loginmodule');
$form->meta('action','resetpass_send');
$form->register('username',$i18n['username'],new textcontrol());
$form->register('submit','',new buttongroupcontrol($i18n['reset']));

$template = new template('loginmodule','_form_resetpass',$loc);
$template->assign('form_html',$form->toHTML());
$template->output();

exponent_forms_cleanup();

?>
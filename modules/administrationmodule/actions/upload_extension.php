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

// Part of the Extensions category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('extensions',pathos_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	$form = new form();
	
	$i18n = pathos_lang_loadFile('modules/administrationmodule/actions/upload_extension.php');
	
	$form->register(null,'',new htmlcontrol(pathos_core_maxUploadSizeMessage()));
	$form->register('mod_archive',$i18n['mod_archive'],new uploadcontrol());
	$form->register('submit','',new buttongroupcontrol($i18n['install']));
	$form->meta('module','administrationmodule');
	$form->meta('action','install_extension');

	$template = new template('administrationmodule','_form_uploadExt',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
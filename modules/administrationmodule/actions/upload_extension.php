<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	$form = new form();
	
	pathos_lang_loadDictionary('modules','administrationmodule');
	
	$form->register(null,'',new htmlcontrol(pathos_core_maxUploadSizeMessage()));
	$form->register('mod_archive',TR_ADMINISTRATIONMODULE_MODARCHIVE,new uploadcontrol());
	$form->register('submit','',new buttongroupcontrol(TR_ADMINISTRATIONMODULE_INSTALL));
	$form->meta('module','administrationmodule');
	$form->meta('action','install_extension');

	$template = new template('administrationmodule','_form_uploadExt',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>
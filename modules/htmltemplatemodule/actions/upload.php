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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('create',$loc)) {
	$t = null;
	if (isset($_GET['id'])) {
		$t = $db->selectObject('htmltemplate','id='.intval($_GET['id']));
	}
	
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$i18n = pathos_lang_loadFile('modules/htmltemplatemodule/actions/upload.php');
	
	$form = htmltemplate::form($t);
	$form->registerBefore('submit','file',$i18n['upload'],new uploadcontrol());
	$form->unregister('body');
	$form->meta('module','htmltemplatemodule');
	$form->meta('action','save_upload');
	
	$template = new template('htmltemplatemodule','_form_upload',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
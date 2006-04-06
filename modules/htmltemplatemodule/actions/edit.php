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

$t = null;
$loc = exponent_core_makeLocation('htmltemplatemodule');
if (isset($_GET['id'])) {
	$t = $db->selectObject('htmltemplate','id='.intval($_GET['id']));
}

if ((!$t && exponent_permissions_check('create',$loc)) || ($t  && exponent_permissions_check('edit',$loc))) {
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	$form = htmltemplate::form($t);
	$form->meta('module','htmltemplatemodule');
	$form->meta('action','save');
	
	$template = new template('htmltemplatemodule','_form_edit',$loc);
	$template->assign('is_edit',(isset($t->id) ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
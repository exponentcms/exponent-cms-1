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

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$policy = null;
	if (isset($_GET['id'])) {
		$policy = $db->selectObject('approvalpolicy','id='.intval($_GET['id']));
	}
	
	$form = approvalpolicy::form($policy);
	$form->meta('module','workflow');
	$form->meta('action','admin_savepolicy');
	
	$template = new template('workflow','_form_editpolicy',$loc);
	$template->assign('is_edit',(isset($policy->id) ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
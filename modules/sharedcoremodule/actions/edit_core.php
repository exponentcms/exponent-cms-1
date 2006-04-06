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

if (exponent_permissions_check('manage_core',exponent_core_makeLocation('sharedcoremodule'))) {
	$core = null;
	if (isset($_GET['id'])) {
		$core = $db->selectObject('sharedcore_core','id='.intval($_GET['id']));
	}
	
	$form = sharedcore_core::form($core);
	$form->meta('module','sharedcoremodule');
	$form->meta('action','save_core');
	
	$template = new template('sharedcoremodule','_form_editCore');
	$template->assign('is_edit',(isset($core->id) ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
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

$collection = null;
if (isset($_GET['id'])) {
	$collection = $db->selectObject('tag_collections','id='.intval($_GET['id']));
	$form_title = "Edit Tag Collection";
} else {
	$form_title = "New Tag Collection";
}

if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	$form = tag_collections::form($collection);
	$form->meta('module','tags');
	$form->meta('action','save_collection');
	
	$template = new template("tags","_edit_collection");
	$template->assign('form_title', $form_title);
	$template->assign('form_html', $form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
